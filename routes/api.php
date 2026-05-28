<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocationController;

/*
|--------------------------------------------------------------------------
| Public API
|--------------------------------------------------------------------------
*/

Route::prefix('location')
    ->controller(LocationController::class)
    ->group(function () {
        Route::get('provinces', 'provinces');
        Route::get('districts', 'districts');
        Route::get('wards', 'wards');
    });


/*
|--------------------------------------------------------------------------
| Private API (Admin)
|--------------------------------------------------------------------------
*/
