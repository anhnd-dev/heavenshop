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
        Schema::create('sliders', function (Blueprint $table) {

            $table->id();

            // Nội dung
            $table->string('title', 150)->nullable();
            $table->string('subtitle', 255)->nullable();

            // Ảnh
            $table->string('image', 255);

            // Link
            $table->string('url', 255)->nullable();

            // Vị trí hiển thị
            $table->string('position', 50)->index();

            /**
             * VD:
             * home_top
             * home_middle
             * home_bottom
             * collection_banner
             * mobile_home
             */

            // Thứ tự
            $table->unsignedInteger('sort_order')->default(0);

            // Trạng thái
            $table->boolean('is_active')->default(true);

            // Lịch hiển thị
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
