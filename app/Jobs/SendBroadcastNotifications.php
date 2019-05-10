<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\Broadcast\BroadcastErrorFound;
use App\Events\Broadcast\BroadcastOpen;
use App\Events\Broadcast\BroadcastInProgress;
use App\Events\Broadcast\BroadcastClosed;
use Carbon\Carbon;
use App\Broadcast;

class SendBroadcastNotifications
{
    use Dispatchable, Queueable;

    public function handle() : void
    {
        $now = Carbon::now()->second(0)->microseconds(0);

        $broadcasts = Broadcast::where('enabled', 1)->get();

        foreach ($broadcasts as $broadcast) {
            
            if (($broadcast->opens_at === null) || ($broadcast->starts_at === null) || ($broadcast->closes_at === null)) {
                event(new BroadcastErrorFound($broadcast));

                \Log::debug("Broadcast Error found on broadcast id $broadcast->id on $now");
            }

            if ($now == $broadcast->opens_at) {
                broadcast(new BroadcastOpen($broadcast));

            } else if ($now == $broadcast->starts_at) {
                broadcast(new BroadcastInProgress($broadcast));

            } else if (($broadcast->closes_at)->isPast()) {
                broadcast(new BroadcastClosed($broadcast));
            }
        }
    }
}
