<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\VnpayController;

Route::prefix('vnpay')
    ->name('vnpay.')
    ->controller(VnpayController::class)
    ->group(function () {

        // URL VNPay redirect người dùng về
        Route::get('/return', [VnpayController::class, 'return'])
            ->name('return');

        // URL VNPay gọi server-to-server
        Route::get('/ipn', [VnpayController::class, 'ipn'])
            ->name('ipn');
    });
