<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::controller(OrderController::class)
    ->group(function () {

        Route::get('/complete', 'complete')->name('complete');
    });
