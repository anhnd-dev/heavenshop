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
            $table->foreignId('province_id')
                ->constrained('provinces')
                ->restrictOnDelete();

            $table->foreignId('district_id')
                ->constrained('districts')
                ->restrictOnDelete();

            $table->foreignId('ward_id')
                ->constrained('wards')
                ->restrictOnDelete();

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
            $table->index([
                'customer_id',
                'is_default'
            ]);
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
