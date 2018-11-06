<?php

use Illuminate\Database\Seeder;
use App\Broadcast;
use Carbon\Carbon;

class BroadcastsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    		$broadcasts = [ 
    			['name' => 'Monday 8:00am', 'day' => 'monday', 'time' => '08:00:00'],
                ['name' => 'Monday 10:00am', 'day' => 'monday', 'time' => '10:00:00'],
    			['name' => 'Monday 12:00pm', 'day' => 'monday', 'time' => '12:00:00'],
    			['name' => 'Monday 2:00pm', 'day' => 'monday', 'time' => '14:00:00'],
    			['name' => 'Monday 7:00pm', 'day' => 'monday', 'time' => '19:00:00'],

                ['name' => 'Tuesday 10:00am', 'day' => 'tuesday', 'time' => '10:00:00'],
    			['name' => 'Tuesday 12:00pm', 'day' => 'tuesday', 'time' => '12:00:00'],
    			['name' => 'Tuesday 2:00pm', 'day' => 'tuesday', 'time' => '14:00:00'],
    			['name' => 'Tuesday 8:00pm', 'day' => 'tuesday', 'time' => '20:00:00'],
    			['name' => 'Tuesday 10:00pm', 'day' => 'tuesday', 'time' => '22:00:00'],

                ['name' => 'Wednesday 08:00am', 'day' => 'wednesday', 'time' => '08:00:00'],
                ['name' => 'Wednesday 10:00am', 'day' => 'wednesday', 'time' => '10:00:00'],
                ['name' => 'Wednesday 12:00pm', 'day' => 'wednesday', 'time' => '12:00:00'],
                ['name' => 'Wednesday 2:00pm', 'day' => 'wednesday', 'time' => '14:00:00'],
                ['name' => 'Wednesday 8:00pm', 'day' => 'wednesday', 'time' => '20:00:00'],
                ['name' => 'Wednesday 10:00pm', 'day' => 'wednesday', 'time' => '22:00:00'],

                ['name' => 'Thursday 10:00am', 'day' => 'thursday', 'time' => '10:00:00'],
                ['name' => 'Thursday 12:00pm', 'day' => 'thursday', 'time' => '12:00:00'],
                ['name' => 'Thursday 2:00pm', 'day' => 'thursday', 'time' => '14:00:00'],
                ['name' => 'Thursday 7:00pm', 'day' => 'thursday', 'time' => '19:00:00'],
                ['name' => 'Thursday 8:00pm', 'day' => 'thursday', 'time' => '20:00:00'],
                ['name' => 'Thursday 10:00pm', 'day' => 'thursday', 'time' => '22:00:00'],

                ['name' => 'Friday 08:00am', 'day' => 'friday', 'time' => '08:00:00'],
                ['name' => 'Friday 10:00am', 'day' => 'friday', 'time' => '10:00:00'],
                ['name' => 'Friday 12:00pm', 'day' => 'friday', 'time' => '12:00:00'],
                ['name' => 'Friday 2:00pm', 'day' => 'friday', 'time' => '14:00:00'],
                ['name' => 'Friday 10:00pm', 'day' => 'friday', 'time' => '22:00:00'],

                ['name' => 'Saturday 10:00am', 'day' => 'saturday', 'time' => '10:00:00'],
                ['name' => 'Saturday 12:00pm', 'day' => 'saturday', 'time' => '12:00:00'],
                ['name' => 'Saturday 2:00pm', 'day' => 'saturday', 'time' => '14:00:00'],
                ['name' => 'Saturday 10:00pm', 'day' => 'saturday', 'time' => '22:00:00'],

                ['name' => 'Sunday Worship 9:30am', 'day' => 'sunday', 'time' => '09:30:00', 'live' => true, 'embed_code' => '<div id="la1-video-player" data-embed-id="f87344c6-2e5a-49fa-8dc2-863a8ca08453"></div>'],
				['name' => 'Sunday Worship 11:11am', 'day' => 'sunday', 'time' => '11:11:00', 'live' => true, 'embed_code' => '<div id="la1-video-player" data-embed-id="f87344c6-2e5a-49fa-8dc2-863a8ca08453"></div>'],
            ];

    		foreach($broadcasts as $broadcast) {
    			Broadcast::create($broadcast);
    		}
    }
}
