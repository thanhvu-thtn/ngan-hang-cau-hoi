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
        Schema::create('topic_types', function (Blueprint $table) {
            $table->id();

            // Tên kiểu chuyên đề (Cơ bản, Nâng cao, Chuyên)
            $table->string('name');

            // Mã nhận diện (CB, NC, CH) - duy nhất (unique)
            $table->string('code', 50)->unique();

            // Mô tả thêm (không bắt buộc - nullable)
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_types');
    }
};
