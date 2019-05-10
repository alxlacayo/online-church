<?php 

declare(strict_types=1);

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\HostComment\GetAllHostCommentsRequest;
use App\Services\HostComment\GetAllHostComments;
use App\Services\Broadcast\GetAllBroadcasts;
use App\Broadcast;
use App\Sermon;
use DB;

class PlayController extends Controller
{
    //public function index(GetAllHostCommentsRequest $request, GetAllHostComments $service) : JsonResponse
    public function index(GetAllBroadcasts $service)
    {
        $b = Broadcast::find(30);

        $b->day = 'tuesday';
        $b->time = '01:10:00';
        
        $b->save();

        // $s = Sermon::find(4);
        // $s->title = "Fisher of People";
        // $s->save();
    }
}
