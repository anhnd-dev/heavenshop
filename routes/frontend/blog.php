<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\BlogController;

// ================= BLOG =================

Route::prefix('blog')
    ->name('blog.')
    ->controller(BlogController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');
        Route::get('{slug}', 'show')->name('show');
    });
