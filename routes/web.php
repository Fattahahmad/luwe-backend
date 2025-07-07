<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/api-tester', function () {
    return view('api-tester');
})->name('api-tester');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Users routes
Route::get('/users', [UserController::class, 'index'])->name('users.index');
