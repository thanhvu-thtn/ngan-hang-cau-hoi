<?php

namespace App\Http\Controllers;

use App\Models\QuestionType;
use Illuminate\Http\Request;

class QuestionTypeController extends Controller
{
    public function index()
    {
        $questionTypes = QuestionType::orderBy('id', 'asc')->paginate(10);
        return view('question_types.index', compact('questionTypes'));
    }

    public function create()
    {
        return view('question_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:question_types,code',
            'num_choices' => 'required|integer|min:0|max:10',
        ], [
            'name.required' => 'Vui lòng nhập tên loại câu hỏi.',
            'code.required' => 'Vui lòng nhập mã nhận diện.',
            'code.unique' => 'Mã này đã tồn tại.',
            'num_choices.required' => 'Vui lòng nhập số lượng phương án.',
        ]);

        QuestionType::create($request->all());

        return redirect()->route('question-types.index')->with('success', 'Đã thêm loại câu hỏi thành công!');
    }

    public function edit(QuestionType $questionType)
    {
        return view('question_types.edit', compact('questionType'));
    }

    public function update(Request $request, QuestionType $questionType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:question_types,code,' . $questionType->id,
            'num_choices' => 'required|integer|min:0|max:10',
        ]);

        $questionType->update($request->all());

        return redirect()->route('question-types.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy(QuestionType $questionType)
    {
        $questionType->delete();
        return redirect()->route('question-types.index')->with('success', 'Đã xóa loại câu hỏi.');
    }
}