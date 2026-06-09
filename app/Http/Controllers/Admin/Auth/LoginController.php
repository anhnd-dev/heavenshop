<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\AuthRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use App\Models\Admin;
use App\Models\Frontend;


class LoginController extends Controller
{
    /**
     * GET: Hiển thị form đăng nhập
     */
    public function getLogin(): View
    {
        $logoIcon = Frontend::query()
            ->where('data_key', 'logo_icon.data')
            ->where('is_active', true)
            ->value('data_value') ?? [];

        return view('admin.auth.login', [
            'logoIcon' => $logoIcon
        ]);
    }

    /**
     * POST: Xử lý đăng nhập
     */
    public function postLogin(
        AuthRequest $request
    ): RedirectResponse {

        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
            'status'   => 1,
        ];

        $remember = $request->boolean('remember');

        if (!Auth::guard('admin')->attempt($credentials, $remember)) {
            Toastr::error(
                'Email hoặc mật khẩu không chính xác!'
            );
            return redirect()
                ->back()
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        /** @var Admin $user */
        $user = Auth::guard('admin')->user();

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        $route = $this->redirectByRole($user);

        if (!$route) {

            Auth::guard('admin')->logout();

            Toastr::error(
                'Bạn không có quyền truy cập hệ thống!',
            );

            return redirect()->route('login');
        }

        Toastr::success(
            'Đăng nhập thành công!',
        );

        return redirect()->route($route);
    }

    /**
     * Logout
     */
    public function logout(): RedirectResponse
    {
        Auth::guard('admin')->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        Toastr::success(
            'Đăng xuất thành công!',
        );

        return redirect()->route('admin.getLogin');
    }


    /**
     * Redirect dashboard by role
     */
    private function redirectByRole(
        Admin $user
    ): ?string {

        return match (true) {

            $user->hasRole('super-admin')
            => 'admin.dashboard.overview',

            // $user->hasRole('manager')
            // => 'manager.dashboard',

            // $user->hasRole('staff')
            // => 'staff.dashboard',

            default => null,
        };
    }
}
