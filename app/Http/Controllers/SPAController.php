<?php

namespace App\Http\Controllers;

use App\Services\User\GetCurrentUser;
use App\Services\IntroVideo\GetIntroVideo;
use App\Services\Broadcast\GetAllBroadcasts;
use Carbon\Carbon;

class SPAController extends Controller
{
    /**
     * Return view with inital app data.
     */
    public function __invoke(GetCurrentUser $getCurrentUser, GetIntroVideo $getIntroVideo, GetAllBroadcasts $getAllBroadcasts)
    {	
        return view('app')->with([
            'user' => $getCurrentUser->execute(),
            'intro_video' => $getIntroVideo->execute(),
            'broadcasts' => $getAllBroadcasts->execute()
        ]);
    }
}
