<?php

declare(strict_types=1);

namespace App\Http\Controllers\Host;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class AuthorizeController extends Controller
{

    public function __invoke(Request $request) : JsonResponse
    {
    	$user = $request->user();

    	if ($user === null) {
    		return response()->json([], 401);
    	}

    	if (! $user->isHost()) {
    		return response()->json([], 403);
    	}

    	return response()->json([], 200);
    }
}
