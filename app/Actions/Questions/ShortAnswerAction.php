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

            // Xóa đáp án cũ
            $q->choices()->delete();

            // Lưu đáp án trả lời ngắn (chỉ 1 dòng)
            if (isset($data['correct_answer'])) {
                $q->choices()->create([
                    'content'     => $data['correct_answer'],
                    'is_true'     => true,
                    'order_index' => 1,
                ]);
            }

            return $q;
        });
    }
}