<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::controller(DashboardController::class)
    ->group(function () {

        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('system-info', 'systemInfo')->name('system.info');
    });
