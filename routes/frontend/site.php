<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;

Route::controller(SiteController::class)
    ->group(function () {

        Route::get('/', 'home')->name('home');
        Route::get('/about', 'about')->name('about');
        Route::get('/contact', 'contact')->name('contact');
        Route::get('/blog', 'blog')->name('blog');
        Route::get('/blog-details/{slug}', 'blogDetails')->name('blog.details');
        Route::get('/policy/{slug}', 'policy')->name('policy');
        Route::get('/faqs', 'faqs')->name('faqs');
    });
