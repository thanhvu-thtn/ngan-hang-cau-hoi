<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Objective;
use App\Models\Topic;
use App\Models\TopicContent;
use App\Models\TopicType;
use App\Services\WordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ObjectiveController extends Controller
{
    public function index(Request $request)
    {
        // Khởi tạo query và join các bảng liên quan
        $query = Objective::query()
            ->select('objectives.*')
            ->join('topic_contents', 'objectives.topic_content_id', '=', 'topic_contents.id')
            ->join('topics', 'topic_contents.topic_id', '=', 'topics.id')
            ->join('grades', 'topics.grade_id', '=', 'grades.id')
            ->join('topic_types', 'topics.topic_type_id', '=', 'topic_types.id')
            // Eager load để tránh N+1 khi hiển thị ở View
            ->with(['topicContent.topic.grade', 'topicContent.topic.topicType']);

        // --- PHẦN LỌC (FILTERS) ---
        // 1. Lọc theo mã Objective
        if ($request->filled('objective_code')) {
            $query->where('objectives.code', 'like', '%'.$request->objective_code.'%');
        }
        // 2. Lọc theo mã Nội dung chuyên đề
        if ($request->filled('content_code')) {
            $query->where('topic_contents.code', 'like', '%'.$request->content_code.'%');
        }
        // 3. Tìm kiếm theo mã Chuyên đề
        if ($request->filled('topic_code')) {
            $query->where('topics.code', 'like', '%'.$request->topic_code.'%');
        }
        // 4. Lọc theo Khối (dùng ID)
        if ($request->filled('grade_id')) {
            $query->where('topics.grade_id', $request->grade_id);
        }
        // 5. Lọc theo Loại chuyên đề (dùng ID)
        if ($request->filled('topic_type_id')) {
            $query->where('topics.topic_type_id', $request->topic_type_id);
        }

        // --- SẮP XẾP (SORTING) ---
        $query->orderBy('objectives.code', 'asc')
            ->orderBy('topic_contents.code', 'asc')
            ->orderBy('topics.code', 'asc')
            ->orderBy('grades.code', 'asc')
            ->orderBy('topic_types.code', 'asc');

        $objectives = $query->paginate(20)->withQueryString();

        // Lấy dữ liệu cho các ô Select filter
        $grades = Grade::all();
        $topicTypes = TopicType::all();

        return view('objectives.index', compact('objectives', 'grades', 'topicTypes'));
    }

    public function create(Request $request)
    {
        $topicContentId = $request->query('topic_content_id');
        $uuid = null;
        $fixedTopicContent = null;

        if ($topicContentId) {
            $fixedTopicContent = TopicContent::with('topic.grade', 'topic.topicType')->findOrFail($topicContentId);
            $uuid = (string) Str::uuid();
            // Lưu ID vào cache trong 30 phút
            Cache::put("create_obj_target_{$uuid}", $topicContentId, now()->addMinutes(30));
        }

        // Dữ liệu cho trường hợp tạo tự do (filters)
        $grades = Grade::all();
        $topicTypes = TopicType::all();
        $topics = Topic::all();
        $contents = TopicContent::all();

        return view('objectives.create', compact(
            'uuid',
            'fixedTopicContent',
            'grades',
            'topicTypes',
            'topics',
            'contents'
        ));
    }

    /**
     * Xử lý lưu Yêu cầu cần đạt mới (Store)
     */
    public function store(Request $request)
    {
        $request->validate([
            'topic_content_id' => 'required|exists:topic_contents,id',
            'code' => 'required|unique:objectives,code',
            'description' => 'required',
        ]);

        // Lưu Objective
        $objective = Objective::create([
            'topic_content_id' => $request->topic_content_id,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        // Xử lý điều hướng thông minh qua UUID
        if ($request->filled('uuid')) {
            $uuid = $request->uuid;
            if (Cache::has("create_obj_target_{$uuid}")) {
                $tcId = Cache::pull("create_obj_target_{$uuid}"); // Lấy ra và xóa cache

                return redirect()->route('topic-contents.show', $tcId)
                    ->with('success', 'Đã thêm yêu cầu cần đạt mới.');
            }
        }

        return redirect()->route('objectives.index')
            ->with('success', 'Đã thêm yêu cầu cần đạt mới.');
    }

    public function edit(Request $request, Objective $objective)
    {
        $backUuid = null;
        $topicContentId = $request->query('topic_content_id');
        // Lấy tất cả nội dung kèm theo thông tin chuyên đề, khối, loại để hiển thị trong select
        $topicContents = TopicContent::with('topic.grade', 'topic.topicType')->get();
        // Nếu có topic_content_id truyền lên, tạo cache để đánh dấu điểm quay về
        if ($topicContentId) {
            $backUuid = (string) Str::uuid();
            // Lưu ID của topic_content vào cache trong 60 phút
            Cache::put('obj_edit_back_'.$backUuid, $topicContentId, now()->addMinutes(60));

            return view('objectives.edit', compact('objective', 'topicContents', 'backUuid'));
        }

        return view('objectives.edit', compact('objective', 'topicContents'));
    }

    /**
     * Xử lý cập nhật Yêu cầu cần đạt (Update)
     */
    public function update(Request $request, Objective $objective)
    {
        // 1. Validate dữ liệu gửi lên
        $request->validate([
            'topic_content_id' => 'required|exists:topic_contents,id',
            // Rule unique phải ngoại trừ ID của chính bản ghi đang sửa để tránh báo lỗi trùng lặp với chính nó
            'code' => 'required|string|max:50|unique:objectives,code,'.$objective->id,
            'description' => 'required|string',
        ], [
            'topic_content_id.required' => 'Vui lòng chọn Nội dung chuyên đề.',
            'topic_content_id.exists' => 'Nội dung chuyên đề được chọn không tồn tại.',
            'code.required' => 'Vui lòng nhập mã định danh.',
            'code.unique' => 'Mã định danh này đã được sử dụng cho một yêu cầu khác.',
            'code.max' => 'Mã định danh không được vượt quá 50 ký tự.',
            'description.required' => 'Vui lòng nhập mô tả chi tiết.',
        ]);

        // 2. Cập nhật vào Database
        try {
            $objective->update([
                'topic_content_id' => $request->topic_content_id,
                'code' => $request->code,
                'description' => $request->description,
            ]);

            // 3. Chuyển hướng về trang danh sách kèm thông báo
            $uuid = $request->input('uuid');

            if ($uuid) {
                // Lấy topic_content_id từ cache và xóa luôn (pull)
                $backId = Cache::pull('obj_edit_back_'.$uuid);

                if ($backId) {
                    return redirect()->route('topic-contents.show', $backId)
                        ->with('success', 'Cập nhật Yêu cầu cần đạt thành công.');
                }
            }

            // Mặc định quay về trang danh sách nếu không có uuid hoặc cache hết hạn
            return redirect()->route('objectives.index')
                ->with('success', 'Đã cập nhật thông tin Yêu cầu cần đạt thành công!');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Có lỗi xảy ra khi cập nhật: '.$e->getMessage());
        }
    }

    public function destroy(Request $request, Objective $objective)
    {
        $topicContentId = $request->query('topic_content_id');
        $objective->delete();
        if ($topicContentId) {
            return redirect()->route('topic-contents.show', $topicContentId)
                ->with('success', 'Đã xóa Yêu cầu cần đạt!');
        }

        return redirect()->route('objectives.index')->with('success', 'Đã xóa Yêu cầu cần đạt!');
    }

    public function importWord()
    {
        return view('objectives.import');
    }

    public function previewWord(Request $request, WordService $wordService)
    {
        $request->validate([
            'word_file' => 'required|file|mimes:docx|max:10240', // Max 10MB
        ]);

        $uuid = (string) Str::uuid();

        // Lưu file vào thư mục public/storage/word-template
        $folderPath = storage_path('app/public/word-template');
        if (! file_exists($folderPath)) {
            mkdir($folderPath, 0775, true);
        }

        $file = $request->file('word_file');
        $fileName = $uuid.'.docx';
        $file->move($folderPath, $fileName);
        $fullPath = $folderPath.'/'.$fileName;

        // 1. Lấy dữ liệu từ WordService
        $parsedData = $wordService->importObjectivesFromWord($fullPath);

        // Chuẩn bị dữ liệu kiểm tra
        $existingTcCodes = TopicContent::pluck('id', 'code')->toArray();
        $existingObjCodes = Objective::pluck('id', 'code')->toArray();
        $fileObjCodes = []; // Mảng tạm để kiểm tra trùng mã ngay trong file

        $validatedData = [];

        // 2. Validate từng dòng
        foreach ($parsedData as $row) {
            $status = 'pass';
            $message = 'Hợp lệ';
            $tcCode = $row['topic_content_code'];
            $objCode = $row['objective_code'];
            $desc = $row['objective_description'];

            // Lỗi 1: topic_content_code không tồn tại
            if (empty($tcCode) || ! isset($existingTcCodes[$tcCode])) {
                $status = 'fail';
                $message = 'Mã ND Chuyên đề không tồn tại trong CSDL';
            }
            // Lỗi 2: description rỗng
            elseif (empty(strip_tags($desc))) {
                $status = 'fail';
                $message = 'Mô tả không được để trống';
            }
            // Lỗi 3: Mã trùng
            else {
                if (empty($objCode) || isset($existingObjCodes[$objCode]) || in_array($objCode, $fileObjCodes)) {
                    $message = 'Mã YCCĐ trùng hoặc rỗng. Sẽ được tự động tạo mã mới khi lưu.';
                    // Đánh dấu pass nhưng thông báo để user biết
                } else {
                    $fileObjCodes[] = $objCode; // Lưu lại để kiểm tra dòng tiếp theo
                }
            }

            $validatedData[] = (object) [
                'topic_content_code' => $tcCode,
                'objective_code' => $objCode,
                'objective_description' => $desc,
                'status' => $status,
                'message' => $message,
            ];
        }

        // 3. Lưu vào Cache (60 phút)
        Cache::put('import_word_obj_'.$uuid, $validatedData, 3600);
        // Cache::put('import_word_obj_'.$uuid, json_encode($validatedData), 3600);

        // 4. Xóa file temp (không bắt buộc nhưng nên làm cho sạch)
        @unlink($fullPath);

        return view('objectives.preview', compact('validatedData', 'uuid'));
    }

    public function saveFromWord(Request $request)
    {
        $uuid = $request->input('uuid');
        $cacheKey = 'import_word_obj_'.$uuid;

        if (! Cache::has($cacheKey)) {
            return redirect()->route('objectives.import.word')->with('error', 'Phiên làm việc đã hết hạn. Vui lòng thử lại.');
        }

        // Lấy thẳng từ Cache ra, nó tự hiểu là mảng
        $data = Cache::get($cacheKey);
        // dd($data);
        $existingTcCodes = TopicContent::pluck('id', 'code')->toArray();
        $count = 0;

        foreach ($data as $item) {
            // Dòng "thần thánh" này sẽ biến $item thành mảng
            // bất kể nó đang là Object hay Mảng
            $item = (array) $item;

            // Sau dòng trên, bạn dùng cú pháp ngoặc vuông [] cho toàn bộ bên dưới
            if ($item['status'] === 'fail') {
                continue;
            }

            $tcId = $existingTcCodes[$item['topic_content_code']] ?? null;
            if (! $tcId) {
                continue;
            }

            $codeToSave = $item['objective_code'];

            if (empty($codeToSave) || Objective::where('code', $codeToSave)->exists()) {
                $codeToSave = $item['topic_content_code'].'-'.Str::random(5);
            }

            Objective::create([
                'topic_content_id' => $tcId,
                'code' => $codeToSave,
                'description' => $item['objective_description'],
            ]);
            $count++;
        }

        Cache::forget($cacheKey);

        return redirect()->route('objectives.index')->with('success', "Đã lưu thành công {$count} Yêu cầu cần đạt.");
    }

    public function cancelFromWord(Request $request)
    {
        $uuid = $request->input('uuid');
        Cache::forget('import_word_obj_'.$uuid);

        return redirect()->route('objectives.index')->with('success', 'Đã hủy quá trình nhập dữ liệu.');
    }

    public function wordExport(Request $request)
    {
        // 1. Lấy tham số từ request
        $grade_id = $request->query('grade_id');
        $topic_type_id = $request->query('topic_type_id');
        // 2. Bắt buộc phải có 2 tham số này
        if (! $grade_id || ! $topic_type_id) {
            return redirect()->back()->with('error', 'Vui lòng chọn Khối và Loại chuyên đề trước khi xuất Word.');
        }

        // 3. Truy vấn dữ liệu có lọc
        $objectives = Objective::with(['topicContent.topic.grade', 'topicContent.topic.topicType'])
            ->join('topic_contents', 'objectives.topic_content_id', '=', 'topic_contents.id')
            ->join('topics', 'topic_contents.topic_id', '=', 'topics.id')
            ->join('grades', 'topics.grade_id', '=', 'grades.id')
            ->join('topic_types', 'topics.topic_type_id', '=', 'topic_types.id')
            // Thêm điều kiện lọc theo ID
            ->where('grades.id', $grade_id)
            ->where('topic_types.id', $topic_type_id)
            ->orderBy('topics.id')
            ->select('objectives.*')
            ->get();

        if ($objectives->isEmpty()) {
            return redirect()->back()->with('error', 'Không có dữ liệu phù hợp để xuất file.');
        }

        // 2. Nhóm dữ liệu: Topic -> TopicContent -> Objectives
        // Việc nhóm này giúp Blade xử lý các vòng lặp lồng nhau dễ dàng hơn
        $groupedData = $objectives->groupBy('topicContent.topic_id');

        // Lấy thông tin chung (giả sử xuất cho 1 khối/loại cụ thể từ dòng đầu tiên)
        $firstObj = $objectives->first();
        $headerInfo = [
            'grade' => $firstObj ? $firstObj->topicContent->topic->grade->name : '',
            'type' => $firstObj ? $firstObj->topicContent->topic->topicType->name : '',
        ];

        // 3. Render trang blade
        $html = view('objectives.word_export', compact('groupedData', 'headerInfo'))->render();

        // 3. Lọc lấy nội dung bên trong thẻ <body>
        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $matches)) {
            $content = $matches[1];
        } else {
            $content = $html;
        }

        // 4. Gọi WordService (Lưu ý: kết quả trả về là một mảng)
        $wordService = new WordService;
        $wordData = $wordService->generateDocxFromHtml($content); // Trả về mảng

        // Kiểm tra xem file thực sự đã được tạo ra chưa trước khi cho tải
        if (! isset($wordData['path']) || ! file_exists($wordData['path'])) {
            return redirect()->back()->with('error', 'Lỗi: Không thể tạo file Word. Vui lòng kiểm tra lại cấu hình Pandoc.');
        }

        // 5. Trả về file cho người dùng download
        // Sửa thành $wordData['path'] để lấy chuỗi đường dẫn
        return response()->download($wordData['path'], 'Chuong-trinh-khung-Vat-ly-THPT.docx')
            ->deleteFileAfterSend(true);
    }
}
