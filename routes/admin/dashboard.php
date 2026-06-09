<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::prefix('dashboard')
    ->name('dashboard.')
    ->controller(DashboardController::class)
    ->group(function () {

        Route::get('/overview', 'overview')
            ->name('overview');

        Route::get('/revenue', 'revenue')
            ->name('revenue');

        Route::get('/orders', 'orders')
            ->name('orders');

        Route::get('/products', 'products')
            ->name('products');

        Route::get('/customers', 'customers')
            ->name('customers');
    });
