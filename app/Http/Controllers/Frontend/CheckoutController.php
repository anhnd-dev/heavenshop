<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Checkout\CheckoutPlaceOrderRequest;
use App\Services\Frontend\OrderService;

class CheckoutController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    /**
     * PLACE ORDER
     */
    public function placeOrder(
        CheckoutPlaceOrderRequest $request
    ) {
        try {

            // =========================
            // CREATE ORDER
            // =========================
            $result = $this->orderService->createOrder(
                $request->validated()
            );

            return response()->json([
                'status' => 200,
                'message' => 'Đặt hàng thành công',
                'order_code' => $result['order']->order_code,
                'payment_url' => $result['payment_url'] ?? null,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * ORDER SUCCESS
     */
}
