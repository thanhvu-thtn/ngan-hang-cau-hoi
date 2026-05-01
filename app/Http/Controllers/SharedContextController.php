<?php

namespace App\Http\Controllers;

use App\Models\SharedContext;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class SharedContextController extends Controller
{
    /**
     * Khai báo Middleware theo chuẩn Laravel 11
     */
    public static function middleware(): array
    {
        return [
            new Middleware('can:create-questions', only: ['index', 'create', 'store']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SharedContext::latest();

        // Lọc theo mã
        if ($request->filled('search_code')) {
            $query->where('code', 'like', '%'.$request->search_code.'%');
        }

        $sharedContexts = $query->paginate(15)->withQueryString();

        return view('shared-contexts.index', compact('sharedContexts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shared-contexts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ImageService $imageService)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:shared_contexts,code',
            'description' => 'nullable|string|max:255',
            'content' => 'required|string',
        ]);

        // Xử lý ảnh base64 / URL ngoài → chuyển thành ảnh nội bộ trong storage
        $validated['content'] = $imageService->processHtmlContent($validated['content']);

        SharedContext::create($validated);

        return redirect()->route('shared-contexts.index')
            ->with('success', 'Đã thêm dữ liệu dùng chung mới thành công!');
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     */
    public function show(SharedContext $sharedContext)
    {
        // Load danh sách câu hỏi kèm các relation cần hiển thị trong bảng
        $sharedContext->load([
            'questions' => function ($query) {
                $query->with(['type', 'cognitiveLevel', 'status'])
                    ->orderBy('code');
            },
        ]);

        return view('shared-contexts.show', compact('sharedContext'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SharedContext $sharedContext)
    {
        return view('shared-contexts.edit', compact('sharedContext'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SharedContext $sharedContext, ImageService $imageService)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:shared_contexts,code,'.$sharedContext->id,
            'description' => 'nullable|string|max:255',
            'content' => 'required|string',
        ]);

        // Xử lý ảnh base64 / URL ngoài → chuyển thành ảnh nội bộ (giống store)
        $validated['content'] = $imageService->processHtmlContent($validated['content']);

        $sharedContext->update($validated);

        return redirect()->route('shared-contexts.show', $sharedContext)
            ->with('success', 'Đã cập nhật dữ liệu dùng chung thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, SharedContext $sharedContext)
    {
        // 1. Kiểm tra quyền
        if (! $request->user()->can('approve-questions')) {
            return back()->with('error', 'Bạn không đủ thẩm quyền xoá.');
        }

        // 2. Kiểm tra còn câu hỏi không
        $questionCount = $sharedContext->questions()->count();
        if ($questionCount > 0) {
            return back()->with('error',
                "Dữ liệu dùng chung mã số {$sharedContext->code} đang có {$questionCount} câu hỏi nên không thể xoá. ".
                'Nếu muốn xoá bạn phải xoá hết câu hỏi của nó trước.'
            );
        }

        // 3. Xoá
        $code = $sharedContext->code;
        $sharedContext->delete();

        return redirect()->route('shared-contexts.index')
            ->with('success', "Đã xóa dữ liệu dùng chung {$code} thành công.");
    }
}
