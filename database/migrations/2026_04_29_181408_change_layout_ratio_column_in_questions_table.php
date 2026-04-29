<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Đổi từ kiểu String sang kiểu Float/Decimal
            // decimal('tên_cột', tổng_số_chữ_số, số_chữ_số_thập_phân)
            $table->decimal('layout_ratio', 3, 2)
                  ->nullable()
                  ->comment('Tỉ lệ phần chữ (từ 0.00 đến 1.00)')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Trả lại kiểu string 20 ký tự nếu Rollback
            $table->string('layout_ratio', 20)->nullable()->change();
        });
    }
};
