<?php

declare(strict_types=1);

namespace App\Http\Controllers\Host;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\HostComment\GetAllHostCommentsRequest;
use App\Http\Requests\HostComment\CreateHostCommentRequest;
use App\Services\HostComment\GetAllHostComments;
use App\Services\HostComment\CreateHostComment;

class HostCommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:host');
    }

    public function getAll(GetAllHostCommentsRequest $request, GetAllHostComments $service) : JsonResponse
    {   
        $maxId = $request->input('maxid');
        $limit = 20;
        
        return response()->json([
            'comments' => $service->execute($maxId, $limit),
            'limit' => $limit
        ]);
    }

    public function create(CreateHostCommentRequest $request, CreateHostComment $service) : JsonResponse
    {   
        $user = $request->user();
        $text = $request->input('text');
        $localCommentId = $request->input('localCommentId');

        $hostComment = $service->execute($user, $text);

        return response()->json([
            'local_comment_id' => $localCommentId,
            'comment' => $hostComment
        ]);
    }
}
