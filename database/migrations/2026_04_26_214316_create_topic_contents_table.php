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
        Schema::create('topic_contents', function (Blueprint $table) {
            $table->id();

            // Khóa ngoại liên kết với bảng topics
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();

            $table->string('code', 50)->unique()->comment('Mã định danh nội dung');
            $table->string('name')->comment('Tên nội dung');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_contents');
    }
};
