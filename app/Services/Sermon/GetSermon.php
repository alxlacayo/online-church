<?php

declare(strict_types=1);

namespace App\Services\Sermon;

use App\Sermon;

class GetSermon 
{
    /**
     * @param mixed  $id
     */
    public function execute($id) : Sermon
    {   
        return Sermon::findOrFail($id);
    }
}
