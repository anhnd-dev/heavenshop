<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrderController;

Route::prefix('order')
    ->name('order.')
    ->controller(OrderController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');

        Route::get('/details/{id}', 'show')->name('show');

        Route::get('/print/{id}', 'print')->name('print');

        Route::post('/edit', 'edit')->name('edit');

        Route::post('/updateStatus', 'updateStatus')->name('updateStatus');

        Route::post('/updatePaymentStatus', 'updatePaymentStatus')->name('updatePaymentStatus');

        // Route::get('/edit', 'edit')->name('edit');
        // Route::put('/update', 'update')->name('update');

        Route::delete('/delete', 'delete')->name('delete');
        // Route::delete('/delete-all', 'deleteAll')->name('delete.all');

        Route::post('/restore', 'restore')->name('restore');
        // Route::post('/restore-all', 'restoreAll')->name('restore.all');

        Route::delete('/force-delete', 'forceDelete')->name('force.delete');
        // Route::delete('/force-delete-all', 'forceDeleteAll')->name('force.delete.all');

        // Route::post('/change-status', 'changeStatus')->name('change.status');
    });
