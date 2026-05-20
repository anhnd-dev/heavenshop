<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Brian2694\Toastr\Facades\Toastr;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermissionAcl
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $permission = null)
    {
        /** @var Admin|null $admin */
        $admin = Auth::guard('admin')->user();

        // Nếu chưa đăng nhập
        if (!$admin) {
            return redirect()->route('admin.login');
        }

        // Check permission
        if ($admin->hasPermission($permission)) {
            return $next($request);
        }

        // Không có quyền
        Toastr::error('Bạn không có quyền truy cập!', 'Error');

        return redirect()->back();
    }
}
