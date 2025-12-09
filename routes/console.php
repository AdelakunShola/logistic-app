<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\UpdateDriverPerformance;
use Illuminate\Support\Facades\Log;
use App\Console\Commands\EscalateCriticalDelays;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Schedule the driver performance update
Schedule::command('drivers:update-performance')
    ->dailyAt('00:30')
    ->timezone('Africa/Lagos') // Your timezone
    ->onSuccess(function () {
        // Optional: Log success
        Log::info('Driver performance metrics updated successfully');
    })
    ->onFailure(function () {
        // Optional: Log failure
        Log::error('Failed to update driver performance metrics');
    });

// Alternative: You can also schedule using closure
Schedule::call(function () {
    // Call the update method directly
    $controller = app(\App\Http\Controllers\DriverController::class);
    $controller->updateDriverPerformanceMetrics();
})->dailyAt('00:30');

// For hourly updates (real-time metrics):
Schedule::command('drivers:update-performance')
    ->hourly()
    ->timezone('Africa/Lagos');


    // Auto-escalate critical delays every hour (delays >= 48 hours)
Schedule::command('delays:escalate --hours=48')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();

// Optional: Run more frequently for very critical delays (72+ hours)
Schedule::command('delays:escalate --hours=72')
    ->everyThirtyMinutes()
    ->withoutOverlapping()
    ->runInBackground();

    

// For testing (runs every minute):
Schedule::command('drivers:update-performance')
    ->everyMinute();



    

