<?php

namespace App\Actions\Questions;

use App\Models\Question;
use Illuminate\Support\Facades\DB;

class TrueFalseAction extends BaseQuestionAction
{
    public function execute(array $data, ?Question $question = null): Question
    {
        return DB::transaction(function () use ($data, $question) {
            $q = $this->saveBaseData($data, $question);

            if (isset($data['choices']) && is_array($data['choices'])) {
                $q->choices()->delete();

                // Lấy phần tử đầu tiên (duy nhất) của mảng choices
                $answer = $data['choices'][0];

                $q->choices()->create([
                    // Nếu là true thì lưu chữ 'Đúng', false lưu chữ 'Sai' (hoặc True/False)
                    'content'     => $answer['is_true'] ? 'Đúng' : 'Sai', 
                    'is_true'     => $answer['is_true'],
                    'order_index' => 1,
                ]);
            }

            return $q;
        });
    }
}