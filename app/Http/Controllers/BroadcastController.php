<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Broadcast;
use App\Sermon;

class BroadcastController extends Controller
{
    /**
     * Return the broadcast schedule.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $broadcasts = Broadcast::where('enabled', 1)
            ->oldest('starts_at')
            ->get();

        $firstDate = $broadcasts->first()->starts_at;
        // Subtract a week so that the sermon's publish_on date is in range.
        $firstDate->subWeek();

        $lastDate = $broadcasts->last()->starts_at;

        $sermons = Sermon::select('title', 'publish_on')
            ->where('publish_on', '>', $firstDate)
            ->where('publish_on', '<=', $lastDate)
            ->oldest('publish_on')
            ->get();

        //$sermons = $sermons->reverse()->values();

        $broadcasts = $broadcasts->map(function($broadcast, $key) use ($sermons) {
            if ($broadcast->live) {
                return $broadcast;
            }

            $sermon = $sermons->first(function($sermon, $key) use ($broadcast) {
                $broadcastStartsAt = $broadcast->starts_at;
                $sermonStartDate = $sermon->publish_on;
                $sermonEndDate = $sermonStartDate->copy()->addWeek();

                return $broadcastStartsAt >= $sermonStartDate
                    && $broadcastStartsAt < $sermonEndDate;
            });

            $broadcast->name = is_null($sermon) ? 'TBA' : $sermon->title;

            return $broadcast;
        });

        return response()->json($broadcasts);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Broadcast  $broadcast
     * @return \Illuminate\Http\Response
     */
    public function show(Broadcast $broadcast)
    {
        $broadcast->configure();

        return response()->json($broadcast);
    }
}
