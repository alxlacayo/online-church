<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Event;
use Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::listen(function ($query) {
            Log::debug([
                $query->sql,
                $query->bindings,
                $query->time
            ]);
            //Log::debug($query->sql);
            //Log::debug('-');    
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
