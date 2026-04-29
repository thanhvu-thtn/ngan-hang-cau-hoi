<?php

namespace App\Http\Controllers;

use App\Models\QuestionStatus;
use Illuminate\Http\Request;

class QuestionStatusController extends Controller
{
    public function index()
    {
        $statuses = QuestionStatus::all();
        return view('question_statuses.index', compact('statuses'));
    }

    public function create()
    {
        return view('question_statuses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:question_statuses,code',
            'order_index' => 'required|integer',
            'color'       => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        QuestionStatus::create($validated);

        return redirect()->route('question-statuses.index')
                         ->with('success', 'Đã thêm trạng thái mới thành công!');
    }

    public function edit(QuestionStatus $questionStatus)
    {
        return view('question_statuses.edit', compact('questionStatus'));
    }

    public function update(Request $request, QuestionStatus $questionStatus)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:question_statuses,code,' . $questionStatus->id,
            'order_index' => 'required|integer',
            'color'       => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $questionStatus->update($validated);

        return redirect()->route('question-statuses.index')
                         ->with('success', 'Cập nhật trạng thái thành công!');
    }

    public function destroy(QuestionStatus $questionStatus)
    {
        $questionStatus->delete();
        return redirect()->route('question-statuses.index')
                         ->with('success', 'Đã xóa trạng thái!');
    }
}