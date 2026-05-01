<?php

namespace App\Http\Controllers;

use App\Actions\Questions\EssayAction;
use App\Actions\Questions\MultipleChoiceAction;
// Import các Actions
use App\Actions\Questions\ShortAnswerAction;
use App\Actions\Questions\TrueFalseAction;
use App\Http\Requests\QuestionRequest;
use App\Models\CognitiveLevel;
use App\Models\Grade;
// 1. Thêm 2 thư viện này để dùng Middleware trong Controller của Laravel 11
use App\Models\Question;
use App\Models\QuestionLayout;
use App\Models\QuestionStatus;
use App\Models\QuestionType;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class QuestionController extends Controller
{
    /**
     * 3. Khai báo Middleware theo chuẩn mới (thay thế cho __construct)
     */
    public static function middleware(): array
    {
        return [
            // Áp dụng quyền 'create-questions' cho create và store
            new Middleware('can:create-questions', only: ['index', 'create', 'store']),

            // Gợi ý cho tương lai:
            // new Middleware('can:edit-questions', only: ['edit', 'update']),
            // new Middleware('can:delete-questions', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rawGrades = Grade::with([
            'topics' => function ($query) {
                $query->orderBy('code')->with([
                    'topicType', // Thường topicType không cần sort vì là quan hệ 1-1
                    'contents' => function ($query) {
                        $query->orderBy('code')->with([
                            'objectives' => function ($query) {
                                $query->orderBy('code');
                            },
                        ]);
                    },
                ]);
            },
        ])->orderBy('code')->get();

        // 2. Format dữ liệu thành mảng Tree chuẩn cho View
        $treeData = $this->getTreeData();

        // 2. Lấy danh sách câu hỏi cơ bản
        // SAU
        $query = Question::with(['type', 'cognitiveLevel', 'status', 'sharedContext'])->latest();

        // 3a. XỬ LÝ LỌC: Theo mục tiêu (Đã làm ở bước trước)
        if ($request->has('objective_ids') && is_array($request->objective_ids) && count($request->objective_ids) > 0) {
            $query->whereHas('objectives', function ($q) use ($request) {
                $q->whereIn('objectives.id', $request->objective_ids);
            });
        }

        // 3b. XỬ LÝ LỌC: Theo mã câu hỏi (MỚI THÊM)
        if ($request->filled('search_code')) {
            // Dùng LIKE để tìm kiếm chuỗi có chứa từ khóa
            $query->where('code', 'like', '%'.$request->search_code.'%');
        }

        // 4. Phân trang (withQueryString để giữ lại TẤT CẢ các bộ lọc khi chuyển trang)
        $questions = $query->paginate(15)->withQueryString();

        // 5. Truyền ra view
        return view('questions.index', compact('questions', 'treeData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // 1. Lấy thông tin điều hướng (để sau này store xong thì quay lại đúng chỗ)
        $navigation = [
            'source' => $request->query('source', 'index'),
            'search_code' => $request->query('search_code'),
            'objective_ids' => $request->query('objective_ids', []),
            'shared_context_id' => $request->query('shared_context_id'),
        ];

        // 2. Lấy dữ liệu cho các dropdowns
        $types = QuestionType::all();
        $levels = CognitiveLevel::all();
        $statuses = QuestionStatus::all();
        $layouts = QuestionLayout::all();

        // 3. Chuẩn bị dữ liệu Treeview cho Objective Selector
        // Giả sử bạn dùng lại logic treeData từ hàm index
        $treeData = $this->getTreeData();

        // Lấy riêng ID của trạng thái PENDING
        $pendingStatus = QuestionStatus::where('code', 'PENDING')->first();

        // Thêm $pendingStatus vào compact
        return view('questions.create', compact('navigation', 'types', 'levels', 'statuses', 'layouts', 'treeData', 'pendingStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionRequest $request)
    {
        // 1. Lấy dữ liệu đã validate thành công
        // dd($request->all());
        $validatedData = $request->validated();
        // dd($validatedData);
        // 4. Gọi Action xử lý
        $type = QuestionType::findOrFail($validatedData['question_type_id']);
        $typeCode = $type->code;
        $action = $this->getActionHandler($typeCode);
        $question = $action->execute($validatedData);

        // 5. Điều hướng theo navigation
        $navSource = $request->input('nav_source', 'index');
        $navSharedContextId = $request->input('nav_shared_context_id');

        // Nếu được gọi từ shared-contexts.show thì quay về đó
        if ($navSource === 'shared_context' && $navSharedContextId) {
            return redirect()->route('shared-contexts.show', $navSharedContextId)
                ->with('success', 'Đã thêm câu hỏi mới thành công!');
        }

        // Mặc định: quay về questions.index (giữ nguyên bộ lọc)
        $queryParams = array_filter([
            'search_code' => $request->input('nav_search_code'),
            'objective_ids' => $request->input('nav_objective_ids', []),
        ]);

        return redirect()->route('questions.index', $queryParams)
            ->with('success', 'Đã thêm câu hỏi mới thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question, Request $request)
    {
        $question->load(['type', 'cognitiveLevel', 'status', 'objectives', 'choices', 'layout']);

        $navigation = [
            'source' => $request->query('source', 'index'),
            'shared_context_id' => $request->query('shared_context_id'),
        ];

        return view('questions.show', compact('question', 'navigation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question, Request $request)
    {
        // 1. Load đầy đủ các quan hệ cần thiết
        $question->load(['type', 'cognitiveLevel', 'status', 'layout', 'objectives', 'choices' => function ($q) {
            $q->orderBy('order_index');
        }]);

        // 2. Lấy thông tin điều hướng (để sau khi update/hủy thì quay lại đúng chỗ)
        // Ưu tiên: nếu validation fail thì old() giữ lại, nếu không thì lấy từ query string
        $navigation = [
            'source' => $request->query('source', 'show'),
            'shared_context_id' => $request->query('shared_context_id', $question->shared_context_id),
        ];

        // 3. Dữ liệu cho các dropdown
        $types = QuestionType::all();
        $levels = CognitiveLevel::all();
        $statuses = QuestionStatus::all();
        $layouts = QuestionLayout::all();

        // 4. Treeview objectives
        $treeData = $this->getTreeData();

        // 5. Danh sách ID objective đã gắn (để pre-check trong modal)
        $selectedObjectiveIds = $question->objectives->pluck('id')->toArray();

        return view('questions.edit', compact(
            'question',
            'navigation',
            'types',
            'levels',
            'statuses',
            'layouts',
            'treeData',
            'selectedObjectiveIds'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $validatedData = $request->validated();

        // Kiểm tra quyền thay đổi trạng thái
        $newStatusId = (int) $validatedData['question_status_id'];
        if ($newStatusId !== (int) $question->question_status_id) {
            // Nếu trạng thái thay đổi mà user không có quyền -> từ chối
            if (! $request->user()->can('approve-questions')) {
                abort(403, 'Bạn không có quyền thay đổi trạng thái câu hỏi.');
            }
            // Có quyền và trạng thái thay đổi -> ghi lại reviewer
            $validatedData['reviewer_id'] = $request->user()->id;
        }

        // Xác định Action Handler theo loại câu hỏi
        $type = QuestionType::findOrFail($validatedData['question_type_id']);
        $typeCode = $type->code;
        $action = $this->getActionHandler($typeCode);

        // Truyền $question vào execute() để Action biết đây là UPDATE
        $action->execute($validatedData, $question);

        // Điều hướng sau khi update thành công
        $navSource = $request->input('nav_source', 'show');
        $navSharedContextId = $request->input('nav_shared_context_id');

        if ($navSource === 'shared_context' && $navSharedContextId) {
            return redirect()->route('shared-contexts.show', $navSharedContextId)
                ->with('success', 'Đã cập nhật câu hỏi thành công!');
        }

        return redirect()->route('questions.show', $question)
            ->with('success', 'Đã cập nhật câu hỏi thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Question $question)
    {
        // 1. Kiểm tra quyền
        if (! $request->user()->can('approve-questions')) {
            abort(403, 'Bạn không đủ thẩm quyền xoá.');
        }

        $source = $request->input('source', 'index');

        // 2. Gọi từ questions.index → chỉ xoá được câu hỏi KHÔNG có shared_context
        if ($source === 'index' && ! is_null($question->shared_context_id)) {
            return back()->with('error', "Câu hỏi {$question->code} thuộc dữ liệu dùng chung, không thể xoá từ đây.");
        }

        // 3. Gọi từ shared-contexts.show → shared_context_id phải khớp
        if ($source === 'shared_context') {
            $sentSharedContextId = (int) $request->input('shared_context_id');
            if ((int) $question->shared_context_id !== $sentSharedContextId) {
                abort(403, 'Dữ liệu dùng chung không khớp, không thể xoá.');
            }
        }

        // 4. Xoá ảnh trong nội dung HTML trước khi xoá bản ghi
        /** @var ImageService $imageService */
        $imageService = app(ImageService::class);

        // Quét stem của câu hỏi
        $imageService->deleteImagesFromHtml($question->stem);

        // Nếu là MC thì quét thêm content của các choices
        if ($question->type?->code === 'MC') {
            $question->load('choices');
            foreach ($question->choices as $choice) {
                $imageService->deleteImagesFromHtml($choice->content);
            }
        }

        // 5. Xoá bản ghi
        $code = $question->code;
        $question->delete();

        // 6. Điều hướng sau xoá
        if ($source === 'shared_context' && $request->input('shared_context_id')) {
            return redirect()->route('shared-contexts.show', $request->input('shared_context_id'))
                ->with('success', "Đã xóa câu hỏi {$code} thành công.");
        }

        return redirect()->route('questions.index')
            ->with('success', "Đã xóa câu hỏi {$code} thành công.");
    }

    /**
     * Helper method: Hàm nội bộ để tự động chọn đúng Action dựa vào mã loại câu hỏi (Code)
     */
    private function getActionHandler(string $typeCode)
    {
        return match ($typeCode) {
            'MC' => app(MultipleChoiceAction::class),
            'TF' => app(TrueFalseAction::class),
            'SA' => app(ShortAnswerAction::class),
            'ES' => app(EssayAction::class),
            default => abort(400, "Loại câu hỏi không được hỗ trợ: {$typeCode}"),
        };
    }

    /**
     * Hàm dùng chung để lấy dữ liệu cây Mục tiêu (Treeview)
     */
    private function getTreeData(): array
    {
        // 1. Query lấy toàn bộ dữ liệu 4 cấp (đã được sắp xếp theo code)
        $rawGrades = Grade::with([
            'topics' => function ($query) {
                $query->orderBy('code')->with([
                    'topicType',
                    'contents' => function ($query) {
                        $query->orderBy('code')->with([
                            'objectives' => function ($query) {
                                $query->orderBy('code');
                            },
                        ]);
                    },
                ]);
            },
        ])->orderBy('code')->get();

        // 2. Chuyển đổi dữ liệu thành mảng dạng Cây (Tree) cho Frontend
        $treeData = [];
        foreach ($rawGrades as $grade) {
            $gradeNode = [
                'id' => 'grade_'.$grade->id,
                'label' => $grade->name,
                'is_leaf' => false,
                'children' => [],
            ];

            foreach ($grade->topics as $topic) {
                $topicNode = [
                    'id' => 'topic_'.$topic->id,
                    'label' => $topic->name,
                    'is_leaf' => false,
                    'children' => [],
                ];

                foreach ($topic->contents as $content) {
                    $contentNode = [
                        'id' => 'content_'.$content->id,
                        'label' => $content->name,
                        'is_leaf' => false,
                        'children' => [],
                    ];

                    foreach ($content->objectives as $objective) {
                        $contentNode['children'][] = [
                            'id' => $objective->id, // ID thật của objective (Quan trọng nhất)
                            'label' => $objective->description ?? $objective->code,
                            'is_leaf' => true,
                        ];
                    }

                    // Chỉ thêm node nếu có con (Loại bỏ các nhánh rỗng)
                    if (! empty($contentNode['children'])) {
                        $topicNode['children'][] = $contentNode;
                    }
                }

                if (! empty($topicNode['children'])) {
                    $gradeNode['children'][] = $topicNode;
                }
            }

            if (! empty($gradeNode['children'])) {
                $treeData[] = $gradeNode;
            }
        }

        return $treeData;
    }
}
