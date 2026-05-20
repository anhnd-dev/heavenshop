<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OptimizationController;

Route::controller(OptimizationController::class)
    ->group(function () {

        Route::get('/clear-cache', 'clearCache');
        Route::get('/migrate', 'migrate');
        Route::get('/migrate-fresh', 'migrateFresh');
    });
