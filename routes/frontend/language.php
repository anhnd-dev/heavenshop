<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\LanguageController;

Route::controller(LanguageController::class)
    ->group(function () {

        Route::get('/language/switch/{lang}', 'switchLang')->name('language.switch');
    });
