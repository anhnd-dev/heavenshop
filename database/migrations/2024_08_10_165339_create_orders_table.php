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

            /*
            |--------------------------------------------------------------------------
            | ORDER INFO
            |--------------------------------------------------------------------------
            */

            $table->string('order_code', 50)
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | RELATIONS
            |--------------------------------------------------------------------------
            */

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            $table->foreignId('coupon_id')
                ->nullable()
                ->constrained('coupons')
                ->nullOnDelete();

            $table->foreignId('customer_address_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | SHIPPING SNAPSHOT
            |--------------------------------------------------------------------------
            | Không dùng FK location trong orders
            | vì order là dữ liệu lịch sử bất biến
            |--------------------------------------------------------------------------
            */

            $table->string('shipping_name', 100);

            $table->string('shipping_phone', 20);

            $table->string('shipping_email')
                ->nullable();

            $table->string('shipping_province', 100);

            $table->string('shipping_district', 100);

            $table->string('shipping_ward', 100);

            $table->string('shipping_address', 255);

            /*
            |--------------------------------------------------------------------------
            | NOTE
            |--------------------------------------------------------------------------
            */
            $table->text('note')->nullable();

            /*
            |--------------------------------------------------------------------------
            | SHIPPING
            |--------------------------------------------------------------------------
            */

            $table->string('shipping_method', 50)
                ->default('standard');

            $table->decimal('shipping_fee', 12, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | PAYMENT
            |--------------------------------------------------------------------------
            */

            // cod | vnpay | momo | stripe
            $table->string('payment_method', 50);

            // pending | paid | failed | refunded
            $table->string('payment_status', 50)->default('pending');

            /*
            |--------------------------------------------------------------------------
            | ORDER STATUS
            |--------------------------------------------------------------------------
            */

            // pending | confirmed | shipping | delivered | cancelled | returned
            $table->string('order_status', 50)->default('pending');

            /*
            |--------------------------------------------------------------------------
            | MONEY
            |--------------------------------------------------------------------------
            */

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | TIMELINE
            |--------------------------------------------------------------------------
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

            $table->timestamp('returned_at')
                ->nullable();

            $table->timestamp('refunded_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | PAYMENT DEADLINE
            |--------------------------------------------------------------------------
            */

            $table->timestamp('payment_deadline')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | CANCEL
            |--------------------------------------------------------------------------
            */

            $table->text('cancel_reason')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | SYSTEM
            |--------------------------------------------------------------------------
            */

            $table->softDeletes();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */
            $table->index([
                'customer_id',
                'created_at'
            ]);

            $table->index('payment_status');

            $table->index('order_status');

            $table->index('payment_deadline');
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
