<?php

use App\Http\Controllers\Frontend\ProductController;
use Illuminate\Support\Facades\Route;

// ================= PRODUCT =================

Route::prefix('product')
    ->name('product.')
    ->controller(ProductController::class)
    ->group(function () {

        Route::get('{slug}', 'show')->name('show');
    });
