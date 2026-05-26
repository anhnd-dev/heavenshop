<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Entry point chỉ dùng để load route modules.
| Không đặt business logic tại đây.
|
*/

// ================= FRONTEND =================

require __DIR__ . '/frontend/web.php';

// ================= AUTH =================

require __DIR__ . '/auth.php';

// ================= ADMIN =================

Route::prefix('admin')
    ->as('admin.')
    // ->middleware(['auth', 'admin'])
    ->group(function () {

        require __DIR__ . '/admin/web.php';
    });
