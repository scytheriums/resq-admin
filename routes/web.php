<?php


use Illuminate\Support\Facades\Route;

// Authentication Routes
require __DIR__.'/auth.php';

// Auth::routes();
require __DIR__.'/admin.php';

// Public Routes
Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');