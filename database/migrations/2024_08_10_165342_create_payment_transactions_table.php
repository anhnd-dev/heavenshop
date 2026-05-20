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

            /**
             * ORDER
             */
            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            /**
             * PAYMENT GATEWAY
             *
             * cod
             * vnpay
             * momo
             * stripe
             * paypal
             */
            $table->string('gateway', 50);

            /**
             * Gateway transaction ID
             */
            $table->string('transaction_id')
                ->nullable();

            /**
             * Gateway reference code
             */
            $table->string('transaction_code')
                ->nullable();

            /**
             * PAYMENT STATUS
             *
             * pending
             * success
             * failed
             * refunded
             */
            $table->string('status', 50)
                ->default('pending');

            /**
             * MONEY
             */
            $table->decimal('amount', 12, 2);

            /**
             * RAW RESPONSE
             * JSON response từ gateway
             */
            $table->longText('response_data')
                ->nullable();

            /**
             * FAILURE MESSAGE
             */
            $table->text('failure_reason')
                ->nullable();

            /**
             * TIME
             */
            $table->timestamp('paid_at')
                ->nullable();

            $table->timestamps();

            /**
             * INDEX
             */
            $table->index('order_id');
            $table->index('gateway');
            $table->index('transaction_id');
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
