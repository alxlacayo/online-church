<?php

declare(strict_types=1);

namespace App\Services\Broadcast;

use Illuminate\Database\Eloquent\Collection;
use App\Broadcast;
use Carbon\Carbon;

class GetAllBroadcasts 
{

    public function execute(bool $hideDisabled = true, string $orderByColumn = 'starts_at', string $direction = 'asc') : Collection
    {   
        $broadcasts = Broadcast::with('sermon:id,title,image')
        	->when($hideDisabled, function($q) {
        		return $q->enabled();
        	})
        	->orderBy($orderByColumn, $direction)
        	->get();

        // $broadcasts->transform(function($broadcast, $key) {
        //     if ($broadcast->status == 'broadcast_closed' && $broadcast->sermon !== null) {
        //         $sermon = clone $broadcast->sermon;
        //         $sermon->addHidden(['description', 'notes']);

        //         $broadcast->setRelation('sermon', $sermon);
        //     }

        //     return $broadcast;
        // });

        return $broadcasts;
    }
}
