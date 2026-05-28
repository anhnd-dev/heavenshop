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

            /*
            |--------------------------------------------------------------------------
            | RELATIONS
            |--------------------------------------------------------------------------
            */
            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('product_variant_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | PRODUCT SNAPSHOT
            |--------------------------------------------------------------------------
            */
            $table->string('product_name');

            $table->string('product_slug')
                ->nullable();

            $table->string('product_sku')
                ->nullable();

            $table->string('product_image')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | VARIANT SNAPSHOT
            |--------------------------------------------------------------------------
            */

            $table->string('variant_name')
                ->nullable();

            $table->string('color_name')
                ->nullable();

            $table->string('size_name')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | PRICE
            |--------------------------------------------------------------------------
            */

            $table->decimal('original_price', 12, 2);

            $table->decimal('final_price', 12, 2);

            /*
            |--------------------------------------------------------------------------
            | QUANTITY
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('quantity');

            /*
            |--------------------------------------------------------------------------
            | TOTAL
            |--------------------------------------------------------------------------
            */

            $table->decimal('total', 12, 2);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            $table->index('order_id');

            $table->index('product_id');

            $table->index('product_variant_id');
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
