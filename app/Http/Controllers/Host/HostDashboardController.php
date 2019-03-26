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

    /**
     * Return the data to display inside the host panel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $reqest)
    {
        $hostComments = HostComment::with('user')
            ->orderBy('id', 'desc')
            ->take(20)
            ->get()
            ->reverse()
            ->values();

    	return response()->json([
            'host_comments' => $hostComments
        ]);
    }
}
