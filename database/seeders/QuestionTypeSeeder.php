<?php

namespace Database\Seeders;

use App\Models\QuestionType;
use Illuminate\Database\Seeder;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Nhiều lựa chọn', 'code' => 'MC', 'num_choices' => 4],
            ['name' => 'Đúng / Sai', 'code' => 'TF', 'num_choices' => 1],
            ['name' => 'Trả lời ngắn', 'code' => 'SA', 'num_choices' => 1],
            ['name' => 'Tự luận', 'code' => 'ES', 'num_choices' => 0],
        ];

        foreach ($types as $type) {
            QuestionType::updateOrCreate(['code' => $type['code']], $type);
        }
    }
}
