<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\CustomerController;

Route::prefix('customer')
    ->name('customer.')
    ->controller(CustomerController::class)
    ->group(function () {

        // =========================
        // SAVED ADDRESS
        // =========================
        Route::get('/address', 'show')
            ->name('address.show');
    });
