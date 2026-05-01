<?php

namespace App\Services;

use App\Models\CognitiveLevel;
use App\Models\Objective;
use App\Models\Question;
use App\Models\QuestionStatus;
use App\Models\QuestionType;
use App\Models\SharedContext;
use App\Models\Choice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuestionImportService
{
    // Cache TTL
    private const CACHE_TTL_MINUTES = 30;
    private const CACHE_PREFIX      = 'question_import:';

    // Trạng thái từng câu hỏi (dùng ở cột "Nhận xét" trên UI)
    public const STATUS_VALID    = 'valid';    // ✅ Hợp lệ, sẵn sàng ghi
    public const STATUS_RENAMED  = 'renamed';  // ⚠️ Hợp lệ nhưng đã đổi code do trùng
    public const STATUS_ERROR    = 'error';    // ❌ Lỗi, không thể ghi

    // -------------------------------------------------------------------------
    // BƯỚC 1 — Preview: validate toàn bộ, cache lại, trả về để hiển thị
    // -------------------------------------------------------------------------

    /**
     * @param  array  $parsed   Output của QuestionImportParser::parse()
     * @return array  ['cache_key' => '...', 'rows' => [...], 'summary' => [...]]
     */
    public function preview(array $parsed): array
    {
        // Load lookup tables 1 lần duy nhất (tránh N+1)
        $lookups = $this->loadLookups();

        $rows = [];
        $ctxValidatedMap = []; // code → validated result, tránh gọi DB 2 lần

        // Set các code đã được gán trong phiên này (tránh trùng giữa các câu trong cùng file)
        // Khởi tạo từ existing_codes để cũng bao phủ luôn các code đã có trong DB
        $seenCodes = $lookups['existing_codes']; // code → code

        // --- Câu hỏi tự do ---
        foreach ($parsed['free_questions'] as $q) {
            $rows[] = $this->validateQuestion($q, null, $lookups, $seenCodes);
        }

        // --- Câu hỏi trong shared_context ---
        foreach ($parsed['shared_contexts'] as $ctx) {
            $ctxValidated = $this->validateSharedContext($ctx, $lookups);
            $ctxValidatedMap[$ctx['code']] = $ctxValidated; // lưu lại, dùng ở extractValidSharedContexts

            // Nếu shared_context bản thân lỗi → tất cả câu con cũng báo lỗi
            if ($ctxValidated['status'] === self::STATUS_ERROR) {
                foreach ($ctx['questions'] as $q) {
                    $rows[] = $this->buildErrorRow(
                        $q,
                        "SharedContext '{$ctx['code']}': {$ctxValidated['remark']}"
                    );
                }
            } else {
                // Đánh dấu shared_context_code vào từng câu để lưu sau
                foreach ($ctx['questions'] as $q) {
                    $q['_shared_context_code'] = $ctx['code'];
                    $rows[] = $this->validateQuestion($q, $ctx['code'], $lookups, $seenCodes);
                }
            }

        }

        // Tính summary
        $summary = $this->summarize($rows);

        // Cache những row có thể ghi được (valid + renamed)
        $validRows = array_filter(
            $rows,
            fn($r) => in_array($r['status'], [self::STATUS_VALID, self::STATUS_RENAMED])
        );

        $cacheKey = self::CACHE_PREFIX . Str::uuid();
        Cache::put($cacheKey, [
            'valid_rows'      => array_values($validRows),
            'shared_contexts' => $this->extractValidSharedContexts($parsed, $rows, $ctxValidatedMap),
        ], now()->addMinutes(self::CACHE_TTL_MINUTES));

        return [
            'cache_key' => $cacheKey,
            'rows'      => $rows,
            'summary'   => $summary,
        ];
    }

    // -------------------------------------------------------------------------
    // BƯỚC 2 — Confirm: đọc cache, lưu DB trong transaction
    // -------------------------------------------------------------------------

    /**
     * @param  string $cacheKey
     * @return array  ['imported' => int, 'skipped_contexts' => int]
     * @throws \Exception nếu cache đã hết hạn
     */
    public function confirm(string $cacheKey): array
    {
        $cached = Cache::get($cacheKey);

        if (!$cached) {
            throw new \Exception('Phiên xem trước đã hết hạn. Vui lòng tải file lên lại.');
        }

        $imported        = 0;
        $importedContexts = 0;

        DB::transaction(function () use ($cached, &$imported, &$importedContexts) {
            $userId        = Auth::id();
            $pendingStatus = QuestionStatus::where('code', 'PENDING')->value('id');

            // 1. Lưu shared_contexts trước (cần id để gán cho câu hỏi)
            $sharedContextIdMap = []; // code → id
            foreach ($cached['shared_contexts'] as $ctx) {
                $sc = SharedContext::create([
                    'code'        => $ctx['code'],
                    'description' => $ctx['description'],
                    'content'     => $ctx['content'],
                ]);
                $sharedContextIdMap[$ctx['code']] = $sc->id;
                $importedContexts++;
            }

            // 2. Lưu từng câu hỏi hợp lệ
            foreach ($cached['valid_rows'] as $row) {
                $sharedContextId = null;
                if (!empty($row['_shared_context_code'])) {
                    $sharedContextId = $sharedContextIdMap[$row['_shared_context_code']] ?? null;
                }

                $question = Question::create([
                    'code'                 => $row['code_to_save'],
                    'question_type_id'     => $row['_question_type_id'],
                    'cognitive_level_id'   => $row['_cognitive_level_id'],
                    'question_status_id'   => $pendingStatus,
                    'shared_context_id'    => $sharedContextId,
                    'stem'                 => $row['stem'],
                    'explanation'          => $row['explanation'] ?? null,
                    'created_by_id'        => $userId,
                ]);

                // Gắn objectives (many-to-many)
                $question->objectives()->attach($row['_objective_ids']);

                // Lưu choices
                $this->saveChoices($question, $row);

                $imported++;
            }
        });

        // Xóa cache sau khi confirm thành công
        Cache::forget($cacheKey);

        return [
            'imported'          => $imported,
            'imported_contexts' => $importedContexts,
        ];
    }

    // -------------------------------------------------------------------------
    // Validate một câu hỏi
    // -------------------------------------------------------------------------

    private function validateQuestion(array $q, ?string $sharedContextCode, array $lookups, array &$seenCodes): array
    {
        $errors        = [];
        $renamedRemark = null;

        // Lỗi parse có sẵn (vd MC nhiều đáp án)
        if (!empty($q['parse_error'])) {
            return $this->buildErrorRow($q, $q['parse_error'], $sharedContextCode);
        }

        // --- question_code ---
        $originalCode = $q['code'] ?? '';
        if (empty($originalCode)) {
            $errors[] = 'Thiếu question_code.';
        } elseif (isset($seenCodes[$originalCode])) {
            // Trùng code (với DB hoặc với câu khác trong cùng file)
            // → tạo code mới đảm bảo chưa từng thấy
            do {
                $newCode = $this->generateUniqueCode($originalCode);
            } while (isset($seenCodes[$newCode]));

            $q['code']          = $newCode;
            $q['code_original'] = $originalCode;
            $seenCodes[$newCode] = $newCode; // đánh dấu code mới cũng đã dùng
            $renamedRemark = "Trùng code '$originalCode' — sẽ lưu với code mới.";
        } else {
            // Code hợp lệ và chưa thấy → đăng ký vào seenCodes
            $seenCodes[$originalCode] = $originalCode;
        }

        // --- question_type ---
        $typeId = $lookups['types'][$q['question_type_code']] ?? null;
        if (!$typeId) {
            $errors[] = "Loại câu hỏi '{$q['question_type_code']}' không hợp lệ.";
        }

        // --- cognitive_level ---
        $levelId = $lookups['levels'][$q['cognitive_level_code']] ?? null;
        if (!$levelId) {
            $errors[] = "Mức độ nhận thức '{$q['cognitive_level_code']}' không tồn tại.";
        }

        // --- objectives ---
        $objectiveIds = [];
        if (empty($q['objective_codes'])) {
            $errors[] = 'Thiếu objective_codes.';
        } else {
            foreach ($q['objective_codes'] as $objCode) {
                $objId = $lookups['objectives'][$objCode] ?? null;
                if (!$objId) {
                    $errors[] = "Objective '$objCode' không tồn tại trong DB.";
                } else {
                    $objectiveIds[] = $objId;
                }
            }
        }

        // --- stem ---
        if (empty(trim(strip_tags($q['stem'] ?? '')))) {
            $errors[] = 'Thiếu nội dung câu hỏi (stem).';
        }

        // --- validate theo type ---
        if ($typeId) {
            $typeErrors = $this->validateByType($q);
            $errors     = array_merge($errors, $typeErrors);
        }

        if (!empty($errors)) {
            return $this->buildErrorRow($q, implode(' | ', $errors), $sharedContextCode);
        }

        // ✅ Hợp lệ — ghi thêm các _internal fields để dùng lúc confirm
        $status = $renamedRemark ? self::STATUS_RENAMED : self::STATUS_VALID;
        $remark = $renamedRemark ? "⚠️ $renamedRemark" : '✅ Hợp lệ';

        $row                        = $this->buildRow($q, $status, $remark, $sharedContextCode);
        $row['_question_type_id']   = $typeId;
        $row['_cognitive_level_id'] = $levelId;
        $row['_objective_ids']      = $objectiveIds;

        return $row;
    }

    // -------------------------------------------------------------------------
    // Validate theo từng loại câu hỏi
    // -------------------------------------------------------------------------

    private function validateByType(array $q): array
    {
        $errors = [];

        switch ($q['question_type_code']) {
            case 'MC':
                // Kiểm tra đủ 4 choices có content
                for ($i = 1; $i <= 4; $i++) {
                    $choice = $q['choices'][$i - 1] ?? null;
                    if (!$choice || empty(trim(strip_tags($choice['content'])))) {
                        $errors[] = "Choice $i bị trống.";
                    }
                }
                // Kiểm tra đúng 1 đáp án đúng
                $trueCount = count(array_filter($q['choices'] ?? [], fn($c) => $c['is_true']));
                if ($trueCount !== 1) {
                    $errors[] = 'MC phải có chính xác 1 đáp án đúng.';
                }
                break;

            case 'TF':
                if (!isset($q['tf_choice']) || $q['tf_choice'] === null) {
                    $errors[] = "Answer TF không hợp lệ — chấp nhận: đúng/sai, đ/s, true/false, t/f.";
                }
                break;

            case 'SA':
                $sa = $q['sa_choice'] ?? '';
                if (empty($sa)) {
                    $errors[] = 'Thiếu answer cho câu SA.';
                } elseif (strlen($sa) > 4) {
                    $errors[] = "SA answer '$sa' vượt quá 4 ký tự.";
                } elseif (!preg_match('/^[\d\-,]+$/', $sa)) {
                    $errors[] = "SA answer '$sa' chỉ được chứa số 0-9, dấu trừ (-) và dấu phẩy (,).";
                }
                break;

            case 'ES':
                // Không có answer, không cần validate thêm
                break;

            default:
                $errors[] = "Loại câu hỏi '{$q['question_type_code']}' chưa được hỗ trợ.";
        }

        return $errors;
    }

    // -------------------------------------------------------------------------
    // Validate shared_context
    // -------------------------------------------------------------------------

    private function validateSharedContext(array $ctx, array $lookups): array
    {
        if (empty($ctx['code'])) {
            return ['status' => self::STATUS_ERROR, 'remark' => 'Thiếu shared_context_code.'];
        }

        if (SharedContext::where('code', $ctx['code'])->exists()) {
            return ['status' => self::STATUS_ERROR, 'remark' => "Mã '{$ctx['code']}' đã tồn tại."];
        }

        if (empty(trim(strip_tags($ctx['content'] ?? '')))) {
            return ['status' => self::STATUS_ERROR, 'remark' => 'Thiếu content của shared_context.'];
        }

        return ['status' => self::STATUS_VALID, 'remark' => '✅ Hợp lệ'];
    }

    // -------------------------------------------------------------------------
    // Lưu choices vào DB
    // -------------------------------------------------------------------------

    private function saveChoices(Question $question, array $row): void
    {
        switch ($row['question_type_code']) {
            case 'MC':
                foreach ($row['choices'] as $choice) {
                    Choice::create([
                        'question_id' => $question->id,
                        'content'     => $choice['content'],
                        'is_true'     => $choice['is_true'],
                        'order_index' => $choice['order_index'],
                    ]);
                }
                break;

            case 'TF':
                Choice::create([
                    'question_id' => $question->id,
                    'content'     => $row['tf_choice'] ? 'Đúng' : 'Sai',
                    'is_true'     => $row['tf_choice'],
                    'order_index' => 1,
                ]);
                break;

            case 'SA':
                Choice::create([
                    'question_id' => $question->id,
                    'content'     => $row['sa_choice'],
                    'is_true'     => true,
                    'order_index' => 1,
                ]);
                break;

            case 'ES':
                // Không lưu choice
                break;
        }
    }

    // -------------------------------------------------------------------------
    // Load toàn bộ lookup một lần (tránh N+1)
    // -------------------------------------------------------------------------

    private function loadLookups(): array
    {
        return [
            // code → id
            'types'          => QuestionType::pluck('id', 'code')->all(),
            'levels'         => CognitiveLevel::pluck('id', 'code')->all(),
            'objectives'     => Objective::pluck('id', 'code')->all(),
            // Set các code đã tồn tại để check trùng
            'existing_codes' => Question::pluck('code', 'code')->all(),
        ];
    }

    // -------------------------------------------------------------------------
    // Helpers: build row
    // -------------------------------------------------------------------------

    private function buildRow(array $q, string $status, string $remark, ?string $sharedContextCode = null): array
    {
        return [
            // Hiển thị trên bảng
            'code'                 => $q['code_original'] ?? $q['code'] ?? '', // luôn hiện code gốc
            'code_to_save'         => $q['code'] ?? '',                         // code thực sự lưu DB
            'question_type_code'   => $q['question_type_code'] ?? '',
            'cognitive_level_code' => $q['cognitive_level_code'] ?? '',
            'objective_codes'      => $q['objective_codes'] ?? [],
            'stem'                 => $q['stem'] ?? '',
            'explanation'          => $q['explanation'] ?? '',
            'choices'              => $q['choices'] ?? [],
            'tf_choice'            => $q['tf_choice'] ?? null,
            'sa_choice'            => $q['sa_choice'] ?? null,
            // Trạng thái & nhận xét
            'status'               => $status,
            'remark'               => $remark,
            // Internal (dùng lúc confirm, không hiển thị)
            '_shared_context_code' => $sharedContextCode,
        ];
    }

    // -------------------------------------------------------------------------
    // Helper: tạo code mới khi bị trùng
    // -------------------------------------------------------------------------

    /**
     * 12 ký tự đầu của code gốc + '-' + uuid đầy đủ 36 ký tự
     * Ví dụ: 'Import-2026-05' + '-' + 'a1b2c3d4-e5f6-...'
     */
    private function generateUniqueCode(string $originalCode): string
    {
        $prefix = substr($originalCode, 0, 12);
        return $prefix . '-' . Str::uuid()->toString();
    }

    private function buildErrorRow(array $q, string $errorMessage, ?string $sharedContextCode = null): array
    {
        $row           = $this->buildRow($q, self::STATUS_ERROR, "❌ $errorMessage", $sharedContextCode);
        return $row;
    }

    // -------------------------------------------------------------------------
    // Helper: tính summary
    // -------------------------------------------------------------------------

    private function summarize(array $rows): array
    {
        $total   = count($rows);
        $valid   = count(array_filter($rows, fn($r) => $r['status'] === self::STATUS_VALID));
        $renamed = count(array_filter($rows, fn($r) => $r['status'] === self::STATUS_RENAMED));
        $error   = count(array_filter($rows, fn($r) => $r['status'] === self::STATUS_ERROR));

        return compact('total', 'valid', 'renamed', 'error');
    }

    // -------------------------------------------------------------------------
    // Helper: rút shared_contexts hợp lệ để cache
    // -------------------------------------------------------------------------

    private function extractValidSharedContexts(array $parsed, array $rows, array $ctxValidatedMap): array
    {
        // Lấy danh sách shared_context_code có ít nhất 1 câu hỏi valid
        $codesWithValidQuestion = collect($rows)
            ->filter(fn($r) => in_array($r['status'], [self::STATUS_VALID, self::STATUS_RENAMED]) && !empty($r['_shared_context_code']))
            ->pluck('_shared_context_code')
            ->unique()
            ->all();

        $result = [];
        foreach ($parsed['shared_contexts'] as $ctx) {
            // Dùng kết quả đã validate từ preview(), không gọi DB lần 2
            $ctxValidated = $ctxValidatedMap[$ctx['code']] ?? ['status' => self::STATUS_ERROR];
            if (
                $ctxValidated['status'] === self::STATUS_VALID &&
                in_array($ctx['code'], $codesWithValidQuestion)
            ) {
                $result[] = [
                    'code'        => $ctx['code'],
                    'description' => $ctx['description'],
                    'content'     => $ctx['content'],
                ];
            }
        }

        return $result;
    }
}