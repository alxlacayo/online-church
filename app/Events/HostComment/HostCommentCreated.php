<?php

declare(strict_types=1);

namespace App\Events\HostComment;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\HostComment;

class HostCommentCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    /**
     * @var \App\HostComment
     */
    public $comment;

    public function __construct(HostComment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastWith() : array
    {
        return $this->comment->toArray();
    }

    public function broadcastOn() : PresenceChannel
    {
        return new PresenceChannel('host.chat');
    }
}
