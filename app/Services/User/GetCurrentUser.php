<?php

declare(strict_types=1);

namespace App\Services\User;

use App\User;
use Auth;

class GetCurrentUser
{

    public function execute() : ?User
    {   
    	$user = Auth::user();

        if (Auth::check()) {
            $user->makeVisible('email');
            $user->setProfilePictureSize('large');
        }

        return $user;
    }
}
