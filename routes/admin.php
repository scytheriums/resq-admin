<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\AmbulanceTypeController;
use App\Http\Controllers\Admin\PurposeController;
use App\Http\Controllers\Admin\AdditionalServiceController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DestinationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
// Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Orders Management
    Route::resource('drivers', DriverController::class);

    // Orders Management
    Route::resource('orders', OrderController::class);
    
    // Drivers Management
    Route::resource('drivers', DriverController::class);
    
    // Ambulance Types Management
    Route::resource('ambulance-types', AmbulanceTypeController::class);
    
    // Purposes Management
    Route::resource('purposes', PurposeController::class);
    
    // Additional Services Management
    Route::resource('additional-services', AdditionalServiceController::class);
    
    // Activity Logs
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Destinations Management
    Route::resource('destinations', DestinationController::class);
});
