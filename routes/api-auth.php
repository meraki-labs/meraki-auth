<?php

use Illuminate\Support\Facades\Route;
use Meraki\Packages\Auth\Http\Controllers\Api\Auth\ApiForgotPasswordController;
use Meraki\Packages\Auth\Http\Controllers\Api\Auth\ApiLoginController;
use Meraki\Packages\Auth\Http\Controllers\Api\Auth\ApiRegisterController;
use Meraki\Packages\Auth\Http\Controllers\Api\Auth\ApiResetPasswordController;

if (!config('meraki-auth.platforms.api.enabled', false)) {
    return;
}

$apiPrefix     = config('meraki-auth.platforms.api.routes.prefix', 'api/auth');
$apiMiddleware = config('meraki-auth.platforms.api.routes.middleware', ['api']);

Route::middleware($apiMiddleware)
    ->prefix($apiPrefix)
    ->group(function () {
        Route::post('/login', [ApiLoginController::class, 'store']);

        if (config('meraki-auth.features.registration', true)) {
            Route::post('/register', [ApiRegisterController::class, 'store']);
        }

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [ApiLoginController::class, 'destroy']);
        });

        if (config('meraki-auth.features.password_reset', true)) {
            Route::post('/forgot-password', [ApiForgotPasswordController::class, 'store'])
                ->name('api.password.email');

            Route::post('/reset-password', [ApiResetPasswordController::class, 'store'])
                ->name('api.password.update');
        }
    });
