<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductGalleryController;

Route::prefix('product')
    ->name('product.')
    ->group(function () {

        Route::controller(ProductController::class)
            ->group(function () {

                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit', 'edit')->name('edit');
                Route::put('/update', 'update')->name('update');

                Route::delete('/delete', 'delete')->name('delete');
                Route::delete('/delete-all', 'deleteAll')->name('delete.all');

                Route::post('/restore', 'restore')->name('restore');
                Route::post('/restore-all', 'restoreAll')->name('restore.all');

                Route::delete('/force-delete', 'forceDelete')->name('force.delete');
                Route::delete('/force-delete-all', 'forceDeleteAll')->name('force.delete.all');

                Route::post('/change-status', 'changeStatus')->name('change.status');

                Route::get('/load-subcategory', 'loadSubcategory')->name('load.subcategory');
                Route::get('/load-categories', 'loadCategories')->name('load.categories');
            });

        Route::prefix('{product}/gallery')
            ->name('gallery.')
            ->controller(ProductGalleryController::class)
            ->group(function () {

                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');

                Route::get('/edit', 'edit')->name('edit');

                Route::put('/update/{id}', 'update')->name('update');

                Route::delete('/delete', 'delete')->name('delete');
                Route::delete('/delete-all', 'deleteAll')->name('delete.all');

                Route::post('/restore', 'restore')->name('restore');
                Route::post('/restore-all', 'restoreAll')->name('restore.all');

                Route::delete('/force-delete', 'forceDelete')->name('force.delete');
                Route::delete('/force-delete-all', 'forceDeleteAll')->name('force.delete.all');
            });
    });
