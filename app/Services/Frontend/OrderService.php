<?php

namespace App\Services\Frontend;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\PaymentTransaction;
use App\Models\CouponCustomer;
use App\Models\CustomerAddress;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function __construct(
        protected CartService $cartService,
        protected PaymentService $paymentService,
        protected MomoService $momoService,
        protected CouponService $couponService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | CREATE ORDER
    |--------------------------------------------------------------------------
    */

    public function createOrder(array $data): array
    {
        return DB::transaction(function () use ($data) {

            $customer = Auth::guard('customer')->user();

            /*
            |--------------------------------------------------------------------------
            | CART
            |--------------------------------------------------------------------------
            */
            $cart = $this->cartService->selectedItems();

            if (empty($cart)) {
                throw new \Exception('Vui lòng chọn sản phẩm');
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDATE STOCK
            |--------------------------------------------------------------------------
            */
            $subtotal = 0;
            $variants = [];

            // =========================
            foreach ($cart as $item) {

                $variant = ProductVariant::query()
                    ->lockForUpdate()
                    ->find($item['variant_id']);

                if (!$variant) {
                    throw new \Exception('Sản phẩm không tồn tại');
                }

                if ($variant->stock < $item['quantity']) {
                    throw new \Exception(
                        'Không đủ tồn kho: ' . $item['product_name']
                    );
                }

                $variants[$variant->id] = $variant;

                $subtotal += $item['price'] * $item['quantity'];
            }

            /*
            |--------------------------------------------------------------------------
            | SHIPPING
            |--------------------------------------------------------------------------
            */
            $shippingFee = $this->cartService->shipping($subtotal);

            /*
            |--------------------------------------------------------------------------
            | COUPON RE-CHECK (VERY IMPORTANT)
            |--------------------------------------------------------------------------
            */
            $couponSession = session('applied_coupon');

            $coupon = null;
            $discount = 0;
            $couponId = null;

            if (
                $couponSession
                && isset($couponSession['id'])
            ) {

                $coupon = Coupon::query()
                    ->lockForUpdate()
                    ->find($couponSession['id']);

                if (
                    ! $coupon
                    || ! $coupon->isValid($subtotal)
                ) {

                    session()->forget(
                        'applied_coupon'
                    );

                    $coupon = null;
                } else {

                    $discount = $this->couponService
                        ->calculateDiscount(
                            $coupon,
                            $subtotal
                        );

                    $couponId = $coupon->id;
                }
            }

            $grandTotal = max($subtotal + $shippingFee - $discount, 0);

            /*
            |--------------------------------------
            | ADDRESS HANDLING
            |--------------------------------------
            */
            $addressId = $data['customer_address_id'] ?? null;

            if ($customer) {

                // CASE 1: chọn address có sẵn
                if ($addressId) {
                    $address = CustomerAddress::where('id', $addressId)
                        ->where('customer_id', $customer->id)
                        ->first();

                    $addressId = $address?->id;
                }

                // CASE 2: Lưu address mới
                if (!empty($data['save_address'])) {

                    $address = CustomerAddress::create([
                        'customer_id' => $customer->id,
                        'full_name'   => $data['shipping_name'],
                        'phone'       => $data['shipping_phone'],
                        'address'     => $data['shipping_address'],
                        'province_id' => $data['shipping_province'],
                        'district_id' => $data['shipping_district'],
                        'ward_id'     => $data['shipping_ward'],
                        'is_default'  => false,
                    ]);

                    $addressId = $address->id;
                }

                // chưa có address -> auto create (QUAN TRỌNG)
                if (empty($addressId)) {

                    $address = CustomerAddress::create([
                        'customer_id' => $customer->id,
                        'full_name'   => $data['shipping_name'],
                        'phone'       => $data['shipping_phone'],
                        'address'     => $data['shipping_address'],
                        'province_id' => $data['shipping_province'],
                        'district_id' => $data['shipping_district'],
                        'ward_id'     => $data['shipping_ward'],
                        'is_default'  => false,
                    ]);

                    $addressId = $address->id;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE ORDER
            |--------------------------------------------------------------------------
            */

            $province = Province::findOrFail(
                $data['shipping_province']
            );

            $district = District::findOrFail(
                $data['shipping_district']
            );

            $ward = Ward::findOrFail(
                $data['shipping_ward']
            );
            $order = Order::create([

                'order_code' => $this->generateCode(),
                'customer_id' => $customer?->id,
                'coupon_id' => $couponId,
                'customer_address_id' => $addressId,

                // shipping snapshot
                'shipping_name' => $data['shipping_name'],
                'shipping_phone' => $data['shipping_phone'],
                'shipping_email' => $data['shipping_email'] ?? null,
                'shipping_province' => $province?->name,
                'shipping_district' => $district?->name,
                'shipping_ward' => $ward?->name,
                'shipping_address' => $data['shipping_address'],

                // payment
                'payment_method' => $data['payment_method'],
                'payment_status' =>  Order::PAYMENT_PENDING,
                'order_status' => Order::STATUS_PENDING,

                // money
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'shipping_fee' => $shippingFee,
                'grand_total' => $grandTotal,

                'payment_deadline' => in_array(
                    $data['payment_method'],
                    [
                        Order::PAYMENT_VNPAY,
                        Order::PAYMENT_MOMO,
                    ]
                )
                    ? now()->addMinutes(15)
                    : null,
                'note' => $data['note'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | CREATE ORDER ITEMS
            |--------------------------------------------------------------------------
            */
            foreach ($cart as $item) {

                $variant = $variants[$item['variant_id']];

                OrderItem::create([

                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $variant->id,

                    // product snapshot
                    'product_name' => $item['product_name'],
                    'product_slug' => $item['product_slug'],
                    'product_sku' => $variant->sku ?? null,
                    'product_image' => $item['image'] ?? null,

                    // variant snapshot
                    'variant_name' => ($item['color'] ?? '') . ' / ' . ($item['size'] ?? ''),
                    'color_name' => $item['color'] ?? null,
                    'size_name' => $item['size'] ?? null,

                    // price
                    'original_price' => $variant->price,
                    'final_price' => $item['price'],

                    // quantity
                    'quantity' => $item['quantity'],

                    // total
                    'total' => $item['price'] * $item['quantity'],
                ]);

                // stock decrement
                $variant->decrement(
                    'stock',
                    $item['quantity']
                );
            }

            /*
            |--------------------------------------------------------------------------
            | COUPON TRACKING + USED COUNT
            |--------------------------------------------------------------------------
            */

            if ($couponId && $customer) {

                $coupon->increment('used_count');

                CouponCustomer::create([
                    'coupon_id' => $coupon->id,
                    'customer_id' => $customer?->id,
                    'order_id' => $order->id,
                    'used_at' => now(),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | PAYMENT HANDLING
            |--------------------------------------------------------------------------
            */
            $paymentUrl = null;

            if ($data['payment_method'] === Order::PAYMENT_COD) {

                PaymentTransaction::create([
                    'order_id' => $order->id,
                    'gateway' => 'cod',
                    'status' => 'pending',
                    'amount' => $order->grand_total,
                ]);

                $this->clearSelectedCart();

                session()->forget('applied_coupon');
            }

            if ($data['payment_method'] === Order::PAYMENT_VNPAY) {

                $paymentUrl =
                    $this->paymentService
                    ->createVnpayUrl([
                        'order_code' => $order->order_code,
                        'amount' => $order->grand_total,
                    ]);

                PaymentTransaction::create([
                    'order_id' => $order->id,
                    'gateway' => 'vnpay',
                    'amount' => $order->grand_total,
                    'status' => 'pending',
                ]);
            }

            if ($data['payment_method'] === Order::PAYMENT_MOMO) {

                $paymentUrl =
                    $this->momoService
                    ->createPayment([

                        'order_code' => $order->order_code,
                        'amount' => $order->grand_total,
                    ]);

                if (!$paymentUrl) {
                    throw new \Exception(
                        'Không thể tạo thanh toán MoMo'
                    );
                }

                PaymentTransaction::create([
                    'order_id' => $order->id,
                    'gateway' => 'momo',
                    'amount' => $order->grand_total,
                    'status' => 'pending',
                ]);
            }

            return [
                'order' => $order,
                'payment_url' => $paymentUrl,
            ];
        });
    }

    // =========================
    // GENERATE ORDER CODE
    // =========================
    private function generateCode(): string
    {
        do {

            $code =
                'ORD-'
                . now()->format('ymd')
                . '-'
                . strtoupper(Str::random(6));
        } while (
            Order::where(
                'order_code',
                $code
            )->exists()
        );

        return $code;
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAR SELECTED CART
    |--------------------------------------------------------------------------
    */

    private function clearSelectedCart(): void
    {
        $cart = $this->cartService
            ->getCart();

        foreach ($cart as $key => $item) {

            if (
                $item['selected']
                ?? false
            ) {

                unset($cart[$key]);
            }
        }

        $this->cartService
            ->putCart($cart);
    }
}
