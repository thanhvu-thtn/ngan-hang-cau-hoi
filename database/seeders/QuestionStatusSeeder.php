<?php

namespace Database\Seeders;

use App\Models\QuestionStatus;
use Illuminate\Database\Seeder;

class QuestionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'code' => 'PENDING',
                'name' => 'Mới tải lên',
                'description' => 'Câu hỏi mới được đưa lên hệ thống, đang chờ tổ chuyên môn thẩm định.',
                'color' => 'bg-yellow-100 text-yellow-800', // Badge màu vàng
                'order_index' => 1,
            ],
            [
                'code' => 'APPROVED',
                'name' => 'Đã thẩm định',
                'description' => 'Câu hỏi đạt yêu cầu lý thuyết, có thể xuất bản ra bài tập hoặc đề thi.',
                'color' => 'bg-green-100 text-green-800', // Badge màu xanh lá
                'order_index' => 2,
            ],
            [
                'code' => 'CTT_EVALUATED',
                'name' => 'Đã đánh giá CTT',
                'description' => 'Câu hỏi đã được thi và có dữ liệu phân tích theo Lý thuyết trắc nghiệm cổ điển (CTT).',
                'color' => 'bg-blue-100 text-blue-800', // Badge màu xanh dương
                'order_index' => 3,
            ],
            [
                'code' => 'IRT_EVALUATED',
                'name' => 'Đã đánh giá IRT',
                'description' => 'Câu hỏi đã được chuẩn hóa theo Lý thuyết ứng đáp câu hỏi (IRT), sẵn sàng cho ngân hàng đề thi thích ứng.',
                'color' => 'bg-purple-100 text-purple-800', // Badge màu tím
                'order_index' => 4,
            ],
            [
                'code' => 'REJECTED',
                'name' => 'Cần sửa / Loại bỏ',
                'description' => 'Câu hỏi không đáp ứng được yêu cầu về nội dung hoặc có chỉ số thống kê quá kém.',
                'color' => 'bg-red-100 text-red-800', // Badge màu đỏ
                'order_index' => 5,
            ],
        ];

        // Dùng updateOrCreate để tránh lỗi trùng lặp dữ liệu (duplicate) nếu bạn chạy seeder nhiều lần
        foreach ($statuses as $status) {
            QuestionStatus::updateOrCreate(
                ['code' => $status['code']], // Điều kiện tìm kiếm (duy nhất)
                $status                      // Dữ liệu sẽ cập nhật hoặc tạo mới
            );
        }
    }
}