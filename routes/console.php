<?php

use Illuminate\Foundation\Inspiring;
use App\Services\Broadcast\ConfigureAllBroadcasts;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('configure-broadcasts', function (ConfigureAllBroadcasts $configureAllBroadcasts) {
    $configureAllBroadcasts->execute();
    $this->comment('Boom shakalaka!');
})->describe('Configure all the broadcasts!');
