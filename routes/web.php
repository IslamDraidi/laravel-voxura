<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;  
use App\Http\Controllers\Auth\Logout; 
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\AdminController;


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


    // Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::get('/products/create', [AdminController::class, 'create']);
    Route::post('/products', [AdminController::class, 'store']);
    Route::get('/products/{product}/edit', [AdminController::class, 'edit']);
    Route::put('/products/{product}', [AdminController::class, 'update']);
    Route::delete('/products/{product}', [AdminController::class, 'destroy']);
    Route::get('/archive', [AdminController::class, 'archive']);
Route::post('/products/{id}/restore', [AdminController::class, 'restore']);
});


