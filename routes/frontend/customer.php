<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\CustomerController;

// ================= CUSTOMER MANAGE =================

Route::prefix('customer')
    ->name('customer.')
    ->controller(CustomerController::class)
    ->group(function () {

        // =========================
        // DASHBOARD
        // =========================
        Route::get('/dashboard', 'index')
            ->name('dashboard');

        // =========================
        // SAVED ADDRESS
        // =========================
        Route::get('/address', 'show')
            ->name('address.show');
    });
