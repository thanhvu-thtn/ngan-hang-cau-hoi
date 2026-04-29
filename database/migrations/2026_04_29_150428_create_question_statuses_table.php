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
        Schema::create('question_statuses', function (Blueprint $table) {
            $table->id();
            // Mã code dùng để check logic trong code (VD: 'PENDING', 'APPROVED')
            $table->string('code', 50)->unique(); 
            // Tên hiển thị trên giao diện (VD: 'Chờ duyệt', 'Đã thẩm định')
            $table->string('name');
            // Mô tả chi tiết ý nghĩa của trạng thái
            $table->text('description')->nullable();
            // Lưu class màu sắc (Tailwind) hoặc mã màu HEX
            $table->string('color', 50)->nullable()->default('gray');
            // Thứ tự hiển thị trong quy trình (Workflow)
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_statuses');
    }
};
