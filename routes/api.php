<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LightHouseController;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/track-performance', [LightHouseController::class, 'trackPerformance']);
    Route::post('/logout', [AuthController::class, 'logout']);

});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/google-auth', [AuthController::class, 'redirectToGoogle']);
Route::get('/google-callback', [AuthController::class, 'handleGoogleCallback']);
