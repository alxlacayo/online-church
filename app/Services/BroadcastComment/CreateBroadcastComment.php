<?php

declare(strict_types=1);

namespace App\Services\BroadcastComment;

use App\Events\BroadcastComment\BroadcastCommentCreated;
use App\User;
use App\Broadcast;
use App\BroadcastComment;

class CreateBroadcastComment
{

    public function execute(Broadcast $broadcast, User $user, string $text) 
    {   
        try {
            $broadcastComment = new BroadcastComment;
            $broadcastComment->text = $text;
            $broadcastComment->user()->associate($user);
            $broadcastComment->broadcast_id = $broadcast->id;
            $broadcastComment->save();

            broadcast(new BroadcastCommentCreated($broadcastComment))->toOthers();

            return $broadcastComment;

        } catch (\Exception $e) {
            // probably throw exception.
            \Log::error($e->getMessage());
        }        
    }
}
