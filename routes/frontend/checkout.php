<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;

Route::prefix('checkout')
    ->name('checkout.')
    ->controller(CheckoutController::class)
    ->group(function () {

        Route::get('/', 'CheckoutController@index')->name('index');
        Route::get('/load-view', 'CheckoutController@loadView')->name('load.view');
        Route::get('/load-province', 'CheckoutController@loadProvince')->name('load.province');
        Route::get('/load-district', 'CheckoutController@loadDistrict')->name('load.district');
        Route::get('/load-ward', 'CheckoutController@loadWard')->name('load.ward');
    });
