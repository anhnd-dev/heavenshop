<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\CartController;

Route::prefix('cart')
    ->name('cart.')
    ->controller(CartController::class)
    ->group(function () {

        Route::get('/', 'index')
            ->name('index');

        Route::get('/items', 'items')
            ->name('items');

        Route::post('/add', 'add')
            ->name('add');

        Route::post('/update', 'update')
            ->name('update');

        Route::post('/change-variant', 'changeVariant')
            ->name('changeVariant');

        Route::post('/select', 'select')
            ->name('select');

        Route::post('/select-all', 'selectAll')
            ->name('selectAll');

        Route::post('/apply-coupon', 'applyCoupon')
            ->name('applyCoupon');

        Route::delete('/remove-coupon', 'removeCoupon')
            ->name('removeCoupon');

        Route::delete('/remove', 'remove')
            ->name('remove');

        Route::delete('/clear', 'clear')
            ->name('clear');

        Route::get('/mini-cart', 'miniCart')
            ->name('miniCart');
    });
