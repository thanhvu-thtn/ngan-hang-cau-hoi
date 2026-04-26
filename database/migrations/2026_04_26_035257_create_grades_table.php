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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            
            // Tên khối lớp (VD: Khối 10, Khối 11)
            $table->string('name'); 
            
            // Mã nhận diện thống nhất là 'code' (VD: K10, K11). 
            // Dùng unique() để đảm bảo không có 2 khối trùng mã.
            $table->string('code', 50)->unique(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
