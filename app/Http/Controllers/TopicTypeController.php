<?php

namespace App\Http\Controllers;

use App\Models\TopicType;
use Illuminate\Http\Request;

class TopicTypeController extends Controller
{
    public function index()
    {
        $topicTypes = TopicType::latest()->paginate(10);
        return view('topic_types.index', compact('topicTypes'));
    }

    public function create()
    {
        return view('topic_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:topic_types,code',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên kiểu chuyên đề.',
            'code.required' => 'Vui lòng nhập mã nhận diện.',
            'code.unique' => 'Mã này đã tồn tại.',
        ]);

        TopicType::create($request->all());

        return redirect()->route('topic-types.index')->with('success', 'Đã thêm kiểu chuyên đề thành công!');
    }

    public function edit(TopicType $topicType)
    {
        return view('topic_types.edit', compact('topicType'));
    }

    public function update(Request $request, TopicType $topicType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:topic_types,code,' . $topicType->id,
            'description' => 'nullable|string',
        ]);

        $topicType->update($request->all());

        return redirect()->route('topic-types.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy(TopicType $topicType)
    {
        $topicType->delete();
        return redirect()->route('topic-types.index')->with('success', 'Đã xóa kiểu chuyên đề.');
    }
}