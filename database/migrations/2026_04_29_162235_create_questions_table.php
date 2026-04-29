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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique()->comment('Mã định danh câu hỏi (VD: VL12-MC-001)');
            
            // Các khóa ngoại bắt buộc
            $table->foreignId('question_type_id')->constrained('question_types');
            $table->foreignId('cognitive_level_id')->constrained('cognitive_levels');
            $table->foreignId('question_status_id')->constrained('question_statuses');
            
            // Các khóa ngoại có thể null
            $table->foreignId('question_layout_id')->nullable()->constrained('question_layouts');
            $table->foreignId('shared_context_id')->nullable()->constrained('shared_contexts')->nullOnDelete();
            
            // Nội dung & Cấu hình
            $table->longText('stem')->nullable()->comment('Phần dẫn của câu hỏi');
            $table->string('layout_ratio', 20)->nullable()->comment('Tỉ lệ chữ/hình (VD: 7:3, 50:50)');
            $table->integer('order_index')->default(0)->comment('Thứ tự hiển thị trong chùm câu hỏi');
            
            // Tracking người dùng
            $table->foreignId('created_by_id')->constrained('users');
            $table->foreignId('reviewer_id')->nullable()->constrained('users');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
