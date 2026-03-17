<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;  
use App\Http\Controllers\Auth\Logout; 
use App\Http\Controllers\Auth\Login;


Route::get('/', [UserController::class, 'index']);

Route::view('/register', 'auth.register')
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisterController::class, 'store'])
    ->middleware('guest');

//Logout route
Route::post('/logout', Logout::class)
    ->middleware('auth')
    ->name('logout');

//Login route
Route::view('/login', 'auth.login')
    ->middleware('guest')
    ->name('login');


Route::post('/login', [Login::class, 'store'])
    ->middleware('guest');