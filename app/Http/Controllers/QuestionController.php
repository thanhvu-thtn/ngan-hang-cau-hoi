<?php

namespace App\Http\Controllers;

use App\Actions\Questions\EssayAction;
use App\Actions\Questions\MultipleChoiceAction;
// Import các Actions
use App\Actions\Questions\ShortAnswerAction;
use App\Actions\Questions\TrueFalseAction;
use App\Models\Grade;
use App\Models\Question;
use Illuminate\Http\Request;
// 1. Thêm 2 thư viện này để dùng Middleware trong Controller của Laravel 11
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
    public function index()
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
        $treeData = [];

        foreach ($rawGrades as $grade) {
            $topicTypes = [];

            // Nhóm các topics của khối này theo topic_type_id bằng collection
            $groupedTopics = $grade->topics->groupBy('topic_type_id');

            foreach ($groupedTopics as $topicsGroup) {
                $topicTypeModel = $topicsGroup->first()->topicType;
                $topics = [];

                foreach ($topicsGroup as $topic) {
                    $contents = [];

                    foreach ($topic->contents as $content) {
                        $objectives = [];

                        // CẤP 5: OBJECTIVES (Node lá, được phép gửi ID về server)
                        foreach ($content->objectives as $objective) {
                            $objectives[] = [
                                'id' => $objective->id,
                                'label' => $objective->code.' - '.$objective->description,
                                'is_leaf' => true, // Đánh dấu đây là cấp cuối cùng
                                'children' => [],
                            ];
                        }

                        // CẤP 4: CONTENTS
                        $contents[] = [
                            'id' => 'content_'.$content->id,
                            'label' => $content->name,
                            'is_leaf' => false,
                            'children' => $objectives,
                        ];
                    }

                    // CẤP 3: TOPICS
                    $topics[] = [
                        'id' => 'topic_'.$topic->id,
                        'label' => $topic->name,
                        'is_leaf' => false,
                        'children' => $contents,
                    ];
                }

                // CẤP 2: TOPIC TYPES (Đại số, Hình học...)
                $topicTypes[] = [
                    'id' => 'type_'.$topicTypeModel->id,
                    'label' => $topicTypeModel->name,
                    'is_leaf' => false,
                    'children' => $topics,
                ];
            }

            // CẤP 1: GRADES (Khối 10, 11, 12)
            $treeData[] = [
                'id' => 'grade_'.$grade->id,
                'label' => $grade->name,
                'is_leaf' => false,
                'children' => $topicTypes,
            ];
        }

        $questions = Question::with(['questionType', 'cognitiveLevel', 'questionStatus'])->latest()->paginate(15);

        // 3. Truyền mảng Tree chuẩn ra view
        return view('questions.index', compact('questions', 'treeData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        //
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
}
