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
        Schema::create('question_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique()->comment('Mã bố cục (VD: 1x4, 2x2, 4x1)');
            $table->string('name')->comment('Tên hiển thị (VD: 1 Hàng - 4 Cột)');
            $table->text('description')->nullable()->comment('Mô tả khi nào nên dùng');
            $table->integer('order_index')->default(0)->comment('Thứ tự hiển thị');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_layouts');
    }
};
