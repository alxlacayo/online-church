<?php

declare(strict_types=1);

namespace App\Events\Broadcast;

use App\Broadcast;

class BroadcastErrorFound
{
    /**
     * @var \App\Broadcast
     */
    public $broadcast;

    public function __construct(Broadcast $broadcast)
    {   
        $this->broadcast = $broadcast;
    }
}