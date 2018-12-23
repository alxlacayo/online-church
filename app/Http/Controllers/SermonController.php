<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sermon;
use Carbon\Carbon;

class SermonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $pager = Sermon::latest('publish_on')
            ->where('publish_on', '<=', Carbon::now())
            ->simplePaginate(10, ['id', 'title', 'image', 'publish_on']);

        return response()->json($pager);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $sermon
     * @return \Illuminate\Http\Response
     */
    public function show(Sermon $sermon)
    {
        return response()->json($sermon);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sermon  $sermon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sermon $sermon)
    {
        $description = htmlentities( $request->input('description') );

        $sermon->description = $description;

        $sermon->save();
    }
}
