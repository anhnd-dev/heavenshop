<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */
    public function getDataTable(bool $includeTrashed = false)
    {
        $query = Order::query()
            ->with([
                'customer'
            ]);

        if ($includeTrashed) {
            $query->onlyTrashed();
        }

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

                return $order->payment_badge;
            })

            ->addColumn('order_badge', function ($order) {

                return $order->order_badge;
            })

            ->addColumn('created_at_format', function ($order) {

                return Carbon::parse(
                    $order->created_at
                )->format('d/m/Y H:i');
            })

            ->addColumn('order_status_value', function ($order) {
                return $order->order_status;
            })

            ->addColumn('action', function ($order) use ($includeTrashed) {

                if ($includeTrashed) {

                    return '
                        <a href="' . route(
                        'admin.order.show',
                        ['id' => $order->id]
                    ) . '"
                        class="btn btn-info shadow btn-xs sharp mr-1">

                            <i class="fas fa-eye"></i>

                        </a>

                        <button type="button"
                            data-id="' . $order->id . '"
                            class="restoreOrderBtn btn btn-success shadow btn-xs sharp mr-1">

                            <i class="fas fa-trash-restore"></i>

                        </button>
                    ';
                }

                $html = '
                    <a href="' . route(
                    'admin.order.show',
                    ['id' => $order->id]
                ) . '"
                    class="btn btn-info shadow btn-xs sharp mr-1">

                        <i class="fas fa-eye"></i>

                    </a>
                ';

                /*
                |--------------------------------------------------------------------------
                | CHỈ CHO XÓA ĐƠN ĐÃ HỦY
                |--------------------------------------------------------------------------
                */
                if (
                    $order->order_status === Order::STATUS_CANCELLED
                ) {
                    $html .= '
                        <button type="button"
                            data-id="' . $order->id . '"
                            class="deleteOrderBtn btn btn-danger shadow btn-xs sharp">

                            <i class="fa fa-trash"></i>

                        </button>
                    ';
                }

                return $html;
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

        $status = trim($status);

        if (
            ! $order->canMoveTo($status)
        ) {
            throw new \Exception(
                'Trạng thái đơn hàng không hợp lệ'
            );
        }

        return match ($status) {

            Order::STATUS_CANCELLED
            => $this->cancelOrder($order),

            Order::STATUS_RETURNED
            => $this->returnOrder($order),

            default
            => $this->changeNormalStatus(
                $order,
                $status
            ),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | CANCEL EXPIRED ORDERS
    |--------------------------------------------------------------------------
    */
    public function cancelExpiredOrders(): int
    {
        return DB::transaction(function () {

            $orders = Order::query()

                ->with('items')

                ->whereIn('payment_method', [
                    Order::PAYMENT_VNPAY,
                    Order::PAYMENT_MOMO,
                ])

                ->where('order_status', Order::STATUS_PENDING)

                ->where('payment_status', Order::PAYMENT_PENDING)

                ->whereNotNull('payment_deadline')

                ->where(
                    'payment_deadline',
                    '<',
                    now()
                )

                ->lockForUpdate()

                ->get();

            $count = 0;

            foreach ($orders as $order) {

                /*
                |--------------------------------------------------------------
                | RESTORE STOCK
                |--------------------------------------------------------------
                */

                foreach ($order->items as $item) {

                    ProductVariant::where(
                        'id',
                        $item->product_variant_id
                    )->increment(
                        'stock',
                        $item->quantity
                    );
                }

                /*
                |--------------------------------------------------------------
                | UPDATE ORDER
                |--------------------------------------------------------------
                */

                $order->update([

                    'order_status'
                    => Order::STATUS_CANCELLED,

                    'payment_status'
                    => Order::PAYMENT_FAILED,

                    'cancelled_at'
                    => now(),
                ]);

                /*
                |--------------------------------------------------------------
                | UPDATE PAYMENT TRANSACTION
                |--------------------------------------------------------------
                */

                $order->paymentTransactions()
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'failed',
                    ]);

                $count++;
            }

            return $count;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CANCEL ORDERS
    |--------------------------------------------------------------------------
    */
    public function cancelOrder(
        Order $order,
    ): bool {

        return DB::transaction(function () use ($order) {

            if (
                in_array(
                    $order->order_status,
                    [
                        Order::STATUS_DELIVERED,
                        Order::STATUS_CANCELLED,
                        Order::STATUS_RETURNED,
                    ]
                )
            ) {
                throw new \Exception(
                    'Không thể hủy đơn hàng này'
                );
            }

            /*
            |-----------------------------------
            | RESTORE STOCK
            |-----------------------------------
            */

            $this->restoreStock($order);

            $data = [

                'order_status'
                => Order::STATUS_CANCELLED,

                'cancelled_at'
                => now(),
            ];

            /*
            |-----------------------------------
            | PAYMENT
            |-----------------------------------
            */

            if (
                $order->payment_status
                === Order::PAYMENT_PENDING
            ) {

                $data['payment_status']
                    = Order::PAYMENT_FAILED;
            }

            $order->update($data);

            /*
            |-----------------------------------
            | PAYMENT TRANSACTION
            |-----------------------------------
            */

            $order->paymentTransactions()
                ->where('status', 'pending')
                ->update([
                    'status' => 'failed',
                ]);

            return true;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RETURN ORDERS
    |--------------------------------------------------------------------------
    */
    public function returnOrder(
        Order $order
    ): bool {
        return DB::transaction(function () use ($order) {

            if (
                $order->order_status
                !== Order::STATUS_DELIVERED
            ) {
                throw new \Exception(
                    'Chỉ được hoàn trả đơn đã giao'
                );
            }

            /*
        |-----------------------------------
        | RESTORE STOCK
        |-----------------------------------
        */

            $this->restoreStock($order);

            $data = [

                'order_status'
                => Order::STATUS_RETURNED,

                'returned_at'
                => now(),
            ];

            /*
        |-----------------------------------
        | REFUND
        |-----------------------------------
        */

            if (
                $order->payment_status
                === Order::PAYMENT_PAID
            ) {

                $data['payment_status']
                    = Order::PAYMENT_REFUNDED;

                $data['refunded_at']
                    = now();
            }

            $order->update($data);

            return true;
        });
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

        if (
            ! $order->canChangePaymentTo(
                $status
            )
        ) {
            throw new \Exception(
                'Trạng thái thanh toán không hợp lệ'
            );
        }

        $data = [
            'payment_status' => $status,
        ];

        switch ($status) {

            case Order::PAYMENT_PAID:

                $data['paid_at']
                    = now();

                break;

            case Order::PAYMENT_FAILED:

                $data['paid_at']
                    = null;

                break;

            case Order::PAYMENT_REFUNDED:

                $data['refunded_at']
                    = now();

                break;
        }

        return $order->update($data);
    }

    public function getAvailableStatuses(
        Order $order
    ): array {

        return $order->availableOrderStatuses();
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    // RESTORE STOCK
    private function restoreStock(
        Order $order
    ): void {
        foreach (
            $order->items as $item
        ) {

            ProductVariant::query()
                ->where(
                    'id',
                    $item->product_variant_id
                )
                ->increment(
                    'stock',
                    $item->quantity
                );
        }
    }

    // CHANGE NORMAL STATUS
    private function changeNormalStatus(
        Order $order,
        string $status
    ): bool {
        $data = [
            'order_status' => $status,
        ];

        switch ($status) {

            case Order::STATUS_CONFIRMED:

                $data['confirmed_at']
                    = now();

                break;

            case Order::STATUS_SHIPPING:

                $data['shipped_at']
                    = now();

                break;

            case Order::STATUS_DELIVERED:

                $data['delivered_at']
                    = now();

                /*
                |-----------------------------------
                | COD => AUTO PAID
                |-----------------------------------
                */

                if (
                    $order->payment_method
                    === Order::PAYMENT_COD
                    &&
                    $order->isPendingPayment()
                ) {

                    $data['payment_status']
                        = Order::PAYMENT_PAID;

                    $data['paid_at']
                        = $order->paid_at ?? now();
                }

                break;
        }

        return $order->update($data);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function delete(
        Order $order
    ): bool {

        if (
            $order->order_status
            !== Order::STATUS_CANCELLED
        ) {
            throw new \Exception(
                'Chỉ được xóa đơn hàng đã hủy'
            );
        }

        return $order->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | RESTORE
    |--------------------------------------------------------------------------
    */
    public function restore(
        int $id
    ): bool {

        $order = Order::onlyTrashed()
            ->findOrFail($id);

        return (bool) $order->restore();
    }
}
