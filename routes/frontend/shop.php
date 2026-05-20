<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;

Route::controller(ShopController::class)

    ->group(function () {

        Route::get(
            '/shop',
            'index'
        )->name('shop');

        Route::get(
            '/shop/{slug}',
            'category'
        )
            ->where('slug', '.*')
            ->name('category.show');

        Route::get(
            '/product-detail/{slug}',
            'productDetails'
        )->name('product.details');

        Route::get(
            '/quick-to-view',
            'quickToView'
        )->name('quick.to.view');

        Route::post(
            '/add-to-cart',
            'addToCart'
        )->name('add.to.cart');
    });
