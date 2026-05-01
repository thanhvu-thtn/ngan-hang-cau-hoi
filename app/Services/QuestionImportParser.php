<?php

namespace App\Services;

/**
 * QuestionImportParser
 *
 * Nhận vào mảng raw rows từ WordService::importFromWord()
 * (mỗi phần tử là ['key' => '...', 'value' => '...'])
 * và trả về cấu trúc đã được phân tích sẵn sàng để validate + import.
 *
 * Output structure:
 * [
 *   'free_questions'  => [ ...question_block ],
 *   'shared_contexts' => [
 *     [
 *       'code'        => '...',
 *       'description' => '...',
 *       'content'     => '...',
 *       'questions'   => [ ...question_block ],
 *     ]
 *   ],
 * ]
 *
 * question_block:
 * [
 *   'code'                 => '...',
 *   'question_type_code'   => 'MC|TF|SA|ES',
 *   'cognitive_level_code' => '...',
 *   'objective_codes'      => ['...', '...'],
 *   'stem'                 => '...',          // giữ nguyên HTML
 *   'explanation'          => '...',          // giữ nguyên HTML
 *
 *   // MC
 *   'choices' => [
 *     ['content' => '...', 'is_true' => bool, 'order_index' => int],
 *     ...
 *   ],
 *
 *   // TF
 *   'tf_choice' => true|false,
 *
 *   // SA
 *   'sa_choice' => '...',
 *
 *   // ES  →  không có trường answer
 * ]
 */
class QuestionImportParser
{
    // -------------------------------------------------------------------------
    // Hằng số nội bộ
    // -------------------------------------------------------------------------

    private const BEGIN  = 'begin';
    private const END    = 'end';
    private const QUESTION       = 'question';
    private const SHARED_CONTEXT = 'shared_context';

    /** Các từ được coi là TRUE cho câu hỏi TF */
    private const TF_TRUE_VALUES = ['đúng', 'đ', 'true', 't'];

    // -------------------------------------------------------------------------
    // Entry point
    // -------------------------------------------------------------------------

    /**
     * @param  array $rows   Output của WordService::importFromWord()
     * @return array         Structured data (xem docblock ở trên)
     */
    public function parse(array $rows): array
    {
        $result = [
            'free_questions'  => [],
            'shared_contexts' => [],
        ];

        $i                  = 0;
        $total              = count($rows);
        $currentSharedCtx   = null; // shared_context block đang mở

        while ($i < $total) {
            $key   = $this->normalizeKey($rows[$i]['key']);
            $value = $this->normalizeValue($rows[$i]['value']);

            // -- BEGIN SHARED_CONTEXT ------------------------------------------
            if ($key === self::BEGIN && $value === self::SHARED_CONTEXT) {
                [$sharedCtx, $i] = $this->parseSharedContext($rows, $i + 1, $total);
                $result['shared_contexts'][] = $sharedCtx;
                continue; // $i đã được cập nhật bên trong
            }

            // -- BEGIN QUESTION (tự do) ----------------------------------------
            if ($key === self::BEGIN && $value === self::QUESTION) {
                [$question, $i] = $this->parseQuestion($rows, $i + 1, $total);
                $result['free_questions'][] = $question;
                continue;
            }

            $i++;
        }

        return $result;
    }

    // -------------------------------------------------------------------------
    // Parse một block shared_context
    // -------------------------------------------------------------------------

    /**
     * Gọi khi con trỏ $i đang ở dòng SAU "begin | shared_context".
     * Trả về [$sharedCtxBlock, $newIndex] — $newIndex trỏ vào dòng sau "end | shared_context".
     */
    private function parseSharedContext(array $rows, int $i, int $total): array
    {
        $ctx = [
            'code'        => '',
            'description' => '',
            'content'     => '',
            'questions'   => [],
        ];

        while ($i < $total) {
            $key   = $this->normalizeKey($rows[$i]['key']);
            $value = $rows[$i]['value']; // giữ nguyên HTML cho content

            // Kết thúc block shared_context
            if ($key === self::END && $this->normalizeValue($value) === self::SHARED_CONTEXT) {
                $i++;
                break;
            }

            // Câu hỏi con bên trong shared_context
            if ($key === self::BEGIN && $this->normalizeValue($value) === self::QUESTION) {
                [$question, $i] = $this->parseQuestion($rows, $i + 1, $total);
                $ctx['questions'][] = $question;
                continue;
            }

            // Các trường metadata của shared_context
            switch ($key) {
                case 'shared_context_code':
                    $ctx['code'] = trim(strip_tags($value));
                    break;
                case 'description':
                    $ctx['description'] = trim($value); // giữ HTML
                    break;
                case 'content':
                    $ctx['content'] = trim($value); // giữ HTML
                    break;
            }

            $i++;
        }

        return [$ctx, $i];
    }

    // -------------------------------------------------------------------------
    // Parse một block question
    // -------------------------------------------------------------------------

    /**
     * Gọi khi con trỏ $i đang ở dòng SAU "begin | question".
     * Trả về [$questionBlock, $newIndex] — $newIndex trỏ vào dòng sau "end | question".
     */
    private function parseQuestion(array $rows, int $i, int $total): array
    {
        $q = [
            'code'                 => '',
            'question_type_code'   => '',
            'cognitive_level_code' => '',
            'objective_codes'      => [],
            'stem'                 => '',
            'explanation'          => '',
            // answer fields sẽ được thêm tùy loại
        ];

        // Thu thập raw fields trước, xử lý answer sau khi biết type
        $rawChoices = []; // choice1..4
        $rawAnswer  = null;

        while ($i < $total) {
            $key   = $this->normalizeKey($rows[$i]['key']);
            $value = $rows[$i]['value']; // giữ nguyên HTML

            // Kết thúc block question
            if ($key === self::END && $this->normalizeValue($value) === self::QUESTION) {
                $i++;
                break;
            }

            switch ($key) {
                case 'question_code':
                    $q['code'] = trim(strip_tags($value));
                    break;

                case 'question_type_code':
                    $q['question_type_code'] = strtoupper(trim(strip_tags($value)));
                    break;

                case 'cognitive_level_code':
                    $q['cognitive_level_code'] = strtoupper(trim(strip_tags($value)));
                    break;

                case 'objective_codes':
                    $q['objective_codes'] = $this->parseObjectiveCodes($value);
                    break;

                case 'stem':
                    $q['stem'] = trim($value); // giữ HTML
                    break;

                case 'explanation':
                    $q['explanation'] = trim($value); // giữ HTML
                    break;

                case 'choice1':
                case 'choice2':
                case 'choice3':
                case 'choice4':
                    $idx = (int) substr($key, -1); // 1-4
                    $rawChoices[$idx] = trim($value); // giữ HTML
                    break;

                case 'answer':
                    $rawAnswer = trim(strip_tags($value));
                    break;
            }

            $i++;
        }

        // Xử lý answer theo từng loại câu hỏi
        $q = $this->resolveAnswer($q, $rawChoices, $rawAnswer);

        return [$q, $i];
    }

    // -------------------------------------------------------------------------
    // Resolve answer theo question_type_code
    // -------------------------------------------------------------------------

    private function resolveAnswer(array $q, array $rawChoices, ?string $rawAnswer): array
    {
        switch ($q['question_type_code']) {

            case 'MC':
                $choices = [];
                for ($idx = 1; $idx <= 4; $idx++) {
                    $choices[] = [
                        'content'     => $rawChoices[$idx] ?? '',
                        'is_true'     => false,
                        'order_index' => $idx,
                    ];
                }

                if ($rawAnswer === null) {
                    $q['parse_error'] = 'MC: thiếu trường answer.';
                } elseif ($this->isMultipleAnswers($rawAnswer)) {
                    // User ghi '2,3' hoặc 'A,B' → lỗi ngay tại bước parse
                    $q['parse_error'] = "MC: answer '$rawAnswer' chứa nhiều đáp án — chỉ được phép 1.";
                } else {
                    $answerIndex = $this->resolveChoiceIndex($rawAnswer);
                    if ($answerIndex === null) {
                        $q['parse_error'] = "MC: answer '$rawAnswer' không hợp lệ — chấp nhận 1/2/3/4 hoặc A/B/C/D.";
                    } else {
                        $choices[$answerIndex - 1]['is_true'] = true;
                    }
                }

                $q['choices'] = $choices;
                break;

            case 'TF':
                // Chấp nhận: đúng/đ/true/t  và  sai/s/false/f
                $q['tf_choice'] = $this->resolveTfAnswer($rawAnswer);
                break;

            case 'SA':
                $q['sa_choice'] = $rawAnswer ?? '';
                break;

            case 'ES':
                // Không có answer field
                break;
        }

        return $q;
    }

    // -------------------------------------------------------------------------
    // Helper: parse answer index cho MC
    // -------------------------------------------------------------------------

    /**
     * Kiểm tra answer có chứa nhiều đáp án không (vd '2,3' hoặc 'A B')
     */
    private function isMultipleAnswers(string $answer): bool
    {
        return str_contains($answer, ',') || str_contains(trim($answer), ' ');
    }

    /**
     * '1' → 1,  '2' → 2,  'A' → 1,  'B' → 2,  'C' → 3,  'D' → 4
     * Nếu không parse được → null
     */
    private function resolveChoiceIndex(string $answer): ?int
    {
        $answer = strtoupper(trim($answer));

        // Dạng số
        if (in_array($answer, ['1', '2', '3', '4'])) {
            return (int) $answer;
        }

        // Dạng chữ
        $map = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4];
        return $map[$answer] ?? null;
    }

    // -------------------------------------------------------------------------
    // Helper: parse TF answer
    // -------------------------------------------------------------------------

    /**
     * Chuẩn hóa các biến thể đúng/sai về boolean.
     * Trả về null nếu không nhận dạng được (để validation bắt lỗi).
     */
    private function resolveTfAnswer(?string $answer): ?bool
    {
        if ($answer === null) {
            return null;
        }

        $normalized = mb_strtolower(trim(strip_tags($answer)));

        if (in_array($normalized, self::TF_TRUE_VALUES)) {
            return true;
        }

        // Các biến thể FALSE
        if (in_array($normalized, ['sai', 's', 'false', 'f'])) {
            return false;
        }

        return null; // không nhận dạng được
    }

    // -------------------------------------------------------------------------
    // Helper: parse objective_codes
    // -------------------------------------------------------------------------

    /**
     * '11-1CB-002-001-003 # 11-1CB-002-001-005'
     * → ['11-1CB-002-001-003', '11-1CB-002-001-005']
     */
    private function parseObjectiveCodes(string $value): array
    {
        $plain = strip_tags($value);
        $parts = preg_split('/\s*#\s*/', trim($plain));
        return array_values(array_filter(array_map('trim', $parts)));
    }

    // -------------------------------------------------------------------------
    // Helpers: normalize key / value
    // -------------------------------------------------------------------------

    private function normalizeKey(string $key): string
    {
        return strtolower(trim(strip_tags($key)));
    }

    private function normalizeValue(string $value): string
    {
        return strtolower(trim(strip_tags($value)));
    }
}
