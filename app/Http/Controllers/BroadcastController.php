<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Broadcast\GetAllBroadcasts;
use App\Services\Broadcast\GetBroadcast;
use App\Broadcast;

class BroadcastController extends Controller
{

    public function getAll(GetAllBroadcasts $getAllBroadcasts) : JsonResponse
    {
        $broadcasts = $getAllBroadcasts->execute();

        return response()->json($broadcasts);
    }

    public function get(string $id, GetBroadcast $getBroadcast) : JsonResponse
    {
        $broadcast = $getBroadcast->execute($id);

        return response()->json($broadcast);
    }

    public function getTimeElapsed(Broadcast $broadcast) : JsonResponse
    {
        return response()->json([
            'time_elapsed' => $broadcast->getTimeElapsed()
        ]);
    }
}
