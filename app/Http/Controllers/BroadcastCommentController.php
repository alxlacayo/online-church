<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\BroadcastComment\CreateBroadcastCommentRequest;
use App\Services\BroadcastComment\CreateBroadcastComment;
use App\Broadcast;
use App\BroadcastComment;

class BroadcastCommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(CreateBroadcastCommentRequest $request, CreateBroadcastComment $service, Broadcast $broadcast) : JsonResponse
    {
        $user = $request->user();
        $text = $request->input('text');
        $localCommentId = $request->input('localCommentId');

        $broadcastComment = $service->execute($broadcast, $user, $text);
        
        return response()->json([
            'local_comment_id' => $localCommentId,
            'comment' => $broadcastComment
        ]);
    }
}
