<?php

namespace App\Services\Frontend;

use App\Models\Coupon;

class CouponService
{
    public function availableCoupons(
        float $subtotal,
        int $customerId = null
    ) {

        return Coupon::query()

            ->where('is_active', true)

            ->where(function ($q) {

                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })

            ->where(function ($q) {

                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })

            ->where(function ($q) {

                $q->where('is_unlimited', true)

                    ->orWhereColumn(
                        'used_count',
                        '<',
                        'quantity'
                    );
            })

            ->where(function ($q) use ($subtotal) {

                $q->whereNull('min_order_amount')

                    ->orWhere(
                        'min_order_amount',
                        '<=',
                        $subtotal
                    );
            })

            ->get()

            ->filter(function ($coupon) use ($customerId) {

                if (!$customerId) {
                    return true;
                }

                return !$coupon->customers()
                    ->where('customer_id', $customerId)
                    ->exists();
            });
    }

    public function calculateDiscount(
        Coupon $coupon,
        float $subtotal
    ): float {

        if (
            $coupon->discount_type ===
            Coupon::TYPE_PERCENTAGE
        ) {

            $discount =
                ($subtotal * $coupon->discount_value) / 100;

            if ($coupon->max_discount_amount) {

                $discount = min(
                    $discount,
                    $coupon->max_discount_amount
                );
            }

            return $discount;
        }

        return min(
            $coupon->discount_value,
            $subtotal
        );
    }

    // =========================
    // VALIDATE COUPON
    // =========================
    public function validateCoupon(
        string $code,
        float $subtotal,
        ?int $customerId = null
    ): array {

        $coupon = Coupon::query()
            ->where('code', strtoupper($code))
            ->where('is_active', true)
            ->first();

        // Không tồn tại
        if (!$coupon) {

            return [
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại',
            ];
        }

        // Chưa tới thời gian
        if (
            $coupon->start_date &&
            now()->lt($coupon->start_date)
        ) {

            return [
                'success' => false,
                'message' => 'Mã giảm giá chưa bắt đầu',
            ];
        }

        // Hết hạn
        if (
            $coupon->end_date &&
            now()->gt($coupon->end_date)
        ) {

            return [
                'success' => false,
                'message' => 'Mã giảm giá đã hết hạn',
            ];
        }

        // Hết lượt dùng
        if (
            !$coupon->is_unlimited &&
            $coupon->used_count >= $coupon->quantity
        ) {

            return [
                'success' => false,
                'message' => 'Mã giảm giá đã hết lượt sử dụng',
            ];
        }

        // Kiểm tra đơn tối thiểu
        if (
            $coupon->min_order_amount &&
            $subtotal < $coupon->min_order_amount
        ) {

            return [
                'success' => false,
                'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' .
                    number_format(
                        $coupon->min_order_amount,
                        0,
                        ',',
                        '.'
                    ) . 'đ',
            ];
        }

        // Kiểm tra user đã dùng chưa
        if ($customerId) {

            $used = $coupon->customers()
                ->where('customer_id', $customerId)
                ->exists();

            if ($used) {

                return [
                    'success' => false,
                    'message' => 'Bạn đã sử dụng mã này rồi',
                ];
            }
        }

        // =========================
        // TÍNH DISCOUNT
        // =========================
        $discount = 0;

        // %
        if (
            $coupon->discount_type ===
            Coupon::TYPE_PERCENTAGE
        ) {

            $discount = (
                $subtotal *
                $coupon->discount_value
            ) / 100;

            // max discount
            if (
                $coupon->max_discount_amount &&
                $discount > $coupon->max_discount_amount
            ) {

                $discount =
                    $coupon->max_discount_amount;
            }
        }

        // fixed
        else {

            $discount = $coupon->discount_value;
        }

        // Không vượt subtotal
        $discount = min($discount, $subtotal);

        return [
            'success' => true,
            'coupon' => $coupon,
            'discount' => $discount,
            'message' => 'Áp mã thành công',
        ];
    }
}
