<?php

namespace App\Actions\Questions;

use App\Models\Question;
use Illuminate\Support\Facades\DB;

class TrueFalseAction extends BaseQuestionAction
{
    public function execute(array $data, ?Question $question = null): Question
    {
        return DB::transaction(function () use ($data, $question) {
            // 1. Lưu các thông tin chung của câu hỏi (stem, code, type,...)
            $q = $this->saveBaseData($data, $question);
            // BẮT BUỘC THÊM: Xóa các lựa chọn cũ trước khi lưu mới
            $q->choices()->delete();
            // 3. Lưu đáp án Đúng/Sai
            // Biến $data['tf_choice'] được gửi lên từ request là boolean (true/false hoặc 1/0)
            if (isset($data['tf_choice'])) {
                $q->choices()->create([
                    'content' => $data['tf_choice'] ? 'Đúng' : 'Sai', // Chuyển boolean thành text để hiển thị nếu cần
                    'is_true' => true, // Luôn là true vì đây là dòng lưu đáp án đúng duy nhất
                    'order_index' => 1,
                ]);
            }

            return $q;
        });
    }
}
