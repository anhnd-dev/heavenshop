<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Entry Point)
|--------------------------------------------------------------------------
| File này chỉ dùng để load các route module
| Không viết logic lớn ở đây
|
*/

// ================= TEST / TEMP =================
// Route::view('/done', 'test');


// ================= LOAD MODULE ROUTES =================

// Frontend (khách)
require __DIR__ . '/frontend/index.php';

// Auth (login/register user)
require __DIR__ . '/auth.php';

// Admin
Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        require __DIR__ . '/admin/index.php';
    });
