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
        Schema::create('settings', function (Blueprint $table) {
        $table->id();
        // Cột key: Lưu tên biến (ví dụ: 'school_name'). Dùng unique() để không bị trùng lặp key.
        $table->string('key')->unique(); 
        
        // Cột value: Lưu giá trị. Nên dùng kiểu text() phòng trường hợp giá trị dài (như đoạn mô tả). 
        // Cho phép nullable() nếu chưa có giá trị.
        $table->text('value')->nullable(); 
        
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
