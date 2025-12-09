<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\LogActivity;
use App\Http\Middleware\CheckDriverAvailability;
use App\Http\Middleware\CheckRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => CheckRole::class,
        'driver.available' => CheckDriverAvailability::class,
        'log.activity' => LogActivity::class,
       
    ]);

    $middleware->web(LogActivity::class);
})


    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
