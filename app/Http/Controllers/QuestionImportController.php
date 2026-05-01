<?php

namespace App\Http\Controllers;

use App\Services\QuestionImportParser;
use App\Services\QuestionImportService;
use App\Services\WordService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;


class QuestionImportController extends Controller
{
    /**
     * Middleware: chỉ user có quyền create-questions mới được import
     */
    public static function middleware(): array
    {
        return [
            new Middleware('can:create-questions', only: ['create', 'store', 'confirm', 'execute', 'preview']),
        ];
    }

    /**
     * GET /question-imports/create
     * Hiển thị form upload file Word
     */
    public function create()
    {
        return view('question-imports.create');
    }

    /**
     * POST /question-imports
     * Nhận file Word, parse, lưu preview vào session, chuyển sang màn hình preview
     */
    /**
     * BƯỚC 2: Xử lý file Upload
     * Inject luôn 3 Service vào hàm này để dùng
     */
    public function store(Request $request, WordService $wordService, QuestionImportParser $parser, QuestionImportService $importService)
    {
        // 1. Validate file upload
        $request->validate([
            'word_file' => ['required', 'file', 'mimes:docx', 'max:10240'],
        ], [
            'word_file.required' => 'Vui lòng chọn file Word.',
            'word_file.mimes'    => 'File phải có định dạng .docx.',
            'word_file.max'      => 'File không được vượt quá 10MB.',
        ]);

        // 2. Dùng WordService để đọc file → mảng [{key, value}]
        $rows = $wordService->importFromWord($request->file('word_file'));

        // 3. Dùng Parser để cấu trúc hóa mảng raw
        $parsed = $parser->parse($rows);

        // 4. Validate + build preview rows, cache kết quả hợp lệ
        $preview = $importService->preview($parsed);

        // 5. Lưu kết quả preview vào session để GET /preview đọc lại
        session([
            'import_preview'   => $preview['rows'],
            'import_summary'   => $preview['summary'],
            'import_cache_key' => $preview['cache_key'],
        ]);

        return redirect()->route('question-imports.preview');
    }

    /**
     * GET /question-imports/preview
     * Hiển thị màn hình preview (đọc từ session)
     */
    public function preview()
    {
        // Nếu không có session → người dùng vào thẳng URL, redirect về form upload
        if (! session()->has('import_preview')) {
            return redirect()->route('question-imports.create')
                ->with('warning', 'Vui lòng tải file lên trước.');
        }

        $rows     = session('import_preview');
        $summary  = session('import_summary');
        $cacheKey = session('import_cache_key');

        return view('question-imports.preview', compact('rows', 'summary', 'cacheKey'));
    }

    /**
     * POST /question-imports/execute
     * Người dùng xác nhận → thực hiện import thật sự vào DB
     */
    public function execute(Request $request, QuestionImportService $importService)
    {
        $request->validate([
            'cache_key' => ['required', 'string'],
        ]);

        try {
            $result = $importService->confirm($request->input('cache_key'));
        } catch (\Exception $e) {
            return redirect()->route('question-imports.create')
                ->with('error', $e->getMessage());
        }

        // Xóa session preview sau khi import xong
        session()->forget(['import_preview', 'import_summary', 'import_cache_key']);

        $msg = "Đã import thành công {$result['imported']} câu hỏi";
        if ($result['imported_contexts'] > 0) {
            $msg .= " và {$result['imported_contexts']} dữ liệu dùng chung";
        }
        $msg .= ".";

        return redirect()->route('questions.index')->with('success', $msg);
    }
}
