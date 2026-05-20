<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SubcategoryController;

Route::prefix('subcategory')
    ->name('subcategory.')
    ->controller(SubcategoryController::class)
    ->group(function () {

        Route::get('/list', 'list')->name('list');
        Route::get('/sub-edit', 'subEdit')->name('sub.edit');
        Route::put('/update', 'update')->name('update');
        Route::delete('/delete', 'delete')->name('delete');
        Route::delete('/delete-all', 'deleteAll')->name('delete.all');
        Route::post('/restore', 'restore')->name('restore');
        Route::post('/restore-all', 'restoreAll')->name('restore.all');
        Route::delete('/force-delete', 'forceDelete')->name('force.delete');
        Route::delete('/force-delete-all', 'forceDeleteAll')->name('force.delete.all');
    });
