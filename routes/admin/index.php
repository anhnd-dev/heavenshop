<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\OptimizationController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\Auth\ProfileController;



/*
|--------------------------------------------------------------------------
| ADMIN ROOT
|--------------------------------------------------------------------------
*/

// AUTH
Route::controller(LoginController::class)->group(function () {

    Route::group(['middleware' => ['CheckAdminSession']], function () {
        Route::get('/', 'getLogin')->name('getLogin');
        Route::post('/', 'postLogin')->name('postLogin');
    });

    Route::get('logout', 'logout')->name('logout');
});


// FORGOT PASSWORD
// Route::controller(ForgotPasswordController::class)->group(function () {

//     Route::get('/forgot-password', 'showForgotForm')
//         ->name('password.request');

//     Route::post('/forgot-password', 'sendResetLink')
//         ->name('password.email');
// });

// // PROFILE
// Route::controller(ProfileController::class)->group(function () {

//     Route::get('profile/{id}', 'profile')->name('profile');
//     Route::post('update-profile/{id}', 'update_profile')->name('update_profile');
//     Route::post('change-password/{id}', 'change_password')->name('change_password');
//     Route::post('recovery-password', 'recovery_password')->name('recovery_password');
//     Route::get('reset-password', 'resetPassword')->name('reset_password');
//     Route::post('update-password', 'updatePassword')->name('update_password');
// });

// Route::controller(RegisterController::class)->group(function () {

//     Route::get('register', 'getRegister')->name('getRegister');

//     Route::match(['get', 'post'], 'register-auth', 'postRegister')->name('postRegister');
// });

// ===== PROTECTED =====
Route::middleware(
    [
        'CheckAdminLogin',
        'AdminLanguageSession'
    ]
)->group(function () {

    require __DIR__ . '/optimize.php';
    require __DIR__ . '/dashboard.php';
    require __DIR__ . '/language.php';
    require __DIR__ . '/category.php';
    require __DIR__ . '/subcategory.php';
    require __DIR__ . '/brand.php';
    require __DIR__ . '/product.php';

    require __DIR__ . '/color.php';
    require __DIR__ . '/size.php';

    // require __DIR__ . '/order.php';
    require __DIR__ . '/coupon.php';
    // require __DIR__ . '/user.php';
    // require __DIR__ . '/role.php';
    // require __DIR__ . '/permission.php';
    require __DIR__ . '/blog.php';
    require __DIR__ . '/subscriber.php';

    require __DIR__ . '/setting.php';

    require __DIR__ . '/frontend.php';

    // require __DIR__ . '/slider.php';
    // require __DIR__ . '/delivery.php';
    // require __DIR__ . '/comment.php';
    // require __DIR__ . '/gallery.php';
    // require __DIR__ . '/review.php';
    // require __DIR__ . '/contact.php';
    // require __DIR__ . '/customer.php';
    // require __DIR__ . '/supplier.php';
    // require __DIR__ . '/statistic.php';
});
