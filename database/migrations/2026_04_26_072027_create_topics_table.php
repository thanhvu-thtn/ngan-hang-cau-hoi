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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            
            // Khóa ngoại liên kết tới bảng grades (Khối lớp)
            $table->foreignId('grade_id')->constrained()->onDelete('cascade');
            
            // Khóa ngoại liên kết tới bảng topic_types (Kiểu chuyên đề)
            $table->foreignId('topic_type_id')->constrained()->onDelete('cascade');
            
            $table->string('name'); // Tên chuyên đề
            
            // Mã nhận dạng: bắt buộc (không null), duy nhất (unique) và tự động được đánh index
            $table->string('code')->unique(); 
            
            $table->timestamps(); // created_at và updated_at

            // Tối ưu: Đánh index cho các trường hay dùng để lọc dữ liệu
            $table->index(['grade_id', 'topic_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
