<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Topic;
use App\Models\TopicContent;
use App\Models\TopicType;
use App\Services\ExcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TopicContentController extends Controller
{
    // Hiển thị danh sách nội dung
    public function index(Request $request)
    {
        // Khởi tạo query và Join các bảng liên quan để có thể sắp xếp & tìm kiếm
        $query = TopicContent::query()
            ->select('topic_contents.*')
            ->join('topics', 'topic_contents.topic_id', '=', 'topics.id')
            ->join('grades', 'topics.grade_id', '=', 'grades.id')
            ->join('topic_types', 'topics.topic_type_id', '=', 'topic_types.id')
            ->with(['topic.grade', 'topic.topicType']);

        // 1. Lọc theo mã nội dung (topic_contents.code)
        if ($request->filled('content_code')) {
            $query->where('topic_contents.code', 'like', '%'.$request->content_code.'%');
        }

        // 2. Lọc theo mã chuyên đề (topics.code)
        if ($request->filled('topic_code')) {
            $query->where('topics.code', 'like', '%'.$request->topic_code.'%');
        }

        // 3. Lọc chính xác theo Khối (qua grade_id)
        if ($request->filled('grade_id')) {
            $query->where('topics.grade_id', $request->grade_id);
        }

        // 4. Lọc chính xác theo Loại chuyên đề (qua topic_type_id)
        if ($request->filled('topic_type_id')) {
            $query->where('topics.topic_type_id', $request->topic_type_id);
        }

        // Sắp xếp TĂNG DẦN theo đúng thứ tự ưu tiên bạn yêu cầu
        $contents = $query->orderBy('topic_contents.code', 'asc')
            ->orderBy('topics.code', 'asc')
            ->orderBy('grades.code', 'asc')
            ->orderBy('topic_types.code', 'asc')
            ->paginate(15)
            ->withQueryString();

        // Lấy danh sách Khối và Loại để làm form Select Dropdown
        $grades = Grade::all();
        $topicTypes = TopicType::all();

        return view('topic_contents.index', compact('contents', 'grades', 'topicTypes'));
    }

    // Giao diện thêm mới
    public function create(Request $request)
    {
        $grades = Grade::all();
        $topicTypes = TopicType::all();
        $topics = Topic::all();

        $fixedTopic = null;
        $returnUuid = null;

        // Nếu có truyền topic_id từ trang topics.show
        if ($request->has('topic_id')) {
            $fixedTopic = Topic::findOrFail($request->topic_id);

            // Tạo UUID và lưu id của topic vào Cache trong 60 phút
            $returnUuid = Str::uuid()->toString();
            Cache::put('return_topic_'.$returnUuid, $fixedTopic->id, now()->addMinutes(60));
        }

        return view('topic_contents.create', compact('topics', 'grades', 'topicTypes', 'fixedTopic', 'returnUuid'));
    }

    // Lưu vào Database
    public function store(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'code' => 'required|string|max:50|unique:topic_contents,code',
            'name' => 'required|string|max:255',
        ]);

        TopicContent::create($request->all());

        // Kiểm tra xem có UUID trả về không
        if ($request->filled('return_uuid')) {
            // Lấy ID từ cache ra và xóa cache đó đi (pull)
            $topicId = Cache::pull('return_topic_'.$request->return_uuid);

            if ($topicId) {
                // Trở về trang chi tiết chuyên đề
                return redirect()->route('topics.show', $topicId)->with('success', 'Thêm nội dung thành công!');
            }
        }

        // Nếu không có UUID hoặc cache hết hạn, trở về trang index bình thường
        return redirect()->route('topic-contents.index')->with('success', 'Thêm nội dung thành công!');
    }

    // Giao diện chỉnh sửa
    public function edit(Request $request, TopicContent $topicContent)
    {
        $topics = Topic::all();
        $grades = Grade::all();
        $topicTypes = TopicType::all();

        $editUuid = null;

        // Nếu request có truyền lên from_topic (ID của Topic)
        if ($request->filled('from_topic')) {
            $editUuid = (string) Str::uuid();
            // Lưu topic_id vào cache với key là uuid trong 60 phút
            Cache::put('edit_origin_'.$editUuid, $request->from_topic, 3600);
        }

        return view('topic_contents.edit', compact(
            'topicContent',
            'topics',
            'grades',
            'topicTypes',
            'editUuid'
        ));
    }

    // Cập nhật dữ liệu
    public function update(Request $request, TopicContent $topicContent)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'code' => 'required|string|max:50|unique:topic_contents,code,'.$topicContent->id,
            'name' => 'required|string|max:255',
        ]);

        $topicContent->update($request->all());

        // Kiểm tra xem có uuid được gửi lên từ form không
        $uuid = $request->input('edit_uuid');

        if ($uuid && Cache::has('edit_origin_'.$uuid)) {
            // Lấy topic_id ra và xóa cache luôn (pull)
            $topicId = Cache::pull('edit_origin_'.$uuid);

            return redirect()->route('topics.show', $topicId)
                ->with('success', 'Cập nhật nội dung thành công!');
        }

        // Mặc định trả về index nếu không có uuid hoặc cache hết hạn
        return redirect()->route('topic-contents.index')
            ->with('success', 'Cập nhật thành công!');
    }

    // Xóa nội dung
    public function destroy(Request $request, TopicContent $topicContent)
    {
        // 1. Lấy topic_id từ tham số 'from_topic' truyền lên (nếu có)
        $fromTopicId = $request->query('from_topic');

        try {
            $topicContent->delete();

            // 2. Kiểm tra: Nếu có truyền topic_id thì quay về trang show của Topic đó
            if ($fromTopicId) {
                return redirect()->route('topics.show', $fromTopicId)
                    ->with('success', 'Đã xóa nội dung khỏi chuyên đề.');
            }

            // 3. Nếu không có, mặc định quay về danh sách TopicContent
            return redirect()->route('topic-contents.index')
                ->with('success', 'Xóa nội dung thành công!');

        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: '.$e->getMessage());
        }
    }

    // 1. Form chọn file
    public function importForm()
    {
        return view('topic_contents.import');
    }

    // 2. Preview và Validate
    public function importPreview(Request $request, ExcelService $excelService)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        $path = $request->file('file')->getRealPath();
        $data = json_decode($excelService->convertToJson($path), true);

        if (! $data) {
            return back()->with('error', 'Không thể đọc file Excel.');
        }

        $previewData = [];
        $importId = Str::uuid()->toString();

        foreach ($data as $row) {
            $tCode = $row['topic_code'] ?? '';
            $cCode = $row['code'] ?? '';
            $cName = $row['name'] ?? '';

            $errors = [];
            $status = 'OK';

            $topic = Topic::where('code', $tCode)->first();
            if (! $topic) {
                $errors[] = "Mã chuyên đề [$tCode] không tồn tại";
                $status = 'ERROR';
            }
            if (empty($cName)) {
                $errors[] = 'Tên nội dung trống';
                $status = 'ERROR';
            }

            if ($status !== 'ERROR') {
                if (TopicContent::where('code', $cCode)->exists()) {
                    $errors[] = "Mã [$cCode] đã tồn tại - Sẽ tự động tạo mã phụ (#uuid)";
                    $status = 'WARNING';
                }
            }

            $previewData[] = [
                'topic_id' => $topic->id ?? null,
                'topic_code' => $tCode,
                'code' => $cCode,
                'name' => $cName,
                'status' => $status,
                'message' => implode(', ', $errors) ?: 'Hợp lệ',
            ];
        }

        Cache::put('import_tc_'.$importId, $previewData, now()->addMinutes(60));

        // Truyền biến là 'data' để đồng bộ với view preview.blade.php
        return view('topic_contents.preview', ['data' => $previewData, 'importId' => $importId]);
    }

    // 3. Lưu vào Database (Xử lý UUID và không ghi đè)
    public function importSave(Request $request)
    {
        $importId = $request->import_id;
        $data = Cache::get('import_tc_'.$importId);

        if (! $data) {
            return redirect()->route('topic-contents.import.form')->with('error', 'Hết hạn phiên làm việc.');
        }

        $count = 0;
        foreach ($data as $item) {
            if ($item['status'] !== 'ERROR') {
                $finalCode = $item['code'];

                if (TopicContent::where('code', $finalCode)->exists()) {
                    $base = mb_strlen($finalCode) > 12 ? mb_substr($finalCode, 0, 12) : $finalCode;
                    $finalCode = $base.'#'.Str::uuid()->toString();
                }

                TopicContent::create([
                    'topic_id' => $item['topic_id'],
                    'code' => $finalCode,
                    'name' => $item['name'],
                ]);
                $count++;
            }
        }

        Cache::forget('import_tc_'.$importId);

        return redirect()->route('topic-contents.index')->with('success', "Đã nhập thành công $count nội dung.");
    }

    public function export(Request $request, ExcelService $excelService)
    {
        // Copy logic query từ hàm index để đồng nhất bộ lọc
        $query = TopicContent::query()
            ->select('topic_contents.*')
            ->join('topics', 'topic_contents.topic_id', '=', 'topics.id')
            ->join('grades', 'topics.grade_id', '=', 'grades.id')
            ->join('topic_types', 'topics.topic_type_id', '=', 'topic_types.id')
            ->with(['topic.grade', 'topic.topicType']);

        // Áp dụng các bộ lọc hiện có
        if ($request->filled('content_code')) {
            $query->where('topic_contents.code', 'like', '%'.$request->content_code.'%');
        }
        if ($request->filled('topic_code')) {
            $query->where('topics.code', 'like', '%'.$request->topic_code.'%');
        }
        if ($request->filled('grade_id')) {
            $query->where('topics.grade_id', $request->grade_id);
        }

        // Lấy toàn bộ dữ liệu (không phân trang) để xuất file
        $contents = $query->get();

        return $excelService->exportTopicContents($contents);
    }
}
