<?php

use Faker\Generator as Faker;

$factory->define(App\Sermon::class, function (Faker $faker) {
    return [
	        'title' => $faker->title,
	        'description' => $faker->paragraph,
	        'notes' => $faker->paragraph,
	        'speaker_id' => 1,
	        'series_id' => 1,
	        'youtube_id' => $faker->uuid,
	        'duration' => '00:45:12'
    ];
});
