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
        Schema::create('cognitive_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên mức độ: Nhận biết, Thông hiểu...');
            $table->string('code')->unique()->comment('Mã định danh: NB, TH, VD, VDC');
            $table->text('description')->nullable()->comment('Mô tả chi tiết về mức độ');
            $table->integer('order_index')->default(0)->comment('Thứ tự sắp xếp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cognitive_levels');
    }
};
