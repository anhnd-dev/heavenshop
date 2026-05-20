<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;

Route::get('/check-auth', function () {
    return response()->json(['isLoggedIn' => Auth::guard('customer')->check()]);
})->name('check.auth');

Route::prefix('buyer')
    ->name('buyer.')
    ->controller(LoginController::class)
    ->group(function () {

        Route::get('/auth', 'getAuth')
            ->name('auth');

        Route::post('/auth-login', 'postAuthLogin')
            ->name('auth.login');

        Route::post('/auth-register', 'postAuthRegister')
            ->name('auth.register');
    });
