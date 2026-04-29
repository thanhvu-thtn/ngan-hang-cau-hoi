<?php

namespace App\Actions\Questions;

use App\Models\Question;
use Illuminate\Support\Facades\DB;

class EssayAction extends BaseQuestionAction
{
    public function execute(array $data, ?Question $question = null): Question
    {
        return DB::transaction(function () use ($data, $question) {
            $q = $this->saveBaseData($data, $question);
            
            // Xóa choices nếu vô tình trước đó là dạng câu hỏi khác bị đổi sang Tự luận
            $q->choices()->delete();

            return $q;
        });
    }
}