<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SubscriberController;

Route::prefix('subscriber')
    ->controller(SubscriberController::class)
    ->group(function () {

        Route::get('/', 'index')->name('subscriber.index');
    });
