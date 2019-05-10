<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Broadcast\CreateBroadcastRequest;
use App\Services\Broadcast\CreateBroadcast;

class BroadcastController extends Controller
{
	// NEED TO SECURE THIS ENDPOINT!!
    public function create(CreateBroadcastRequest $request, CreateBroadcast $service) : JsonResponse
    {   
        $broadcast = $service->execute($request->validated());

        return response()->json($broadcast);
    }
}
