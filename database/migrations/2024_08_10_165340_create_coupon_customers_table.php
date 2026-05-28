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
             * Coupon được sử dụng
             */
            $table->foreignId('coupon_id')
                ->constrained('coupons')
                ->cascadeOnDelete();

            /**
             * Customer sử dụng coupon
             */
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            /**
             * Order đã dùng coupon
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

            $table->unique(['coupon_id', 'customer_id', 'order_id']);

            $table->index(['customer_id', 'coupon_id']);
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
