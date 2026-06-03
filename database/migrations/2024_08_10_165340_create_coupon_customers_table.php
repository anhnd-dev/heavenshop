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
        Schema::create('coupon_customers', function (Blueprint $table) {
            $table->id();

            /**
             * Coupon
             */
            $table->foreignId('coupon_id')
                ->constrained('coupons')
                ->cascadeOnDelete();

            /**
             * Customer
             */
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            /**
             * Order đã dùng voucher
             */
            $table->foreignId('order_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /**
             * Thời gian sử dụng
             */
            $table->timestamp('used_at')
                ->nullable();

            $table->timestamps();

            /**
             * 1 customer chỉ giữ 1 voucher duy nhất
             * (tránh duplicate claim voucher)
             */
            $table->unique(['coupon_id', 'customer_id']);

            /**
             * INDEX tối ưu query ví voucher
             */
            $table->index(['customer_id', 'used_at']);
            $table->index(['coupon_id', 'used_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_customers');
    }
};
