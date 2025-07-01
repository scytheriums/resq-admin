<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Authentication Routes
Route::middleware(['guest:admin'])->name('admin.')->group(function () {
    Route::get('admin/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('admin/login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('admin/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('admin/register', [RegisterController::class, 'register'])->name('register.submit');
    Route::get('admin/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('admin/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('admin/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('admin/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Logout Route (requires authentication)
Route::get('admin/logout', [LoginController::class, 'logout'])->middleware('auth:admin')->name('admin.logout');
Route::post('admin/logout', [LoginController::class, 'logout'])->middleware('auth:admin')->name('admin.logout');
