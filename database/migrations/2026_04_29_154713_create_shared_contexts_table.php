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
        Schema::create('shared_contexts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique(); // Mã định danh để tìm kiếm nhanh
            $table->longText('content');         // Nội dung phần dẫn (chứa văn bản, công thức, HTML)
            $table->text('description')->nullable(); // Ghi chú (VD: Trích nguồn từ sách nào)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shared_contexts');
    }
};
