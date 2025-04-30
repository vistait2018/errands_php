<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;

Route::prefix('v1')->group(function () {
    // Public Routes
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('auth/google/redirect', [GoogleController::class, 'redirectToGoogle']);
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');

    // Protected Routes (requires authentication)
    Route::middleware(['auth:sanctum', \App\Http\Middleware\CheckInternetConnection::class])->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::post('/email/resend', [AuthController::class, 'resend'])
        ->name('verification.resend');
        Route::get('users',[\App\Http\Controllers\UserController::class, 'index'])->name('users');
        Route::get('me',[\App\Http\Controllers\UserController::class, 'me'])->name('me');
        Route::apiResource('profiles', \App\Http\Controllers\ProfileController::class);
        Route::apiResource('banks', \App\Http\Controllers\BankController::class);
        Route::apiResource('roles', \App\Http\Controllers\RoleController::class);
        Route::post('verify-nin',[\App\Http\Controllers\BvnAndNinController::class,'verifyNIN']);
        Route::post('verify-bvn',[\App\Http\Controllers\BvnAndNinController::class,'verifyBvn']);
        Route::post('verify-bvn/save',[\App\Http\Controllers\BvnAndNinController::class,'validateAndCreateBVN']);
        Route::post('verify-nin/save',[\App\Http\Controllers\BvnAndNinController::class,'validateAndCreateNIN']);
    });
});
