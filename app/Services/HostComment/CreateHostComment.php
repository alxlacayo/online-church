<?php

declare(strict_types=1);

namespace App\Services\HostComment;

use App\Events\HostComment\HostCommentCreated;
use App\User;
use App\HostComment;

class CreateHostComment
{

    public function execute(User $user, string $text) 
    {   
        try {
            $hostComment = new HostComment;
            $hostComment->text = $text;
            $hostComment->user()->associate($user);
            $hostComment->save();

            broadcast(new HostCommentCreated($hostComment))->toOthers();

            return $hostComment;

        } catch (\Exception $e) {
            // probably throw exception.
            \Log::error($e->getMessage());
        }        
    }
}
