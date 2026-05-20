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
        Schema::create('categories', function (Blueprint $table) {

            $table->id();

            $table->string('name', 100);

            $table->string('slug', 120);

            $table->string('image', 255)->nullable();

            $table->text('description')->nullable();

            $table->enum('type', ['product', 'blog']);

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->tinyInteger('level')->default(0);

            $table->boolean('is_active')->default(true);

            $table->softDeletes();

            $table->timestamps();

            $table->index('type');
            $table->index('parent_id');
            $table->index('is_active');

            $table->unique(['parent_id', 'slug', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
