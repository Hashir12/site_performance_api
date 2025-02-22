<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LightHouseController;
use App\Http\Controllers\Auth\GoogleController;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/track-performance', [LightHouseController::class, 'trackPerformance']);
    Route::post('/logout', [AuthController::class, 'logout']);

});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/google-auth', [GoogleController::class, 'redirectToGoogle']);
Route::get('/google-callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('get/auth/token', [GoogleController::class, 'getAuthToken']);
