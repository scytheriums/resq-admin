<?php

use Illuminate\Support\Facades\Route;

// Authentication Routes
require __DIR__.'/auth.php';

// Admin Routes
require __DIR__.'/admin.php';

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');