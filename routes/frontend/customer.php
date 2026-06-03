<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\AccountController;

// ================= ACCOUNT MANAGE =================

Route::middleware('customer.auth')
    ->prefix('account')
    ->name('account.')
    ->controller(AccountController::class)
    ->group(function () {

        // Profile
        Route::get('/', 'profile')
            ->name('profile');

        Route::post('/avatar/update', 'updateAvatar')
            ->name('avatar.update');

        Route::post('/profile/update', 'updateProfile')
            ->name('profile.update');

        Route::post('/password/update', 'updatePassword')
            ->name('password.update');

        // Orders
        Route::get('/orders', 'orders')
            ->name('orders');

        Route::get('/orders/{order}/detail', 'orderDetail')
            ->name('orders.detail');

        Route::post(
            'orders/{order}/cancel',
            'orderCancel'
        )->name('orders.cancel');

        // Addresses
        Route::get('/addresses', 'addresses')
            ->name('addresses');

        // Vouchers
        Route::get('/vouchers', 'vouchers')
            ->name('vouchers');

        // Address
        Route::get('/address', 'show')
            ->name('address.show');

        Route::post('/address/store', 'storeAddress')
            ->name('address.store');

        Route::post('/address/update', 'updateAddress')
            ->name('address.update');

        Route::post('/address/set-default', 'setDefault')
            ->name('address.setDefault');

        // Logout
        Route::post('/logout', 'logout')
            ->name('logout');
    });
