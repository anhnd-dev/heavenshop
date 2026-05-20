<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

Route::prefix('cart')
    ->name('cart.')
    ->controller(CartController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');
        Route::get('/load-view', 'loadView')->name('load.view');
        Route::delete('/remove-item', 'removeItem')->name('remove.item');
        Route::delete('/delete-selected-item', 'deleteSelectedItem')->name('delete.selected.item');
        Route::delete('/remove-all-item', 'removeAllItem')->name('remove.all.item');
        Route::post('/update-quantity', 'updateQuantity')->name('update.quantity');
        Route::post('/apply-coupon', 'applyCoupon')->name('apply.coupon');
        Route::post('/shipping-fee', 'getShippingFee')->name('shipping.fee');
        Route::post('/save-coupon-session', 'saveCouponSession')->name('save.coupon.session');
        Route::get('/forget-coupon-session', 'forgetCouponSession')->name('forget.coupon.session');
        Route::post('/save-shipping-method-session', 'saveShippingMethodSession')->name('save.shipping.method.session');
    });
