<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            /**
             * ORDER CODE
             * Ví dụ:
             * ORD202605160001
             */
            $table->string('order_code', 50)
                ->unique();

            /**
             * CUSTOMER
             */
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            /**
             * COUPON
             */
            $table->foreignId('coupon_id')
                ->nullable()
                ->constrained('coupons')
                ->nullOnDelete();

            /**
             * SHIPPING LOCATION IDS
             */
            $table->foreignId('province_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('district_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('ward_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /**
             * SHIPPING INFO SNAPSHOT
             */
            $table->string('shipping_name');

            $table->string('shipping_phone', 20);

            $table->string('shipping_email')
                ->nullable();

            $table->string('shipping_province');

            $table->string('shipping_district');

            $table->string('shipping_ward');

            $table->string('shipping_address');

            /**
             * SHIPPING
             */
            $table->string('shipping_method', 50)
                ->default('standard');

            $table->decimal('shipping_fee', 12, 2)
                ->default(0);

            /**
             * NOTE
             */
            $table->text('note')
                ->nullable();

            /**
             * PAYMENT
             *
             * cod
             * vnpay
             * momo
             * stripe
             */
            $table->string('payment_method', 50);

            /**
             * pending
             * paid
             * failed
             * refunded
             */
            $table->string('payment_status', 50)
                ->default('pending');

            /**
             * pending
             * confirmed
             * shipping
             * delivered
             * cancelled
             * returned
             */
            $table->string('order_status', 50)
                ->default('pending');

            /**
             * MONEY
             */

            // Tổng tiền sản phẩm
            $table->decimal('subtotal', 12, 2)
                ->default(0);

            // Giảm giá coupon
            $table->decimal('discount_amount', 12, 2)
                ->default(0);

            // Tổng cuối cùng
            $table->decimal('grand_total', 12, 2)
                ->default(0);

            /**
             * TIME
             */

            $table->timestamp('paid_at')
                ->nullable();

            $table->timestamp('confirmed_at')
                ->nullable();

            $table->timestamp('shipped_at')
                ->nullable();

            $table->timestamp('delivered_at')
                ->nullable();

            $table->timestamp('cancelled_at')
                ->nullable();

            /**
             * CANCEL REASON
             */
            $table->text('cancel_reason')
                ->nullable();

            $table->softDeletes();
            $table->timestamps();

            /**
             * INDEX
             */
            $table->index('order_code');
            $table->index('customer_id');
            $table->index('coupon_id');
            $table->index('province_id');
            $table->index('district_id');
            $table->index('ward_id');
            $table->index('payment_status');
            $table->index('order_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
