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
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            $table->string('name', 100);
            $table->string('slug', 100)->unique();

            $table->text('description')->nullable();
            $table->longText('content')->nullable();

            $table->json('tags')->nullable();

            $table->string('image')->nullable();

            $table->boolean('is_featured')->default(false);

            $table->boolean('is_active')->default(true);

            $table->unsignedBigInteger('view_count')
                ->default(0);

            $table->unsignedBigInteger('sold_count')
                ->default(0);

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->nullOnDelete();

            $table->index('is_active');
            $table->index('is_featured');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('color_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('size_id')
                ->constrained()
                ->cascadeOnDelete();

            // =========================
            // BUSINESS DATA
            // =========================

            $table->string('sku', 100)->nullable()->index();


            $table->decimal('price', 12, 2);

            $table->decimal('sale_price', 12, 2)
                ->nullable();

            $table->unsignedInteger('stock')
                ->default(0);

            $table->unsignedInteger('sold_count')
                ->default(0);

            $table->string('image')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            // =========================
            // UNIQUE RULE (QUAN TRỌNG NHẤT)
            // =========================
            $table->unique(
                ['product_id', 'color_id', 'size_id'],
                'product_variant_unique'
            );

            // =========================
            // SYSTEM
            // =========================
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('product_galleries', function (Blueprint $table) {

            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('color_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // image/video
            $table->string('file');

            // image | video
            $table->enum('type', [
                'image',
                'video',
            ]);

            // thumbnail video
            $table->string('thumbnail')
                ->nullable();

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_variants');
    }
};
