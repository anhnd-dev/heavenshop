<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;

Route::controller(HomeController::class)
    ->group(function () {

        Route::get('/', 'index')->name('home');
    });
