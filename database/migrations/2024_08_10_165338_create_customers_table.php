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
        Schema::create('customers', function (Blueprint $table) {

            $table->id();

            // =========================
            // BASIC
            // =========================
            $table->string('name', 100);

            $table->string('email', 100)
                ->nullable()
                ->unique();

            $table->string('phone', 20)
                ->unique();

            $table->string('password');

            // =========================
            // PROFILE
            // =========================
            $table->string('avatar')
                ->nullable();

            $table->enum('gender', [
                'male',
                'female'
            ])->nullable();

            // =========================
            // STATUS
            // =========================
            $table->boolean('is_active')
                ->default(true);

            $table->rememberToken();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
