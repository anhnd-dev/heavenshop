<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\PaymentTransaction;

use App\Services\Frontend\PaymentService;

class VnpayController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
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
        if (!$this->paymentService->verify($input)) {

            return redirect()
                ->route('order.failed')
                ->with('error', 'Sai chữ ký');
        }

        $orderCode =
            $input['vnp_TxnRef'] ?? null;

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
     * VNPAY IPN
     * =========================
     */
    public function ipn(Request $request)
    {
        $input = $request->all();

        // =========================
        // VERIFY SIGNATURE
        // =========================
        if (!$this->paymentService->verify($input)) {

            return response()->json([
                'RspCode' => '97',
                'Message' => 'Invalid signature'
            ]);
        }

        $orderCode =
            $input['vnp_TxnRef'] ?? null;

        $order = Order::query()
            ->where('order_code', $orderCode)
            ->first();

        // =========================
        // ORDER NOT FOUND
        // =========================
        if (!$order) {

            return response()->json([
                'RspCode' => '01',
                'Message' => 'Order not found'
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
                'RspCode' => '99',
                'Message' => 'Order cancelled'
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
                'RspCode' => '98',
                'Message' => 'Payment expired'
            ]);
        }

        // =========================
        // VALIDATE AMOUNT
        // =========================
        if (
            ((int)$input['vnp_Amount'] / 100)
            !=
            (int)$order->grand_total
        ) {

            return response()->json([
                'RspCode' => '04',
                'Message' => 'Invalid amount'
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
                'RspCode' => '02',
                'Message' => 'Order already confirmed'
            ]);
        }

        // =========================
        // GET TRANSACTION
        // =========================
        $transaction = PaymentTransaction::query()
            ->where('order_id', $order->id)
            ->where('gateway', 'vnpay')
            ->latest('id')
            ->first();

        // =========================
        // SUCCESS
        // =========================
        if (
            ($input['vnp_ResponseCode'] ?? null)
            == '00'
        ) {

            $order->update([

                'payment_status' => Order::PAYMENT_PAID,

                'order_status' => Order::STATUS_CONFIRMED,

                'paid_at' => now(),
            ]);

            if ($transaction) {

                $transaction->update([

                    'transaction_id'
                    => $input['vnp_TransactionNo'] ?? null,

                    'transaction_code'
                    => $input['vnp_BankTranNo'] ?? null,

                    'status' => 'success',

                    'response_data' => $input,

                    'paid_at' => now(),
                ]);
            }

            return response()->json([
                'RspCode' => '00',
                'Message' => 'Confirm Success'
            ]);
        }

        // =========================
        // FAILED
        // =========================
        $order->update([
            'payment_status' => Order::PAYMENT_FAILED
        ]);

        if ($transaction) {

            $transaction->update([

                'status' => 'failed',

                'response_data' => $input,

                'failure_reason'
                => 'Thanh toán thất bại',
            ]);
        }

        return response()->json([
            'RspCode' => '00',
            'Message' => 'Payment Failed'
        ]);
    }
}
