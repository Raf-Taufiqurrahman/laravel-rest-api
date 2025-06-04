<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;

Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

Route::controller(ProfileController::class)->middleware('auth:sanctum')->group(function(){
    Route::get('/profile', 'show');
    Route::put('/profile', 'update');
    Route::post('/logout', 'logout');
});
