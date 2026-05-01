<?php

namespace App\Actions\Questions;

use App\Models\Question;
use Illuminate\Support\Facades\DB;

class ShortAnswerAction extends BaseQuestionAction
{
    public function execute(array $data, ?Question $question = null): Question
    {
        return DB::transaction(function () use ($data, $question) {
            $q = $this->saveBaseData($data, $question);
            // BẮT BUỘC THÊM: Xóa các lựa chọn cũ trước khi lưu mới
            $q->choices()->delete();
            // Lưu đáp án trả lời ngắn (chỉ 1 dòng)
            if (isset($data['sa_choice'])) {
                $q->choices()->create([
                    'content'     => $data['sa_choice'], // Lưu trực tiếp nội dung đáp án SA vào trường content
                    'is_true'     => true,
                    'order_index' => 1,
                ]);
            }

            return $q;
        });
    }
}