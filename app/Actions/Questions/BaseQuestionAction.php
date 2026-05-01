<?php

namespace App\Actions\Questions;

use App\Models\Question;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;

abstract class BaseQuestionAction
{
    protected $imageService;
    // Inject ImageService vào để các class con (MC, SA...) đều dùng được
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    /**
     * Hàm lưu các thuộc tính chung của câu hỏi
     */
    protected function saveBaseData(array $data, ?Question $question = null): Question
    {
        // Nếu truyền vào $question thì là Update, ngược lại là Create
        $question = $question ?? new Question();
        // QUÉT ẢNH TRONG STEM VÀ EXPLANATION
        if (isset($data['stem'])) {
            $data['stem'] = $this->imageService->processHtmlContent($data['stem']);
        }
        if (isset($data['explanation'])) {
            $data['explanation'] = $this->imageService->processHtmlContent($data['explanation']);
        }
        //dd($data);
        $question->fill([
            'code'               => $data['code'],
            'description'        => $data['description'] ?? null,
            'question_type_id'   => $data['question_type_id'],
            'cognitive_level_id' => $data['cognitive_level_id'],
            'question_status_id' => $data['question_status_id'],
            'question_layout_id' => $data['question_layout_id'] ?? null,
            'shared_context_id'  => $data['shared_context_id'] ?? null,
            'stem'               => $data['stem'] ?? null,
            'explanation'        => $data['explanation'] ?? null,
            'layout_ratio'       => $data['layout_ratio'] ?? 1,
            'order_index'        => $data['order_index'] ?? 0,
            'created_by_id'      => $data['created_by_id'] ?? auth()->id(), // Tự động lấy user đang đăng nhập nếu tạo mới
            'reviewer_id'        => $data['reviewer_id'] ?? null,
        ]);

        $question->save();

        // Xử lý quan hệ Nhiều-Nhiều với bảng Objectives (Yêu cầu cần đạt)
        if (isset($data['objective_ids']) && is_array($data['objective_ids'])) {
            // sync() sẽ tự động thêm mới, giữ nguyên hoặc xóa bớt các liên kết
            $question->objectives()->sync($data['objective_ids']);
        }

        return $question;
    }

    /**
     * Các lớp con bắt buộc phải triển khai hàm này
     */
    abstract public function execute(array $data, ?Question $question = null): Question;
}