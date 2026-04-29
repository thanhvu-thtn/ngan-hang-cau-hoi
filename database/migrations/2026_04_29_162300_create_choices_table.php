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
        Schema::create('choices', function (Blueprint $table) {
            $table->id();
            // cascadeOnDelete giúp tự động xóa tất cả choice nếu câu hỏi bị xóa
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete(); 
            
            $table->longText('content')->comment('Nội dung lựa chọn hoặc chuỗi đáp án (SA)');
            $table->boolean('is_true')->default(false)->comment('Là đáp án đúng / Phát biểu đúng');
            $table->integer('order_index')->default(0)->comment('Thứ tự A, B, C, D');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choices');
    }
};
