<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;

Route::controller(LanguageController::class)
    ->group(function () {

        Route::get('/language/switch/{lang}', 'switchLang')->name('language.switch');
    });
