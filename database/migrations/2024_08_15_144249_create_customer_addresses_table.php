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
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();

            /**
             * Customer
             */
            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            /**
             * RECEIVER
             */
            $table->string('full_name');

            $table->string('phone', 20);

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
             * ADDRESS
             */
            $table->string('address');

            /**
             * DEFAULT ADDRESS
             */
            $table->boolean('is_default')
                ->default(false);

            $table->timestamps();

            /**
             * INDEX
             */
            $table->index('customer_id');
            $table->index('province_id');
            $table->index('district_id');
            $table->index('ward_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
