<?php

namespace App\Http\Controllers\Host;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Broadcast;
use App\HostComment;
use Cookie;

class HostDashboardController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->middleware('auth');
        // I should probably use a gate or policy?
        $this->middleware('role:host');
    }

    // Add specifics
    public function index(Request $reqest)
    {
        $broadcast = Broadcast::where('enabled', 1)
            ->oldest('starts_at')
            ->first();

        $broadcast->configure();

        if (!isset($broadcast->sermon)) {
            $broadcast->loadSermon();
        }

        $hostComments = HostComment::with('user')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get()
            ->reverse()
            ->values();

    	return response()->json([ 
            'broadcast' => $broadcast,
            'host_comments' => $hostComments
        ]);
    }
}
