<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| FRONTEND ROOT
|--------------------------------------------------------------------------
*/

/* ================= UTIL ================= */

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return redirect()->route('home_page');
});

Route::get('/clear-route', function () {
    Artisan::call('route:clear');
    return redirect()->route('home_page');
});

Route::get('/clear-config', function () {
    Artisan::call('config:clear');
    return redirect()->route('home_page');
});

Route::get('/clear-view', function () {
    Artisan::call('view:clear');
    return redirect()->route('home_page');
});

/* ================= LANGUAGE ================= */

Route::get('lang/{locale}', function ($locale) {
    abort_unless(in_array($locale, ['vi', 'en']), 404);

    Session::put('locale', $locale);
    return back();
});

// ===== PROTECTED =====
Route::middleware(
    [
        'UserLanguageSession',
    ]
)->group(function () {

    require __DIR__ . '/language.php';

    require __DIR__ . '/site.php';

    require __DIR__ . '/product.php';

    require __DIR__ . '/shop.php';

    require __DIR__ . '/basic.php';

    require __DIR__ . '/order.php';

    require __DIR__ . '/cart.php';

    require __DIR__ . '/checkout.php';

    require __DIR__ . '/payment.php';

    require __DIR__ . '/catalog.php';
});
