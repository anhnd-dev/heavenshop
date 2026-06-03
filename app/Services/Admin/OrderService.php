<?php

namespace App\Services\Admin;

use App\Models\Order;
use Carbon\Carbon;

class OrderService
{
    /*
    |--------------------------------------------------------------------------
    | ORDER FLOW
    |--------------------------------------------------------------------------
    */

    private const ORDER_FLOW = [

        Order::STATUS_PENDING => [
            Order::STATUS_CONFIRMED,
            Order::STATUS_CANCELLED,
        ],

        Order::STATUS_CONFIRMED => [
            Order::STATUS_SHIPPING,
            Order::STATUS_CANCELLED,
        ],

        Order::STATUS_SHIPPING => [
            Order::STATUS_DELIVERED,
            Order::STATUS_RETURNED,
        ],

        Order::STATUS_DELIVERED => [],

        Order::STATUS_CANCELLED => [],

        Order::STATUS_RETURNED => [],
    ];

    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    public function getDataTable()
    {
        $query = Order::query()
            ->with([
                'customer'
            ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if (request()->filled('keyword')) {

            $keyword = request('keyword');

            $query->where(function ($q) use ($keyword) {

                $q->where(
                    'order_code',
                    'like',
                    "%{$keyword}%"
                )

                    ->orWhere(
                        'shipping_name',
                        'like',
                        "%{$keyword}%"
                    )

                    ->orWhere(
                        'shipping_phone',
                        'like',
                        "%{$keyword}%"
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | ORDER STATUS
        |--------------------------------------------------------------------------
        */

        if (request()->filled('order_status')) {

            $query->where(
                'order_status',
                request('order_status')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT STATUS
        |--------------------------------------------------------------------------
        */

        if (request()->filled('payment_status')) {

            $query->where(
                'payment_status',
                request('payment_status')
            );
        }

        return datatables()
            ->of($query)

            ->addIndexColumn()

            ->addColumn('customer_name', function ($order) {

                return $order->shipping_name;
            })

            ->addColumn('customer_phone', function ($order) {

                return $order->shipping_phone;
            })

            ->addColumn('total_format', function ($order) {

                return number_format(
                    $order->grand_total
                ) . ' ₫';
            })

            ->addColumn('payment_badge', function ($order) {

                return $this->paymentBadge(
                    $order->payment_status
                );
            })

            ->addColumn('order_badge', function ($order) {

                return $this->orderBadge(
                    $order->order_status
                );
            })

            ->addColumn('created_at_format', function ($order) {

                return Carbon::parse(
                    $order->created_at
                )->format(
                    'd/m/Y H:i'
                );
            })

            ->addColumn('action', function ($order) {

                return '

                    <a href="' . route(
                    'admin.order.show',
                    ['id' => $order->id]
                ) . '"
                        class="btn btn-info shadow btn-xs sharp">

                        <i class="fas fa-eye"></i>

                    </a>

                ';
            })

            ->rawColumns([
                'payment_badge',
                'order_badge',
                'action'
            ])

            ->make(true);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE ORDER STATUS
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        Order $order,
        string $status
    ): bool {

        $allowed = self::ORDER_FLOW[$order->order_status] ?? [];

        if (
            !in_array(
                $status,
                $allowed
            )
        ) {
            throw new \Exception(
                'Trạng thái đơn hàng không hợp lệ'
            );
        }

        $data = [
            'order_status' => $status
        ];

        switch ($status) {

            case Order::STATUS_CONFIRMED:

                $data['confirmed_at'] = now();

                break;

            case Order::STATUS_SHIPPING:

                $data['shipped_at'] = now();

                break;

            case Order::STATUS_DELIVERED:

                $data['delivered_at'] = now();

                break;

            case Order::STATUS_CANCELLED:

                $data['cancelled_at'] = now();

                break;

            case Order::STATUS_RETURNED:

                $data['returned_at'] = now();

                break;
        }

        return $order->update($data);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PAYMENT STATUS
    |--------------------------------------------------------------------------
    */

    public function updatePaymentStatus(
        Order $order,
        string $status
    ): bool {

        $data = [
            'payment_status' => $status
        ];

        switch ($status) {

            case Order::PAYMENT_PAID:

                $data['paid_at'] = now();

                break;

            case Order::PAYMENT_REFUNDED:

                $data['refunded_at'] = now();

                break;
        }

        return $order->update($data);
    }

    /*
    |--------------------------------------------------------------------------
    | ORDER BADGE
    |--------------------------------------------------------------------------
    */

    private function orderBadge(
        string $status
    ): string {

        return match ($status) {

            Order::STATUS_PENDING =>
            '<span class="badge badge-warning">Chờ xác nhận</span>',

            Order::STATUS_CONFIRMED =>
            '<span class="badge badge-primary">Đã xác nhận</span>',

            Order::STATUS_SHIPPING =>
            '<span class="badge badge-info">Đang giao</span>',

            Order::STATUS_DELIVERED =>
            '<span class="badge badge-success">Đã giao</span>',

            Order::STATUS_CANCELLED =>
            '<span class="badge badge-danger">Đã hủy</span>',

            Order::STATUS_RETURNED =>
            '<span class="badge badge-dark">Hoàn trả</span>',

            default =>
            '<span class="badge badge-secondary">Không xác định</span>',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT BADGE
    |--------------------------------------------------------------------------
    */

    private function paymentBadge(
        string $status
    ): string {

        return match ($status) {

            Order::PAYMENT_PENDING =>
            '<span class="badge badge-warning">Chờ thanh toán</span>',

            Order::PAYMENT_PAID =>
            '<span class="badge badge-success">Đã thanh toán</span>',

            Order::PAYMENT_FAILED =>
            '<span class="badge badge-danger">Thất bại</span>',

            Order::PAYMENT_REFUNDED =>
            '<span class="badge badge-dark">Hoàn tiền</span>',

            default =>
            '<span class="badge badge-secondary">Không xác định</span>',
        };
    }
}
