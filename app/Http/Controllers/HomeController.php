<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Broadcast;
use App\Sermon;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display resources for home page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        $timeThreshold = Carbon::now()->subWeek();

        $sermons = Sermon::where('publish_on', '<=', $timeThreshold)
            ->latest('publish_on')
        	->take(6)
        	->get();

        return response()->json([
        	'sermons' => $sermons
        ]);
    }
}
