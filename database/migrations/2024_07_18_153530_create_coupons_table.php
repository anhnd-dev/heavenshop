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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            /**
             * CODE
             * Ví dụ:
             * SUMMER2026
             * SALE50K
             */
            $table->string('code', 50)->unique();

            /**
             * discount_type
             * - percentage => giảm theo %
             * - fixed => giảm số tiền cố định
             */
            $table->string('discount_type', 20);

            /**
             * Giá trị giảm
             *
             * percentage:
             * 10 => giảm 10%
             *
             * fixed:
             * 100000 => giảm 100.000 VNĐ
             */
            $table->decimal('discount_value', 12, 2);

            /**
             * Đơn tối thiểu để áp mã
             * Ví dụ:
             * 500000 => đơn từ 500k
             */
            $table->decimal('min_order_amount', 12, 2)
                ->nullable();

            /**
             * Giảm tối đa
             *
             * Dùng cho coupon %
             * Ví dụ:
             * giảm 20% tối đa 100k
             */
            $table->decimal('max_discount_amount', 12, 2)
                ->nullable();

            /**
             * Tổng số lượt có thể dùng
             */
            $table->unsignedInteger('quantity')
                ->default(0);

            /**
             * Đã dùng bao nhiêu lần
             */
            $table->unsignedInteger('used_count')
                ->default(0);

            /**
             * Không giới hạn số lượng
             */
            $table->boolean('is_unlimited')
                ->default(false);

            /**
             * Mô tả coupon
             */
            $table->text('description')
                ->nullable();

            /**
             * Thời gian bắt đầu
             */
            $table->timestamp('start_date')
                ->nullable();

            /**
             * Thời gian kết thúc
             */
            $table->timestamp('end_date')
                ->nullable();

            /**
             * Trạng thái hoạt động
             */
            $table->boolean('is_active')
                ->default(true);

            $table->softDeletes();
            $table->timestamps();

            // INDEX
            $table->index('code');
            $table->index('discount_type');
            $table->index('is_active');
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
