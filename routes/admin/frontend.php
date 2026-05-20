<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FrontendController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SocialController;

Route::name('frontend.')
    ->prefix('frontend')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Frontend Settings
        |--------------------------------------------------------------------------
        */

        Route::controller(FrontendController::class)->group(function () {

            Route::get('/seo', 'seo')->name('seo');
            Route::post('/seo', 'seoSubmit')->name('seo.submit');

            Route::get('/contact', 'contact')->name('contact');
            Route::post('/contact', 'contactSubmit')
                ->name('contact.submit');
        });

        /*
        |--------------------------------------------------------------------------
        | Policy
        |--------------------------------------------------------------------------
        */

        Route::prefix('policy')
            ->name('policy.')
            ->controller(PolicyController::class)
            ->group(function () {

                Route::get('/', 'index')->name('index');

                Route::post('/store', 'store')->name('store');

                Route::get('/edit', 'edit')->name('edit');

                Route::put('/update', 'update')->name('update');

                Route::delete('/delete', 'delete')->name('delete');

                Route::delete('/delete-all', 'deleteAll')
                    ->name('delete.all');

                Route::post('/restore', 'restore')
                    ->name('restore');

                Route::post('/restore-all', 'restoreAll')
                    ->name('restore.all');

                Route::delete('/force-delete', 'forceDelete')
                    ->name('force.delete');

                Route::delete('/force-delete-all', 'forceDeleteAll')
                    ->name('force.delete.all');

                Route::post('/change-status', 'changeStatus')
                    ->name('change.status');
            });

        /*
        |--------------------------------------------------------------------------
        | Slider
        |--------------------------------------------------------------------------
        */

        Route::prefix('slider')
            ->name('slider.')
            ->controller(SliderController::class)
            ->group(function () {

                Route::get('/', 'index')->name('index');

                Route::post('/store', 'store')->name('store');

                Route::get('/edit', 'edit')->name('edit');

                Route::put('/update', 'update')->name('update');

                Route::delete('/delete', 'delete')->name('delete');

                Route::delete('/delete-all', 'deleteAll')
                    ->name('delete.all');

                Route::post('/restore', 'restore')
                    ->name('restore');

                Route::post('/restore-all', 'restoreAll')
                    ->name('restore.all');

                Route::delete('/force-delete', 'forceDelete')
                    ->name('force.delete');

                Route::delete('/force-delete-all', 'forceDeleteAll')
                    ->name('force.delete.all');

                Route::post('/change-status', 'changeStatus')
                    ->name('change.status');
            });

        /*
        |--------------------------------------------------------------------------
        | Social Icon
        |--------------------------------------------------------------------------
        */

        Route::prefix('social-icon')
            ->name('social_icon.')
            ->controller(SocialController::class)
            ->group(function () {

                Route::get('/', 'index')->name('index');

                Route::post('/store', 'store')->name('store');

                Route::get('/edit', 'edit')->name('edit');

                Route::put('/update', 'update')->name('update');

                Route::delete('/delete', 'delete')->name('delete');

                Route::delete('/delete-all', 'deleteAll')
                    ->name('delete.all');

                Route::post('/restore', 'restore')
                    ->name('restore');

                Route::post('/restore-all', 'restoreAll')
                    ->name('restore.all');

                Route::delete('/force-delete', 'forceDelete')
                    ->name('force.delete');

                Route::delete('/force-delete-all', 'forceDeleteAll')
                    ->name('force.delete.all');

                Route::post('/change-status', 'changeStatus')
                    ->name('change.status');
            });
    });
