<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\CheckoutController;

Route::prefix('checkout')
    ->name('checkout.')
    ->controller(CheckoutController::class)
    ->group(function () {

        Route::post('/place', 'placeOrder')->name('place');
    });
