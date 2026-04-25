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
        Schema::create('tests', function (Blueprint $table) {
        $table->id();
        
        // Dùng longText thay vì string/text thông thường vì 
        // TinyMCE mã hóa ảnh Base64 sẽ sinh ra chuỗi ký tự rất dài.
        $table->longText('content'); 
        
        $table->timestamps(); // Tự động tạo 2 cột created_at và updated_at
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
