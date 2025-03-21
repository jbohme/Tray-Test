<?php

use Illuminate\Support\Facades\Route;

Route::get('auth/google', [\App\Http\Controllers\GoogleOAuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [\App\Http\Controllers\GoogleOAuthController::class, 'handleGoogleCallback']);

Route::middleware('auth.jwt')->prefix('users')->group(function () {
    Route::get('/', [\App\Http\Controllers\UserController::class, 'listUsers']);
    Route::put('update', [\App\Http\Controllers\UserController::class, 'update']);
});
