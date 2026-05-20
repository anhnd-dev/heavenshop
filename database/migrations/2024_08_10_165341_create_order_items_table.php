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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            /**
             * ORDER
             */
            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            /**
             * PRODUCT
             */
            $table->foreignId('product_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /**
             * PRODUCT VARIANT
             * size + color
             */
            $table->foreignId('product_variant_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /**
             * PRODUCT SNAPSHOT
             */

            $table->string('product_name');

            $table->string('product_slug')
                ->nullable();

            $table->string('product_sku')
                ->nullable();

            $table->string('product_image')
                ->nullable();

            /**
             * VARIANT SNAPSHOT
             */

            $table->foreignId('color_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('size_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('color_name')
                ->nullable();

            $table->string('size_name')
                ->nullable();


            /**
             * PRICE
             */

            // Giá gốc
            $table->decimal('price', 12, 2);

            // Giá sau sale
            $table->decimal('sale_price', 12, 2)
                ->nullable();

            /**
             * QUANTITY
             */
            $table->unsignedInteger('quantity');

            /**
             * TOTAL
             */
            $table->decimal('total', 12, 2);

            $table->timestamps();

            /**
             * INDEX
             */
            $table->index('order_id');
            $table->index('product_id');
            $table->index('product_variant_id');
            $table->index('color_id');
            $table->index('size_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
