<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\PaymentTransaction;

use App\Services\Frontend\MomoService;

class MomoController extends Controller
{
    public function __construct(
        protected MomoService $momoService
    ) {}

    /**
     * =========================
     * RETURN URL
     * =========================
     */
    public function return(Request $request)
    {
        $input = $request->all();

        // verify signature
        if (!$this->momoService->verify($input)) {

            return redirect()
                ->route('order.failed')
                ->with('error', 'Sai chữ ký');
        }

        $orderCode = $input['orderId'] ?? null;

        $order = Order::query()
            ->where('order_code', $orderCode)
            ->first();

        if (!$order) {

            return redirect()
                ->route('order.failed')
                ->with('error', 'Không tìm thấy đơn hàng');
        }

        /**
         * KHÔNG update DB ở RETURN
         * Chỉ redirect UI
         */

        if ($order->payment_status === Order::PAYMENT_PAID) {

            return redirect()
                ->route(
                    'order.success',
                    $order->order_code
                );
        }

        return redirect()
            ->route('order.pending')
            ->with(
                'info',
                'Đang xác nhận thanh toán'
            );
    }

    /**
     * =========================
     * MOMO IPN
     * =========================
     */
    public function ipn(Request $request)
    {
        $input = $request->all();

        // =========================
        // VERIFY SIGNATURE
        // =========================
        if (!$this->momoService->verify($input)) {

            return response()->json([
                'resultCode' => 97,
                'message' => 'Invalid signature'
            ]);
        }

        $orderCode = $input['orderId'] ?? null;

        $order = Order::query()
            ->where('order_code', $orderCode)
            ->first();

        // =========================
        // ORDER NOT FOUND
        // =========================
        if (!$order) {

            return response()->json([
                'resultCode' => 1,
                'message' => 'Order not found'
            ]);
        }

        // =========================
        // CANCELLED ORDER
        // =========================
        if (
            $order->order_status ===
            Order::STATUS_CANCELLED
        ) {

            return response()->json([
                'resultCode' => 99,
                'message' => 'Order cancelled'
            ]);
        }

        // =========================
        // EXPIRED PAYMENT
        // =========================
        if (
            $order->payment_deadline &&
            now()->gt($order->payment_deadline)
        ) {

            return response()->json([
                'resultCode' => 98,
                'message' => 'Payment expired'
            ]);
        }

        // =========================
        // VALIDATE AMOUNT
        // =========================
        if (
            (float)$input['amount']
            !=
            (float)$order->grand_total
        ) {

            return response()->json([
                'resultCode' => 98,
                'message' => 'Invalid amount'
            ]);
        }

        // =========================
        // ALREADY PAID
        // =========================
        if (
            $order->payment_status ===
            Order::PAYMENT_PAID
        ) {

            return response()->json([
                'resultCode' => 0,
                'message' => 'Order already confirmed'
            ]);
        }

        // =========================
        // GET TRANSACTION
        // =========================
        $transaction = PaymentTransaction::query()
            ->where('order_id', $order->id)
            ->where('gateway', 'momo')
            ->latest('id')
            ->first();

        // =========================
        // PAYMENT SUCCESS
        // =========================
        if (($input['resultCode'] ?? 1) == 0) {

            $order->update([

                'payment_status' => Order::PAYMENT_PAID,

                'order_status' => Order::STATUS_CONFIRMED,

                'paid_at' => now(),
            ]);

            if ($transaction) {

                $transaction->update([

                    'transaction_id'
                    => $input['transId'] ?? null,

                    'transaction_code'
                    => $input['orderId'] ?? null,

                    'status' => 'success',

                    'response_data' => $input,

                    'paid_at' => now(),
                ]);
            }

            return response()->json([
                'resultCode' => 0,
                'message' => 'Success'
            ]);
        }

        // =========================
        // PAYMENT FAILED
        // =========================
        $order->update([
            'payment_status' => Order::PAYMENT_FAILED
        ]);

        if ($transaction) {

            $transaction->update([

                'status' => 'failed',

                'response_data' => $input,

                'failure_reason'
                => $input['message']
                    ?? 'Thanh toán thất bại',
            ]);
        }

        return response()->json([
            'resultCode' => 0,
            'message' => 'Payment failed'
        ]);
    }
}
