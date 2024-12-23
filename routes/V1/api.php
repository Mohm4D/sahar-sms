<?php


use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\V1\UserController;
use Illuminate\Support\Facades\Route;


Route::namespace('V1')->prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('verify', [AuthController::class, 'verify']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
        Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword']);
    });

    Route::middleware(['auth:api', 'check.status'])->group(function () {
        Route::prefix('profile')->group(function () {
            Route::get('/', [UserController::class, 'profile']);
            Route::post('/', [UserController::class, 'update']);
        });
    });
});
