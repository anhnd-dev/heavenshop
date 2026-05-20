<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::prefix('payment')
    ->name('payment.')
    ->controller(PaymentController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');
    });
