<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;  
use App\Http\Controllers\Auth\Logout; 


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