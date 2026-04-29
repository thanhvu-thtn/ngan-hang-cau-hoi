<?php

namespace App\Http\Controllers;

use App\Models\QuestionLayout;
use Illuminate\Http\Request;

class QuestionLayoutController extends Controller
{
    public function index()
    {
        $layouts = QuestionLayout::all();
        return view('question_layouts.index', compact('layouts'));
    }

    public function create()
    {
        return view('question_layouts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:question_layouts,code',
            'order_index' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        QuestionLayout::create($validated);

        return redirect()->route('question-layouts.index')
                         ->with('success', 'Đã thêm bố cục mới thành công!');
    }

    public function edit(QuestionLayout $questionLayout)
    {
        return view('question_layouts.edit', compact('questionLayout'));
    }

    public function update(Request $request, QuestionLayout $questionLayout)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:question_layouts,code,' . $questionLayout->id,
            'order_index' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        $questionLayout->update($validated);

        return redirect()->route('question-layouts.index')
                         ->with('success', 'Cập nhật bố cục thành công!');
    }

    public function destroy(QuestionLayout $questionLayout)
    {
        $questionLayout->delete();
        return redirect()->route('question-layouts.index')
                         ->with('success', 'Đã xóa bố cục!');
    }
}