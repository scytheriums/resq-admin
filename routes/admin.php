<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\AmbulanceTypeController;
use App\Http\Controllers\Admin\PurposeController;
use App\Http\Controllers\Admin\AdditionalServiceController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('notif-test', [DashboardController::class, 'send_notif'])->name('send_notif');

    // Orders Management
    Route::resource('orders', OrderController::class);
    Route::get('orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::delete('orders/{order}/review/delete', [OrderController::class, 'deleteReview'])->name('orders.review.delete');
    
    

    // Location data endpoints
    Route::get('drivers/get-provinces', [DriverController::class, 'getProvinces'])->name('drivers.get-provinces');
    Route::get('drivers/get-cities', [DriverController::class, 'getCities'])->name('drivers.get-cities');
    Route::get('drivers/get-districts', [DriverController::class, 'getDistricts'])->name('drivers.get-districts');
    Route::get('drivers/get-villages', [DriverController::class, 'getVillages'])->name('drivers.get-villages');

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

    // Settings Management
    Route::resource('settings', SettingController::class);

    // Users Management
    Route::resource('users', UserController::class);

    // Roles & Permissions
    Route::resource('roles', RoleController::class)->names('roles');
    Route::resource('permissions', PermissionController::class)->names('permissions');
});
