<?php

use Illuminate\Support\Facades\Route;

Route::prefix('order')->group(function () {

    Route::get('/success/{order_code}', function ($order_code) {
        return view('frontend.order.success', compact('order_code'));
    })->name('order.success');

    Route::get('/failed', function () {
        return view('frontend.order.failed');
    })->name('order.failed');
});
