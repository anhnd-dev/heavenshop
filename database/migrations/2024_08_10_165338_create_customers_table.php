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
            $table->string('full_name', 50);
            $table->string('user_name', 50)->unique();
            $table->string('email', 100);
            $table->string('password', 100);
            $table->string('avatar', 100)->nullable();
            $table->char('phone', 20)->unique();
            $table->text('address');
            $table->enum('gender', ['male','female'])->nullable();
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
