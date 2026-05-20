<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard() // :GET
    {
        return view('admin.pages.dashboard.index');
    }

    public function systemInfo()
    {
        $laravelVersion = app()->version();
        $serverDetails = $_SERVER;
        $currentPHP = phpversion();
        $timeZone = config('app.timezone');
        return view('admin.pages.info.index', compact('currentPHP', 'laravelVersion', 'serverDetails', 'timeZone'));
    }

    public function logout() // :GET
    {
        Auth::guard('admin')->logout();
        toastr()->success('Đăng xuất khỏi trang quản trị thành công');
        return redirect()->route('admin.getLogin');
    }
}
