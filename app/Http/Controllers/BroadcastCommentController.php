<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Broadcast;
use App\BroadcastComment;
use App\Events\BroadcastCommentCreated;

class BroadcastCommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Broadcast  $broadcast
     * @return \Illuminate\Http\Response
     */
    public function index(Broadcast $broadcast)
    {
        $broadcast->load(['comments' => function($query) use ($broadcast) {
            $query->with('user.roles')
                ->where('created_at', '>=', $broadcast->opensAt());
        }]);

        return $broadcast->comments; 
    }

    /**
     * Store a newly created broadcast comment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $broadcastId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $broadcastId)
    {      
        // Need to add validation for text

        $broadcastComment = new BroadcastComment;
        $broadcastComment->text = $request->input('text');
        $broadcastComment->user()->associate($request->user());
        $broadcastComment->broadcast_id = $broadcastId;

        // change to try catch? // return something
        if ($broadcastComment->save()) {
            broadcast(new BroadcastCommentCreated($broadcastComment->toArray()))->toOthers();
            
            $broadcastComment->local_id = $request->input('commentId');

            return response()->json($broadcastComment);
        } else {
            return response()->json(['message' => 'Something happened. Try again.'], 500);
        }
    }
}
