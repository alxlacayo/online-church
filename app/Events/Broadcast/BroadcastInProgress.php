<?php

declare(strict_types=1);

namespace App\Events\Broadcast;

use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Broadcast;

class BroadcastInProgress implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    /**
     * @var \App\Broadcast
     */
    public $broadcast;

    public function __construct(Broadcast $broadcast)
    {
        $this->broadcast = $broadcast;
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith() : array
    {
        $this->broadcast->load('sermon');

        return $this->broadcast->toArray();
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn() : Channel
    {
        return new Channel('main');
    }
}
