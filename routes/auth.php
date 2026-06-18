<?php

use Illuminate\Support\Facades\Route;
use Meraki\Packages\Auth\Http\Controllers\Auth\EmailVerificationNoticeController;
use Meraki\Packages\Auth\Http\Controllers\Auth\ForgotPasswordController;
use Meraki\Packages\Auth\Http\Controllers\Auth\LoginController;
use Meraki\Packages\Auth\Http\Controllers\Auth\RegisterController;
use Meraki\Packages\Auth\Http\Controllers\Auth\ResendEmailVerificationController;
use Meraki\Packages\Auth\Http\Controllers\Auth\ResetPasswordController;
use Meraki\Packages\Auth\Http\Controllers\Auth\VerifyEmailController;

if (!config('meraki-auth.platforms.web.enabled', true)) {
    return;
}

$webPrefix     = config('meraki-auth.platforms.web.routes.prefix', '');
$webMiddleware = config('meraki-auth.platforms.web.routes.middleware', ['web']);

Route::middleware($webMiddleware)
    ->prefix($webPrefix)
    ->group(function () {
        Route::middleware('guest')->group(function () {
            Route::get('/login', [LoginController::class, 'create'])->name('login');
            Route::post('/login', [LoginController::class, 'store']);

            if (config('meraki-auth.features.registration', true)) {
                Route::get('/register', [RegisterController::class, 'create'])->name('register');
                Route::post('/register', [RegisterController::class, 'store']);
            }

            if (config('meraki-auth.features.password_reset', true)) {
                Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
                Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');

                Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
                Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
            }
        });

        Route::middleware('auth')->group(function () {
            Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

            if (config('meraki-auth.features.email_verification', true)) {
                Route::get('/verify-email', EmailVerificationNoticeController::class)->name('verification.notice');

                Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
                    ->middleware(['signed', 'throttle:6,1'])
                    ->name('verification.verify');

                Route::post('/email/verification-notification', ResendEmailVerificationController::class)
                    ->middleware('throttle:6,1')
                    ->name('verification.send');
            }
        });
    });
