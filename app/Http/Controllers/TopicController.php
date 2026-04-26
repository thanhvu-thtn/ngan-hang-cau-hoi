<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Topic;
use App\Models\TopicType;
use App\Services\ExcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // Để dùng Redis/Cache
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        // Sử dụng join để có thể sắp xếp theo cột của bảng liên quan
        $query = Topic::query()
            ->select('topics.*') // Chỉ lấy các cột của bảng topics
            ->join('grades', 'topics.grade_id', '=', 'grades.id')
            ->join('topic_types', 'topics.topic_type_id', '=', 'topic_types.id')
            ->with(['grade', 'topicType']); // Vẫn eager load để hiển thị ở view

        // 1. Lọc theo Mã chuyên đề (Cần thêm tiền tố topics.)
        if ($request->filled('code')) {
            // Sửa 'code' thành 'topics.code'
            $query->where('topics.code', 'like', '%'.$request->code.'%');
        }

        // 2. Lọc chính xác theo Khối lớp
        if ($request->filled('grade_id')) {
            // Sửa 'grade_id' thành 'topics.grade_id'
            $query->where('topics.grade_id', $request->grade_id);
        }

        // 3. Lọc chính xác theo Loại chuyên đề
        if ($request->filled('topic_type_id')) {
            // Sửa 'topic_type_id' thành 'topics.topic_type_id'
            $query->where('topics.topic_type_id', $request->topic_type_id);
        }
        // Thực hiện phân trang và giữ lại các tham số lọc trên URL
        $topics = $query->orderBy('topics.code', 'asc')
            ->orderBy('grades.code', 'asc')
            ->orderBy('topic_types.code', 'asc')
            ->paginate(15)
            ->withQueryString();

        // Lấy danh sách để đổ vào các thẻ <select> trong form lọc
        $grades = Grade::all();
        $topicTypes = TopicType::all();

        return view('topics.index', compact('topics', 'grades', 'topicTypes'));
    }

    public function create()
    {
        $grades = Grade::all();
        $topicTypes = TopicType::all();

        return view('topics.create', compact('grades', 'topicTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:topics,code',
            'grade_id' => 'required|exists:grades,id',
            'topic_type_id' => 'required|exists:topic_types,id',
        ]);

        Topic::create($request->all());

        return redirect()->route('topics.index')->with('success', 'Thêm chuyên đề thành công!');
    }

    public function edit(Topic $topic)
    {
        $grades = Grade::all();
        $topicTypes = TopicType::all();

        return view('topics.edit', compact('topic', 'grades', 'topicTypes'));
    }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:topics,code,'.$topic->id,
            'grade_id' => 'required|exists:grades,id',
            'topic_type_id' => 'required|exists:topic_types,id',
        ]);

        $topic->update($request->all());

        return redirect()->route('topics.index')->with('success', 'Cập nhật chuyên đề thành công!');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();

        return redirect()->route('topics.index')->with('success', 'Đã xóa chuyên đề.');
    }

    public function importForm()
    {
        return view('topics.import');
    }

    /**
     * Xử lý file Excel được tải lên
     */
    public function importExcel(Request $request, ExcelService $excelService)
    {
        $request->validate(['excel_file' => 'required|file|mimes:xlsx,xls']);

        // 1. Chuyển Excel thành mảng JSON qua Service
        $jsonResult = $excelService->convertToJson($request->file('excel_file')->getPathname());
        $data = json_decode($jsonResult, true);

        // 2. CHỐNG N+1 QUERY: Load sẵn dữ liệu để so sánh trong RAM
        $existingCodes = Topic::pluck('code')->toArray();
        $grades = Grade::pluck('id', 'code')->toArray(); // ['K10' => 1, 'K11' => 2...]
        $topicTypes = TopicType::pluck('id', 'code')->toArray();

        $previewData = [];
        $importId = Str::uuid()->toString(); // Tạo ID duy nhất cho phiên làm việc này

        foreach ($data as $row) {
            $errors = [];
            $status = 'OK';
            $finalCode = $row['code'] ?? '';

            // Kiểm tra Grade Code
            if (! isset($grades[$row['grade_code']])) {
                $errors[] = "Mã khối lớp '{$row['grade_code']}' không tồn tại.";
                $status = 'Error';
            }

            // Kiểm tra Topic Type Code
            if (! isset($topicTypes[$row['topic_type_code']])) {
                $errors[] = "Mã kiểu chuyên đề '{$row['topic_type_code']}' không tồn tại.";
                $status = 'Error';
            }

            // Kiểm tra Name
            if (empty($row['name'])) {
                $errors[] = 'Tên chuyên đề không được để trống.';
                $status = 'Error';
            }

            // Kiểm tra Trùng Code
            if (in_array($row['code'], $existingCodes)) {
                $suffix = Str::random(4); // Tạo hậu tố ngắn 4 ký tự
                $finalCode = $row['code'].'-'.$suffix;
                $errors[] = "Mã '{$row['code']}' đã tồn tại. Hệ thống sẽ đổi thành '{$finalCode}'.";
                // Lưu ý: Trường hợp này $status vẫn là 'OK' vì chúng ta chấp nhận xử lý được
            }

            $previewData[] = [
                'original_code' => $row['code'],
                'final_code' => $finalCode,
                'name' => $row['name'],
                'grade_code' => $row['grade_code'],
                'grade_id' => $grades[$row['grade_code']] ?? null,
                'topic_type_code' => $row['topic_type_code'],
                'topic_type_id' => $topicTypes[$row['topic_type_code']] ?? null,
                'status' => $status,
                'message' => implode(' | ', $errors),
            ];
        }

        // 3. LƯU VÀO REDIS: Lưu trong 60 phút để người dùng kịp nhấn nút Ghi
        Cache::put("import_{$importId}", $previewData, 3600);

        return view('topics.preview', [
            'data' => $previewData,
            'importId' => $importId,
        ]);
    }

    public function importSave(Request $request)
    {
        $importId = $request->input('import_id');

        // 1. Lấy dữ liệu từ "kho tạm" Redis
        $data = Cache::get("import_{$importId}");

        if (! $data) {
            return redirect()->route('topics.import.form')
                ->with('error', 'Phiên làm việc đã hết hạn hoặc không tìm thấy dữ liệu. Vui lòng thử lại.');
        }

        // 2. Lọc ra những dòng có trạng thái OK để lưu
        $validData = collect($data)->where('status', 'OK');

        if ($validData->isEmpty()) {
            return redirect()->route('topics.import.form')
                ->with('error', 'Không có dữ liệu hợp lệ để lưu.');
        }

        try {
            // 3. Dùng Transaction để đảm bảo: hoặc lưu hết, hoặc không lưu gì nếu lỗi
            DB::transaction(function () use ($validData) {
                foreach ($validData as $item) {
                    Topic::create([
                        'code' => $item['final_code'], // Dùng mã cuối cùng (đã xử lý trùng nếu có)
                        'name' => $item['name'],
                        'grade_id' => $item['grade_id'],
                        'topic_type_id' => $item['topic_type_id'],
                    ]);
                }
            });

            // 4. Xóa dữ liệu trong Redis sau khi đã lưu xong cho sạch bộ nhớ
            Cache::forget("import_{$importId}");

            return redirect()->route('topics.index')
                ->with('success', 'Đã nhập thành công '.$validData->count().' chuyên đề từ Excel!');

        } catch (\Exception $e) {
            return redirect()->route('topics.import.form')
                ->with('error', 'Có lỗi xảy ra trong quá trình lưu dữ liệu: '.$e->getMessage());
        }
    }

    public function export(Request $request, ExcelService $excelService)
    {
        $query = Topic::query()
            ->select('topics.*')
            ->join('grades', 'topics.grade_id', '=', 'grades.id')
            ->join('topic_types', 'topics.topic_type_id', '=', 'topic_types.id')
            ->with(['grade', 'topicType']); // Dùng with để tối ưu lấy tên Khối và Loại

        // Giữ nguyên các bộ lọc
        if ($request->filled('code')) {
            $query->where('topics.code', 'like', '%' . $request->code . '%');
        }
        if ($request->filled('grade_id')) {
            $query->where('topics.grade_id', $request->grade_id);
        }
        if ($request->filled('topic_type_id')) {
            $query->where('topics.topic_type_id', $request->topic_type_id);
        }

        // Giữ nguyên sắp xếp
        $query->orderBy('topics.code', 'asc')
              ->orderBy('grades.code', 'asc')
              ->orderBy('topic_types.code', 'asc');

        // Dùng get() để lấy TOÀN BỘ dữ liệu thỏa mãn điều kiện (không dùng paginate khi xuất file)
        $topics = $query->get();

        // Giao việc tạo và tải file Excel cho Service lo!
        return $excelService->exportTopics($topics);
    }
}
