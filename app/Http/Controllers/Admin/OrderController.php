<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\Admin\OrderService;

class OrderController extends BaseAdminController
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->orderService
                ->getDataTable();
        }

        return view(
            'admin.pages.order.index'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Request $request)
    {
        $order = Order::with([
            'customer',
            'coupon',
            'items',
            'paymentTransactions',
        ])->findOrFail($request->id);

        return view(
            'admin.pages.order.show',
            compact(
                'order',
            )
        );
    }

    public function print() {}

    /*
    |--------------------------------------------------------------------------
    | UPDATE ORDER STATUS
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        Request $request
    ) {
        return $this->transaction(function () use ($request) {

            $order = Order::findOrFail(
                $request->id
            );

            $this->orderService
                ->updateStatus(
                    $order,
                    $request->status
                );

            return $this->successResponse(
                'Cập nhật trạng thái đơn hàng thành công'
            );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PAYMENT STATUS
    |--------------------------------------------------------------------------
    */

    public function updatePaymentStatus(
        Request $request
    ) {
        return $this->transaction(function () use ($request) {

            $order = Order::findOrFail(
                $request->id
            );

            $this->orderService
                ->updatePaymentStatus(
                    $order,
                    $request->status
                );

            return $this->successResponse(
                'Cập nhật trạng thái thanh toán thành công'
            );
        });
    }
}
