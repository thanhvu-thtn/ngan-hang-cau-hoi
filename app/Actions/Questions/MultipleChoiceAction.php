<?php

namespace App\Actions\Questions;

use App\Models\Question;
use Illuminate\Support\Facades\DB;

class MultipleChoiceAction extends BaseQuestionAction
{
    public function execute(array $data, ?Question $question = null): Question
    {
        return DB::transaction(function () use ($data, $question) {
            // 1. Lưu phần chung (Cha làm)
            $q = $this->saveBaseData($data, $question);

            // 2. Xử lý lựa chọn (Con làm)
            if (isset($data['choices']) && is_array($data['choices'])) {
                // Xóa các lựa chọn cũ (nếu là update) để nạp lại cái mới cho an toàn
                $q->choices()->delete();

                // Tạo danh sách lựa chọn mới
                // Kỳ vọng $data['choices'] là mảng gồm 4 phần tử: 
                // [['content' => 'A...', 'is_true' => true, 'order_index' => 1], ...]
                foreach ($data['choices'] as $choiceData) {
                    // QUÉT ẢNH TRONG TỪNG ĐÁP ÁN
                    $processedContent = $this->imageService->processHtmlContent($choiceData['content']);
                    $q->choices()->create([
                        'content'     => $processedContent,
                        'is_true'     => $choiceData['is_true'] ?? false,
                        'order_index' => $choiceData['order_index'] ?? 0,
                    ]);
                }
            }

            return $q;
        });
    }
}