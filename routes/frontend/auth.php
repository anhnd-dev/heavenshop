<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Auth\LoginController;

Route::prefix('auth')
    ->name('auth.')
    ->controller(LoginController::class)
    ->group(function () {

        // =========================
        // AJAX LOGIN
        // =========================
        Route::post('/ajax-login', 'ajaxLogin')
            ->name('ajax.login');

        // =========================
        // AJAX REGISTER
        // =========================
        Route::post('/ajax-register', 'ajaxRegister')
            ->name('ajax.register');
    });
