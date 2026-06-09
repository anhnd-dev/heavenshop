<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\Dashboard\CustomerService;
use App\Services\Admin\Dashboard\OrderService;
use App\Services\Admin\Dashboard\OverviewService;
use App\Services\Admin\Dashboard\ProductService;
use App\Services\Admin\Dashboard\RevenueService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function overview(
        OverviewService $service
    ) {
        return view(
            'admin.pages.dashboard.overview',
            $service->getData()
        );
    }

    public function revenue(
        RevenueService $service
    ) {
        return view(
            'admin.pages.dashboard.revenue',
            $service->getData()
        );
    }

    public function orders(
        OrderService $service
    ) {
        return view(
            'admin.pages.dashboard.orders',
            $service->getData()
        );
    }

    public function products(
        ProductService $service
    ) {
        return view(
            'admin.pages.dashboard.products',
            $service->getData()
        );
    }

    public function customers(
        CustomerService $service
    ) {
        return view(
            'admin.pages.dashboard.customers',
            $service->getData()
        );
    }

    public function systemInfo()
    {
        return view(
            'admin.pages.info.index',
            [
                'currentPHP' => phpversion(),
                'laravelVersion' => app()->version(),
                'serverDetails' => $_SERVER,
                'timeZone' => config('app.timezone'),
            ]
        );
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        toastr()->success(
            'Đăng xuất khỏi trang quản trị thành công'
        );

        return redirect()->route(
            'admin.getLogin'
        );
    }
}
