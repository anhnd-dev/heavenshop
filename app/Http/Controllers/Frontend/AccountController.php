<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\CustomerAddress;
use App\Models\CouponCustomer;
use App\Services\Frontend\AccountService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    public function __construct(
        protected AccountService $accountService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        $customer = Auth::guard('customer')->user();

        return view('frontend.account.profile', compact('customer'));
    }

    public function updateAvatar(Request $request): JsonResponse
    {
        return response()->json(
            $this->accountService->updateAvatar($request)
        );
    }

    public function updateProfile(Request $request): JsonResponse
    {
        return response()->json(
            $this->accountService->updateProfile($request)
        );
    }

    public function updatePassword(Request $request): JsonResponse
    {
        return response()->json(
            $this->accountService->updatePassword($request)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ORDERS
    |--------------------------------------------------------------------------
    */

    public function orders(Request $request)
    {
        $customerId = Auth::guard('customer')->id();

        $orders = Order::query()
            ->ofCustomer($customerId)
            ->when($request->keyword, function ($q, $keyword) {
                $q->where('order_code', 'like', "%{$keyword}%");
            })
            ->when($request->status, function ($q, $status) {
                $q->where('order_status', $status);
            })
            ->when($request->range, function ($q, $range) {

                match ($range) {
                    'today' => $q->whereDate('created_at', today()),
                    '3days' => $q->where('created_at', '>=', now()->subDays(3)),
                    'week'  => $q->where('created_at', '>=', now()->subWeek()),
                    'month' => $q->where('created_at', '>=', now()->subMonth()),
                    default => null,
                };
            })
            ->when($request->from_date && $request->to_date, function ($q) use ($request) {
                $q->whereBetween('created_at', [
                    $request->from_date . ' 00:00:00',
                    $request->to_date . ' 23:59:59',
                ]);
            })
            ->with('items')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = Order::where('customer_id', $customerId)
            ->selectRaw("
                COUNT(*) as total_orders,
                SUM(order_status = 'pending') as pending_orders,
                SUM(order_status = 'shipping') as shipping_orders,
                SUM(order_status = 'delivered') as delivered_orders
            ")
            ->first();

        $highlight = $request->highlight;

        return view('frontend.account.orders', [
            'orders'         => $orders,
            'totalOrders'    => $stats->total_orders ?? 0,
            'pendingOrders'  => $stats->pending_orders ?? 0,
            'shippingOrders' => $stats->shipping_orders ?? 0,
            'deliveredOrders' => $stats->delivered_orders ?? 0,

            'highlight'       => $highlight,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ORDER DETAIL
    |--------------------------------------------------------------------------
    */

    public function orderDetail(Order $order): JsonResponse
    {
        abort_if($order->customer_id !== auth('customer')->id(), 403);

        $order->load('items');

        $html = view(
            'frontend.account.partials.order-detail-modal',
            compact('order')
        )->render();

        return response()->json(['html' => $html]);
    }

    /*
    |--------------------------------------------------------------------------
    | CANCEL ORDER
    |--------------------------------------------------------------------------
    */

    public function orderCancel(Request $request, Order $order): JsonResponse
    {
        abort_if($order->customer_id !== auth('customer')->id(), 403);

        if (! $order->canCustomerCancel()) {
            return response()->json([
                'message' => 'Đơn hàng không thể hủy'
            ], 422);
        }

        $order->update([
            'order_status' => Order::STATUS_CANCELLED,
            'cancel_reason' => $request->cancel_reason,
            'cancelled_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hủy đơn hàng thành công'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ADDRESSES
    |--------------------------------------------------------------------------
    */

    public function addresses()
    {
        return view('frontend.account.addresses');
    }

    public function show(Request $request): JsonResponse
    {
        $address = CustomerAddress::where('customer_id', auth('customer')->id())
            ->findOrFail($request->id);

        return response()->json([
            'id'          => $address->id,
            'full_name'   => $address->full_name,
            'phone'       => $address->phone,
            'email'       => auth('customer')->user()->email,
            'address'     => $address->address,
            'province_id' => $address->province_id,
            'district_id' => $address->district_id,
            'ward_id'     => $address->ward_id,
        ]);
    }

    public function storeAddress(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name'   => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'address'     => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'ward_id'     => 'required|exists:wards,id',
        ]);

        $validated['customer_id'] = auth('customer')->id();

        CustomerAddress::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Thêm địa chỉ thành công'
        ]);
    }

    public function updateAddress(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'address_id'  => 'required|integer',
            'full_name'   => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'address'     => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'ward_id'     => 'required|exists:wards,id',
        ]);

        $address = CustomerAddress::where('customer_id', auth('customer')->id())
            ->findOrFail($validated['address_id']);

        $address->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật địa chỉ thành công'
        ]);
    }

    public function setDefault(Request $request): JsonResponse
    {
        $customerId = auth('customer')->id();

        CustomerAddress::where('customer_id', $customerId)
            ->update(['is_default' => 0]);

        CustomerAddress::where('id', $request->id)
            ->where('customer_id', $customerId)
            ->update(['is_default' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Đã đặt làm địa chỉ mặc định'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VOUCHERS
    |--------------------------------------------------------------------------
    */

    public function vouchers()
    {
        $customerId = auth('customer')->id();

        $available = CouponCustomer::with('coupon')
            ->ofCustomer($customerId)
            ->available()
            ->latest()
            ->get();

        $used = CouponCustomer::with('coupon')
            ->ofCustomer($customerId)
            ->used()
            ->latest()
            ->get();

        $expired = CouponCustomer::with('coupon')
            ->ofCustomer($customerId)
            ->expired()
            ->latest()
            ->get();

        return view('frontend.account.vouchers', compact(
            'available',
            'used',
            'expired'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->forget([
            'cart',
            'applied_coupon',
            'continue_shopping_url',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Toastr::success('Đăng xuất thành công');

        return redirect()->route('home');
    }
}
