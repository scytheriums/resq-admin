<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
// Authentication Routes
require __DIR__.'/auth.php';

// Auth::routes();
require __DIR__.'/admin.php';

// Public Routes
Route::get('/', function () {
    return view('enduser.portal');
});

Route::get('/driver-registration', function () {
    return view('enduser.driver_registration');
});

Route::post('/driver-registration', [RegistrationController::class, 'driverRegistration'])->name('driver-registration');
Route::get('/registration-complete', [RegistrationController::class, 'registrationComplete'])->name('registration-complete');

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');