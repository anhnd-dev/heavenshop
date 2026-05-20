<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BasicController;

Route::controller(BasicController::class)
    ->group(function () {

        Route::get('/load-cart-count', 'loadCartCount')->name('load.cart.count');
        Route::get('/load-cart-dropdown', 'loadCartDropdown')->name('load.cart.dropdown');
        Route::post('/shipping-address-store', 'shippingAddressStore')->name('shipping.address.store');
        Route::get('/shipping-address-edit', 'shippingAddressEdit')->name('shipping.address.edit');
        Route::put('/shipping-address-update', 'shippingAddressUpdate')->name('shipping.address.update');
    });
