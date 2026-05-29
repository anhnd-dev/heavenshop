<?php

use App\Http\Controllers\Frontend\CollectionController;
use Illuminate\Support\Facades\Route;

// ================= COLLECTIONS =================

Route::prefix('collections')
    ->controller(CollectionController::class)
    ->group(function () {

        Route::get('{path}', 'show')
            ->where('path', '.*')
            ->name('collection.show');
    });
