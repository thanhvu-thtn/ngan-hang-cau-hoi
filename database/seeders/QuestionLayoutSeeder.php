<?php
namespace Database\Seeders;

use App\Models\QuestionLayout;
use Illuminate\Database\Seeder;

class QuestionLayoutSeeder extends Seeder
{
    public function run(): void
    {
        $layouts = [
            [
                'code' => '4x1',
                'name' => '4 Hàng x 1 Cột',
                'description' => 'Các đáp án được xếp thành 4 dòng từ trên xuống dưới. Dùng cho các câu hỏi có đáp án dài (nhiều chữ, chứa công thức lớn).',
                'order_index' => 1,
            ],
            [
                'code' => '2x2',
                'name' => '2 Hàng x 2 Cột',
                'description' => 'Các đáp án được xếp thành 2 dòng, mỗi dòng 2 đáp án (A B trên, C D dưới). Dùng cho đáp án có độ dài trung bình.',
                'order_index' => 2,
            ],
            [
                'code' => '1x4',
                'name' => '1 Hàng x 4 Cột',
                'description' => 'Cả 4 đáp án (A, B, C, D) nằm trên cùng 1 dòng ngang. Dùng cho đáp án rất ngắn (chỉ là 1 từ, 1 con số).',
                'order_index' => 3,
            ],
        ];

        foreach ($layouts as $layout) {
            QuestionLayout::updateOrCreate(['code' => $layout['code']], $layout);
        }
    }
}