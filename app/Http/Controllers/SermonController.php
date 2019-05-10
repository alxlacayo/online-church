<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Services\Sermon\GetSermon;
use App\Services\Sermon\GetAllSermons;
use App\Services\Sermon\GetPreviousSermons;

class SermonController extends Controller
{

    public function get(string $id, GetSermon $service) : JsonResponse
    {   
        $sermon = $service->execute($id);

        return response()->json($sermon);
    }

    public function getAll(GetAllSermons $service) : JsonResponse
    {        
        $pager = $service->execute();

        return response()->json($pager);
    }

    public function getPrevious(GetPreviousSermons $service) : JsonResponse
    {   
        $sermons = $service->execute();

        return response()->json([
            'sermons' => $sermons
        ]);
    }
}
