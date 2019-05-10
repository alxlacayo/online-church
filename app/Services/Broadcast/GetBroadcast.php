<?php

declare(strict_types=1);

namespace App\Services\Broadcast;

use App\Broadcast;

class GetBroadcast 
{
    /**
     * @param mixed  $id
     */
    public function execute($id) : array
    {   
    	$broadcast = Broadcast::findOrFail($id);

        if (! $broadcast->live) {
            $broadcast->load('sermon');
        }

        $broadcast->load(['comments' => function($query) use ($broadcast) {
            $query->with('user')
                ->where('created_at', '>=', $broadcast->opens_at);
        }]);

        $broadcastComments = $broadcast->getRelation('comments');

        $broadcast->unsetRelation('comments');
        $broadcast->append('time_elapsed');

        return [
            'broadcast' => $broadcast,
            'comments' => $broadcastComments
        ];
    }
}
