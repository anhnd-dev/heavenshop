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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100);
            $table->string('user_name', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');

            $table->string('avatar', 255)->nullable();
            $table->string('phone', 20)->nullable()->unique();
            $table->string('address', 255)->nullable();

            $table->tinyInteger('gender')->default(0);

            $table->boolean('status')->default(true);

            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();

            $table->timestamps();
            $table->rememberToken();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
