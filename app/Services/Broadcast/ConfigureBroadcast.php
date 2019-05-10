<?php

declare(strict_types=1);

namespace App\Services\Broadcast;

use App\Events\Broadcast\BroadcastChanged;
use App\Broadcast;
use App\Sermon;
use Carbon\Carbon;
use Log;

class ConfigureBroadcast 
{

    public function execute(Broadcast $broadcast) : void
    {   
        try {
            if ($broadcast->live) {
                $this->setDataForLiveBroadcast($broadcast);
            } else {
                $this->setDataForBroadcast($broadcast);
            }

            $broadcast->withoutEvents(function() use ($broadcast) {
                $broadcast->save();
            });

            if (! $broadcast->live) {
                $broadcast->load('sermon');
            }

            broadcast(new BroadcastChanged($broadcast));
            
        } catch (\Exception $e) {
            // probably throw exception.
            Log::error($e->getMessage());
        }
    }

    private function setDataForLiveBroadcast(Broadcast $broadcast, bool $addWeekToStartsAt = false) : void
    {
        // Only set the starts_at timestamp if the live broadcast is
        // recurring. If the broadcast is not recurring then it was created
        // with the intention of being a one-time event and a fully formatted
        // starts_at time was inputted by an admin.
        if ($broadcast->recurring) {
            $this->setStartsAtTime($broadcast, $addWeekToStartsAt);
        }

        $this->setOpensAtTime($broadcast);
        $this->setClosesAtTime($broadcast);

        $broadcast->sermon_id = null;
        
        if (($broadcast->closes_at)->isPast() && $broadcast->recurring) {
            $this->setDataForLiveBroadcast($broadcast, true);
        }
    }

    private function setDataForBroadcast(Broadcast $broadcast, bool $addWeekToStartsAt = false) : void
    {
        $this->setStartsAtTime($broadcast, $addWeekToStartsAt);
        $this->setOpensAtTime($broadcast);
        $this->setSermon($broadcast);
        $this->setClosesAtTime($broadcast);

        if (($broadcast->closes_at)->isPast()) {
            $this->setDataForBroadcast($broadcast, true);
        }
    }

    private function setStartsAtTime(Broadcast $broadcast, bool $addWeek) : void
    {
        $time = $broadcast->day . ' ' . $broadcast->time;
        $date = Carbon::createFromFormat('l H:i:s', $time, 'America/Chicago');

        if ($addWeek) {
            $date->addWeek();
        }
            
        $broadcast->starts_at = $date->timezone('utc');
    }

    private function setOpensAtTime(Broadcast $broadcast) : void
    {
        $broadcast->opens_at = ($broadcast->starts_at)->copy()->subMinutes(env('MINUTES_BEFORE_START', '10'));
    }

    private function setClosesAtTime(Broadcast $broadcast) : void
    {
        $broadcast->closes_at = $broadcast->live
            ? ($broadcast->starts_at)->copy()
                ->addMinutes(env('LIVE_BROADCAST_DURATION', '80'))
                ->addMinutes(env('MINUTES_AFTER_END', '10'))
            : ($broadcast->starts_at)->copy()
                ->addSeconds($broadcast->sermon->duration)
                ->addMinutes(env('MINUTES_AFTER_END', '10'));
    }

    private function setSermon(Broadcast $broadcast) : void
    {
        $sermon = Sermon::where('publish_on', '<=', $broadcast->starts_at)
            ->orderBy('publish_on', 'DESC')
            ->first();

        $broadcast->sermon()->associate($sermon);
    }
}
