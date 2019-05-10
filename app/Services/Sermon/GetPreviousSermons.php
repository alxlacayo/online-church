<?php

declare(strict_types=1);

namespace App\Services\Sermon;

use Illuminate\Database\Eloquent\Collection;
use App\Sermon;

class GetPreviousSermons 
{

    public function execute() : Collection
    {   
        return Sermon::where('publish_on', '<=', NOW())
            ->orderBy('publish_on', 'desc')
            ->skip(1)
            ->take(6)
            ->get();
    }
}
