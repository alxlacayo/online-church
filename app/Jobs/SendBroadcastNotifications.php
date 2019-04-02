<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\BroadcastOpen;
use App\Events\BroadcastStarting;
use App\Events\BroadcastClosed;
use App\Broadcast;
use App\Sermon;
use Carbon\Carbon;

class SendBroadcastNotifications
{
    use Dispatchable, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = Carbon::now()->second(0)->microseconds(0);

        // Copy $now and add minutes so we can check if
        // now + MINUTES_BEFORE_START == publish_on/start_at time
        $possibleStartTime = $now->copy()->addMinutes(Broadcast::MINUTES_BEFORE_START);

        $sermon = Sermon::where('publish_on', '<=', $possibleStartTime)
            ->latest('publish_on')
            ->first();

        $broadcasts = Broadcast::where('starts_at', '<=', $possibleStartTime)
            ->where('enabled', 1)
            ->oldest('starts_at')
            ->get();

        foreach ($broadcasts as $broadcast) {
            $durationInSeconds = $broadcast->live ? Broadcast::LIVE_BROADCAST_DURATION : $sermon->duration;
            $opensAt = $broadcast->opensAt();
            $startsAt = $broadcast->starts_at;
            $closesAt = $broadcast->closesAt($durationInSeconds);

            if ($now == $opensAt) {
                if (! $broadcast->live) { 
                    $broadcast->sermon = $sermon;
                }

                $broadcast->status = Broadcast::BROADCAST_OPEN;

                broadcast(new BroadcastOpen($broadcast->toArray()));

            } else if ($now == $startsAt) {
                if (! $broadcast->live) { 
                    $broadcast->sermon = $sermon;
                }

                $broadcast->status = Broadcast::BROADCAST_IN_PROGRESS;

                broadcast(new BroadcastStarting($broadcast->toArray()));

            } else if ($closesAt->isPast()) {
                // Save broadcast so our listener can update
                // the new starts_at timestamp

                $broadcast->save();
                $broadcast->status = Broadcast::BROADCAST_CLOSED;

                broadcast(new BroadcastClosed($broadcast->toArray()));
            }
        }
    }
}
