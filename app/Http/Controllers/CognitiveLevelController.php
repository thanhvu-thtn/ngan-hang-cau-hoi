<?php

namespace App\Http\Controllers;

use App\Models\CognitiveLevel;
use Illuminate\Http\Request;

class CognitiveLevelController extends Controller
{
    public function index()
    {
        // Vì trong Model chúng ta đã thiết lập Global Scope sắp xếp theo order_index,
        // nên ở đây chỉ cần gọi hàm lấy dữ liệu là danh sách sẽ tự động chuẩn.
        $levels = CognitiveLevel::all(); 
        
        return view('cognitive_levels.index', compact('levels'));
    }

    public function create()
    {
        return view('cognitive_levels.create');
    }

    public function store(Request $request)
    {
        // Validate dữ liệu gửi lên
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:cognitive_levels,code',
            'order_index' => 'required|integer',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên mức độ nhận thức.',
            'code.required' => 'Vui lòng nhập mã định danh.',
            'code.unique'   => 'Mã định danh này đã tồn tại.',
            'order_index.required' => 'Vui lòng nhập số thứ tự sắp xếp.',
        ]);

        CognitiveLevel::create($validated);

        return redirect()->route('cognitive-levels.index')
                         ->with('success', 'Đã thêm mức độ nhận thức thành công!');
    }

    public function edit(CognitiveLevel $cognitiveLevel)
    {
        return view('cognitive_levels.edit', compact('cognitiveLevel'));
    }

    public function update(Request $request, CognitiveLevel $cognitiveLevel)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            // Bỏ qua ID hiện tại khi check unique
            'code'        => 'required|string|max:50|unique:cognitive_levels,code,' . $cognitiveLevel->id,
            'order_index' => 'required|integer',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Vui lòng nhập tên mức độ nhận thức.',
            'code.required' => 'Vui lòng nhập mã định danh.',
            'code.unique'   => 'Mã định danh này đã tồn tại.',
            'order_index.required' => 'Vui lòng nhập số thứ tự sắp xếp.',
        ]);

        $cognitiveLevel->update($validated);

        return redirect()->route('cognitive-levels.index')
                         ->with('success', 'Cập nhật mức độ nhận thức thành công!');
    }

    public function destroy(CognitiveLevel $cognitiveLevel)
    {
        // Tùy chọn: Sau này nếu có liên kết với bảng objectives, 
        // bạn có thể thêm logic kiểm tra xem mức độ này có đang được sử dụng không trước khi xóa.
        
        $cognitiveLevel->delete();

        return redirect()->route('cognitive-levels.index')
                         ->with('success', 'Đã xóa mức độ nhận thức!');
    }
}