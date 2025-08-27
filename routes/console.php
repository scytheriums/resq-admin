<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule untuk destroy expired orders setiap 5 menit
Schedule::command('app:destroy-expired-order')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/expired-orders.log'))
    ->onSuccess(function () {
        \Log::info('Expired orders command completed successfully at ' . now());
    })
    ->onFailure(function () {
        \Log::error('Expired orders command failed at ' . now());
    });