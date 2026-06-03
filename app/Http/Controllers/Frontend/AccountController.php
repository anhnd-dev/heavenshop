<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\CustomerAddress;
use App\Models\CouponCustomer;

use App\Http\Controllers\Controller;

use App\Services\Frontend\AccountService;
use Brian2694\Toastr\Facades\Toastr;

use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    public function __construct(
        protected AccountService $accountService
    ) {}

    public function show(Request $request)
    {
        $address = CustomerAddress::where(
            'customer_id',
            Auth::guard('customer')->id()
        )->findOrFail($request->id);

        return response()->json([
            'id'          => $address->id,
            'full_name'   => $address->full_name,
            'phone'       => $address->phone,
            'email'       => Auth::guard('customer')->user()->email,
            'address'     => $address->address,
            'province_id' => $address->province_id,
            'district_id' => $address->district_id,
            'ward_id'     => $address->ward_id,
        ]);
    }

    // Account Profile
    public function profile()
    {
        $customer = Auth::guard('customer')
            ->user();

        return view(
            'frontend.account.profile',
            compact('customer')
        );
    }

    public function updateAvatar(Request $request)
    {
        return response()->json(
            $this->accountService
                ->updateAvatar($request)
        );
    }

    public function updateProfile(Request $request)
    {
        return response()->json(
            $this->accountService
                ->updateProfile($request)
        );
    }

    public function updatePassword(Request $request)
    {
        return response()->json(
            $this->accountService
                ->updatePassword($request)
        );
    }

    // Account Order
    public function orders(Request $request)
    {
        $customerId = Auth::guard('customer')->id();

        $query = Order::ofCustomer($customerId);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('keyword')) {

            $query->where(
                'order_code',
                'like',
                '%' . $request->keyword . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'order_status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RANGE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('range')) {

            switch ($request->range) {

                case 'today':

                    $query->whereDate(
                        'created_at',
                        today()
                    );

                    break;

                case '3days':

                    $query->where(
                        'created_at',
                        '>=',
                        now()->subDays(3)
                    );

                    break;

                case 'week':

                    $query->where(
                        'created_at',
                        '>=',
                        now()->subWeek()
                    );

                    break;

                case 'month':

                    $query->where(
                        'created_at',
                        '>=',
                        now()->subMonth()
                    );

                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CUSTOM DATE
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('from_date')
            &&
            $request->filled('to_date')
        ) {

            $query->whereBetween(
                'created_at',
                [
                    $request->from_date . ' 00:00:00',
                    $request->to_date . ' 23:59:59'
                ]
            );
        }

        $orders = $query
            ->with('items')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | STATS
        |--------------------------------------------------------------------------
        */

        $stats = Order::query()
            ->where('customer_id', $customerId)
            ->selectRaw("
                COUNT(*) as total_orders,
                SUM(CASE WHEN order_status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                SUM(CASE WHEN order_status = 'shipping' THEN 1 ELSE 0 END) as shipping_orders,
                SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END) as delivered_orders
            ")
            ->first();

        return view(
            'frontend.account.orders',
            [
                'orders' => $orders,

                'totalOrders' => $stats->total_orders,

                'pendingOrders' => $stats->pending_orders,

                'shippingOrders' => $stats->shipping_orders,

                'deliveredOrders' => $stats->delivered_orders,
            ]
        );
    }

    public function orderDetail(Order $order)
    {
        abort_if(
            $order->customer_id !== Auth::guard('customer')->id(),
            403
        );

        $order->load('items');

        return response()->json([
            'html' => view(
                'frontend.account.partials.order-detail-modal',
                compact('order')
            )->render()
        ]);
    }

    public function orderCancel(
        Request $request,
        Order $order
    ) {
        abort_if(
            $order->customer_id !==
                Auth::guard('customer')->id(),
            403
        );

        if (! $order->canCancel()) {

            return response()->json([
                'message' =>
                'Đơn hàng không thể hủy'
            ], 422);
        }

        $order->update([

            'order_status' =>
            Order::STATUS_CANCELLED,

            'cancel_reason' =>
            $request->cancel_reason,

            'cancelled_at' =>
            now(),
        ]);

        return response()->json([

            'success' => true,

            'message' =>
            'Hủy đơn hàng thành công'
        ]);
    }

    // Account Address
    public function addresses()
    {
        return view(
            'frontend.account.addresses'
        );
    }

    public function storeAddress(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name'   => ['required', 'string', 'max:255'],
            'phone'       => ['required', 'string', 'max:20'],
            'address'     => ['required', 'string', 'max:255'],

            'province_id' => ['required', 'exists:provinces,id'],
            'district_id' => ['required', 'exists:districts,id'],
            'ward_id'     => ['required', 'exists:wards,id'],
        ]);

        $validated['customer_id'] = auth('customer')->id();

        CustomerAddress::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Thêm địa chỉ thành công',
        ]);
    }

    public function updateAddress(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'address_id'  => ['required', 'integer'],

            'full_name'   => ['required', 'string', 'max:255'],
            'phone'       => ['required', 'string', 'max:20'],
            'address'     => ['required', 'string', 'max:255'],

            'province_id' => ['required', 'exists:provinces,id'],
            'district_id' => ['required', 'exists:districts,id'],
            'ward_id'     => ['required', 'exists:wards,id'],
        ]);

        $address = CustomerAddress::where(
            'customer_id',
            auth('customer')->id()
        )->findOrFail($validated['address_id']);

        $address->update([
            'full_name'   => $validated['full_name'],
            'phone'       => $validated['phone'],
            'address'     => $validated['address'],

            'province_id' => $validated['province_id'],
            'district_id' => $validated['district_id'],
            'ward_id'     => $validated['ward_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật địa chỉ thành công',
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
            'status' => true,
            'message' => 'Đã đặt làm địa chỉ mặc định'
        ]);
    }

    // Account voucher
    public function vouchers()
    {
        $customerId = Auth::guard('customer')->id();

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

    // Logout
    public function logout(Request $request)
    {
        Auth::guard('customer')
            ->logout();

        $request->session()->forget([
            'cart',
            'applied_coupon',
        ]);

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        Toastr::success('Đăng xuất thành công');

        return redirect()->route('home');
    }
}
