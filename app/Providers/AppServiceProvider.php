<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MapboxService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }


    

public function register()
{
    $this->app->singleton(MapboxService::class, function ($app) {
        return new MapboxService();
    });
}
}
