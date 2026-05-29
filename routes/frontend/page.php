<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PageController;

Route::controller(PageController::class)
    ->group(function () {

        Route::get('/about', 'about')->name('about');
        Route::get('/contact', 'contact')->name('contact');
        Route::get('/policy/{slug}', 'policy')->name('policy');
        Route::get('/faqs', 'faqs')->name('faqs');
    });
