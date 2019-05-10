<?php

declare(strict_types=1);

namespace App\Services\HostComment;

use Illuminate\Database\Eloquent\Collection;
use App\HostComment;

class GetAllHostComments
{

    public function execute(string $maxId = null, int $limit) : Collection
    {   
    	$hostComments = HostComment::with('user');

    	if (! is_null($maxId)) {
    		$hostComments->where('id', '<', (int) $maxId);
    	}

        return $hostComments->orderByDesc('id')
            ->take($limit)
            ->get()
            ->reverse()
            ->values();
    }
}
