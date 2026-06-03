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
             * Mã voucher
             */
            $table->string('code', 50)->unique();

            /**
             * Loại giảm giá
             * - percentage => giảm theo %
             * - fixed => giảm số tiền cố định
             */
            $table->string('discount_type', 20);

            /**
             * Giá trị giảm
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
             * Giảm tối đa (%)
             * Ví dụ:
             * giảm 20% tối đa 100k
             */
            $table->decimal('max_discount_amount', 12, 2)
                ->nullable();

            /**
             * Tổng lượt dùng toàn hệ thống
             */
            $table->unsignedInteger('quantity')
                ->default(0);

            /**
             * Đã dùng toàn hệ thống
             */
            $table->unsignedInteger('used_count')
                ->default(0);

            /**
             * Không giới hạn số lượng
             */
            $table->boolean('is_unlimited')
                ->default(false);

            /**
             * Giới hạn mỗi user được dùng bao nhiêu lần
             */
            $table->unsignedInteger('limit_per_customer')->default(1);

            /**
             * Mô tả coupon
             */
            $table->text('description')
                ->nullable();

            /**
             * Thời gian hiệu lực
             */
            $table->timestamp('start_date')
                ->nullable();

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
