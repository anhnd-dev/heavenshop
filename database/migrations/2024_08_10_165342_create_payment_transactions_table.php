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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | ORDER
            |--------------------------------------------------------------------------
            */

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | GATEWAY
            |--------------------------------------------------------------------------
            */

            // vnpay | momo | cod | stripe | paypal
            $table->string('gateway', 50);

            /*
            |--------------------------------------------------------------------------
            | TRANSACTION
            |--------------------------------------------------------------------------
            */

            $table->string('transaction_id', 100)
                ->nullable()
                ->unique();

            $table->string('transaction_code')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            // pending | success | failed | refunded
            $table->string('status', 50)
                ->default('pending');


            /*
            |--------------------------------------------------------------------------
            | MONEY
            |--------------------------------------------------------------------------
            */
            $table->decimal('amount', 12, 2);

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            $table->json('response_data')
                ->nullable();

            $table->text('failure_reason')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | TIME
            |--------------------------------------------------------------------------
            */
            $table->timestamp('paid_at')
                ->nullable();

            $table->timestamp('refunded_at')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            $table->index('order_id');

            $table->index('gateway');

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
