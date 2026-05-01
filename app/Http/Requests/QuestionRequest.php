<?php

namespace App\Http\Requests;

use App\Models\QuestionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Xử lý Rule Unique cho trường hợp Update
        // Giả sử route của bạn là Route::resource('questions', QuestionController::class)
        // thì $this->route('question') sẽ lấy ra được ID hoặc Model của câu hỏi đang sửa
        $questionId = $this->route('question');

        $rules = [
            'code' => ['required', 'string', 'max:50', Rule::unique('questions', 'code')->ignore($questionId)],
            'description' => ['nullable', 'string', 'max:255'],
            'question_type_id' => ['required', 'integer', 'exists:question_types,id'],
            'cognitive_level_id' => ['required', 'integer', 'exists:cognitive_levels,id'],
            'question_status_id' => ['required', 'integer', 'exists:question_statuses,id'],
            'question_layout_id' => ['nullable', 'integer', 'exists:question_layouts,id'],
            'shared_context_id' => ['nullable', 'integer', 'exists:shared_contexts,id'],

            'stem' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'layout_ratio' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'order_index' => ['nullable', 'integer'],

            'objective_ids' => ['required', 'array', 'min:1'],
            'objective_ids.*' => ['integer', 'exists:objectives,id'],
        ];

        // Rút trích loại câu hỏi
        $type = QuestionType::find($this->input('question_type_id'));

        if ($type) {
            switch ($type->code) {
                case 'MC':
                    $rules['choices'] = ['required', 'array', 'size:4'];
                    $rules['choices.*.content'] = ['required', 'string'];
                    $rules['choices.*.is_true'] = ['required', 'boolean'];
                    $rules['choices.*.order_index'] = ['nullable', 'integer'];
                    $rules['choices'][] = function ($attribute, $value, $fail) {
                        $trueCount = collect($value)->where('is_true', true)->count();
                        if ($trueCount !== 1) {
                            $fail('Câu hỏi Trắc nghiệm nhiều lựa chọn phải có chính xác 1 đáp án đúng.');
                        }
                    };
                    break;

                case 'TF':
                    // Chỉ yêu cầu 1 biến đơn tf_choice (dạng boolean)
                    $rules['tf_choice'] = ['required', 'boolean'];
                    break;

                case 'SA':
                    // Chỉ yêu cầu 1 biến đơn sa_choice, giữ nguyên các ràng buộc khắt khe
                    $rules['sa_choice'] = [
                        'required',
                        'string',
                        'max:4',
                        'regex:/^[\d\-,]+$/', // Chỉ cho phép số 0-9, dấu - và dấu ,
                    ];
                    break;

                case 'ES':
                    break;
            }
        }

        return $rules;
    }

    /**
     * Dịch tên các trường dữ liệu sang tiếng Việt
     */
    public function attributes(): array
    {
        return [
            'code' => 'Mã câu hỏi',
            'question_type_id' => 'Loại câu hỏi',
            'cognitive_level_id' => 'Mức độ nhận thức',
            'question_status_id' => 'Trạng thái câu hỏi',
            'stem' => 'Nội dung câu hỏi',
            'objective_ids' => 'Yêu cầu cần đạt',
            'choices' => 'Danh sách lựa chọn/đáp án',
            'choices.*.content' => 'Nội dung đáp án',
            'choices.*.is_true' => 'Đáp án đúng/sai',
        ];
    }

    /**
     * Tùy biến thông báo lỗi tiếng Việt (cho các rule đặc biệt)
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống.',
            'unique' => ':attribute này đã tồn tại trên hệ thống.',
            'exists' => ':attribute không hợp lệ.',

            'objective_ids.min' => 'Vui lòng chọn ít nhất 1 Yêu cầu cần đạt.',

            'layout_ratio.numeric' => 'Tỉ lệ dàn trang phải là một số.',
            'layout_ratio.min' => 'Tỉ lệ dàn trang không được nhỏ hơn 0.',
            'layout_ratio.max' => 'Tỉ lệ dàn trang không được lớn hơn 1.',

            'choices.size' => 'Số lượng lựa chọn không đúng với định dạng của loại câu hỏi này.',

            // Thông báo lỗi riêng cho loại Trả lời ngắn (SA)
            'choices.*.content.size' => 'Đáp án trả lời ngắn bắt buộc phải có độ dài chính xác là 4 ký tự.',
            'choices.*.content.regex' => 'Đáp án trả lời ngắn chỉ được chứa các ký tự số (0-9), dấu trừ (-) và dấu phẩy (,).',
        ];
    }
}
