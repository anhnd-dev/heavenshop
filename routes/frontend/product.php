<?php

use App\Http\Controllers\Frontend\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Catalog Routes
|--------------------------------------------------------------------------
*/

// ================= COLLECTIONS =================

Route::prefix('product')
    ->controller(ProductController::class)
    ->group(function () {

        Route::get('{slug}', 'show')->name('product.show');
    });
