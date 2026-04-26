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
        Schema::create('question_types', function (Blueprint $table) {
            $table->id();

            // Tên kiểu câu hỏi (VD: Trắc nghiệm nhiều lựa chọn, Trả lời ngắn...)
            $table->string('name');

            // Mã nhận diện (VD: MC, TF, SA, ES)
            $table->string('code', 20)->unique();

            // Số lượng phương án lựa chọn (VD: 4, 1, 0)
            $table->integer('num_choices')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_types');
    }
};
