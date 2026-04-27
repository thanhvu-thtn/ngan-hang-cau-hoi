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
        Schema::create('objectives', function (Blueprint $blueprint) {
            $blueprint->id();
            // Khóa ngoại liên kết với bảng topic_contents
            // onDelete('cascade') giúp tự động xóa các yêu cầu khi nội dung chuyên đề bị xóa
            $blueprint->foreignId('topic_content_id')->constrained('topic_contents')->onDelete('cascade');
            
            $blueprint->string('code')->comment('Mã nhận diện yêu cầu');
            $blueprint->text('description')->comment('Mô tả chi tiết yêu cầu, có thể chứa KaTeX');
            
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objectives');
    }
};
