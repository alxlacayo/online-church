<?php

namespace App\Http\Controllers\Host;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\HostCommentCreated;
use App\HostComment;

class HostCommentController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:host');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        if ($request->has('maxid')) {
            $maxId = $request->input('maxid');
            $limit = 10;
            
            $comments = HostComment::with('user')
                ->where('id', '<', $maxId)
                ->orderBy('id', 'desc')
                ->take($limit)
                ->get()
                ->reverse()
                ->values();

            return response()->json(['comments' => $comments, 'limit' => $limit]);
        }       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $comment = new HostComment;
        $comment->text = $request->input('text');
        $comment->user()->associate($request->user());

        // change to try catch /// return something
        if ($comment->save()) {

            broadcast(new HostCommentCreated($comment->toArray()))->toOthers();

            $comment->local_id = $request->input('commentId');

            return response()->json($comment);
        } else {
            return response()->json(['message' => 'Something happened. Try again.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
