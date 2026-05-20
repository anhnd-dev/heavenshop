<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\GeneralSettingController;

Route::prefix('setting')
    ->name('setting.')
    ->controller(GeneralSettingController::class)
    ->group(function () {

        Route::get('/general', 'general')->name('general');
        Route::post('/general', 'generalSubmit')->name('general.submit');
        Route::get('/optimize', 'optimize')->name('optimize');
        Route::get('/cookie', 'cookie')->name('cookie');
        Route::post('/cookie', 'cookieSubmit')->name('cookie.submit');
        Route::get('/logo-icon', 'logoIcon')->name('logo.icon');
        Route::post('/logo-icon', 'logoIconSubmit')->name('logo.icon.submit');
    });
