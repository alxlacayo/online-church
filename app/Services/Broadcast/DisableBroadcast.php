<?php

declare(strict_types=1);

namespace App\Services\Broadcast;

use App\Events\Broadcast\BroadcastChanged;
use App\Broadcast;
use Log;

class DisableBroadcast 
{

    public function execute(Broadcast $broadcast) : void
    {
        try {
            $broadcast->enabled = false;

            $broadcast->withoutEvents(function() use ($broadcast) {
                $broadcast->save();
            });

            broadcast(new BroadcastChanged($broadcast));
            
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
