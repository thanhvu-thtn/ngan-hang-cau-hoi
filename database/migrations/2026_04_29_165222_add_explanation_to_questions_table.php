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
        Schema::table('questions', function (Blueprint $table) {
            // Thêm cột explanation vào sau cột stem để database trông gọn gàng
            $table->longText('explanation')
                ->nullable()
                ->after('stem')
                ->comment('Lời giải chi tiết / Hướng dẫn chấm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Xóa cột nếu rollback
            $table->dropColumn('explanation');
        });
    }
};
