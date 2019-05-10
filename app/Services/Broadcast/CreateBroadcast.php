<?php

declare(strict_types=1);

namespace App\Services\Broadcast;

use Carbon\Carbon;
use App\Broadcast;

class CreateBroadcast 
{

    public function execute(array $data) : Broadcast
    {   
        // If the starts_at value is set we need to parse it and
        // convert it from American/Chicago time to utc for database
        // storage. When creating a one-time broadcast admins enter
        // the time the broadcast should start in central time.
        if (array_key_exists('starts_at', $data)) {
            $data['starts_at'] = (Carbon::parse($data['starts_at'], 'America/Chicago'))->tz('utc');
        }

        $broadcast = new Broadcast;
        $broadcast->fill($data);
        $broadcast->save();

        return $broadcast;
     }
}
