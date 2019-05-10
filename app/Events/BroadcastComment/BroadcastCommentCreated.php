<?php

declare(strict_types=1);

namespace App\Events\BroadcastComment;

use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\BroadcastComment;

class BroadcastCommentCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    /**
     * @var \App\BroadcastComment
     */
    public $comment;

    public function __construct(BroadcastComment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastWith() : array
    {
        return $this->comment->toArray();
    }

    public function broadcastOn() : Channel
    {
        $channel = 'broadcast.chat.' . $this->comment->broadcast_id;

        return new Channel($channel);
    }
}
