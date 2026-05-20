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
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();

            /**
             * LOCATION
             */

            // Áp dụng toàn tỉnh
            $table->foreignId('province_id')
                ->nullable()
                ->constrained('provinces')
                ->cascadeOnDelete();

            // Áp dụng riêng quận/huyện
            $table->foreignId('district_id')
                ->nullable()
                ->constrained('districts')
                ->cascadeOnDelete();

            // Áp dụng riêng phường/xã
            $table->foreignId('ward_id')
                ->nullable()
                ->constrained('wards')
                ->cascadeOnDelete();

            /**
             * SHIPPING METHOD
             *
             * standard
             * express
             */
            $table->string('shipping_method', 50)
                ->default('standard');

            /**
             * SHIPPING PRICE
             */
            $table->decimal('price', 12, 2);

            /**
             * FREE SHIP
             *
             * Freeship nếu subtotal >= value
             */
            $table->decimal('free_shipping_from', 12, 2)
                ->nullable();

            /**
             * WEIGHT
             *
             * gram
             */
            $table->unsignedInteger('min_weight')
                ->default(0);

            $table->unsignedInteger('max_weight')
                ->nullable();

            /**
             * STATUS
             */
            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            /**
             * INDEX
             */
            $table->index('province_id');

            $table->index('district_id');

            $table->index('ward_id');

            $table->index('shipping_method');

            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
