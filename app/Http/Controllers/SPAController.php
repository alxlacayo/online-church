<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IntroVideo;
use App\Broadcast;
use Auth;

class SPAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
    	$user = Auth::user();

        if (Auth::check()) {
            $user->makeVisible('email');
            $user->setProfilePictureSize('large');
        }

        $nextBroadcast = Broadcast::where('enabled', 1)
            ->oldest('starts_at')
            ->first();
            
        $nextBroadcast->configure();

        $introVideo = IntroVideo::where('enabled', 1)
            ->orderByDesc('id')
            ->first();

        return view('app')->with([
            'user' => $user,
            'intro_video' => $introVideo,
            'next_broadcast' => $nextBroadcast
        ]);
    }
}
