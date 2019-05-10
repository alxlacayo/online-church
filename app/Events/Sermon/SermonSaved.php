<?php

namespace App\Events\Sermon;

use App\Sermon;

class SermonSaved
{
    /**
     * The sermon that was saved.
     *
     * @var \App\Sermon
     */
    public $sermon;

    /**
     * Create a new event instance.
     *
     * @param  \App\Sermon  $sermon
     * @return void
     */
    public function __construct(Sermon $sermon)
    {   
        $this->sermon = $sermon;
    }
}
