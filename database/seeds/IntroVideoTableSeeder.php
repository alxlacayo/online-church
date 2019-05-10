<?php

use Illuminate\Database\Seeder;
use App\IntroVideo;

class IntroVideoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IntroVideo::create([
        	'video_id' => 'https://vimeo.com/218845426/1d582e7485',
        	'enabled' => 1
        ]);
    }
}
