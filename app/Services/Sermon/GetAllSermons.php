<?php

declare(strict_types=1);

namespace App\Services\Sermon;

use Illuminate\Pagination\Paginator;
use App\Sermon;

class GetAllSermons 
{

    public function execute() : Paginator
    {   
        return Sermon::latest('publish_on')
            ->where('publish_on', '<=', NOW())
            ->simplePaginate(10, ['id', 'title', 'image', 'publish_on']);
    }
}
