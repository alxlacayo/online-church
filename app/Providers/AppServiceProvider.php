<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;
use DB;
use Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // if (App::environment('local')) {
        //     DB::listen(function ($query) {
        //         Log::debug([
        //             $query->sql,
        //             $query->bindings,
        //             $query->time
        //         ]);   
        //     });  
        // }
    }
}
