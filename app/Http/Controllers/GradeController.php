<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        // Lấy danh sách khối lớp, sắp xếp mới nhất lên đầu
        $grades = Grade::latest()->paginate(10);
        return view('grades.index', compact('grades'));
    }

    public function create()
    {
        return view('grades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:grades,code',
        ], [
            'name.required' => 'Vui lòng nhập tên khối lớp.',
            'code.required' => 'Vui lòng nhập mã nhận diện.',
            'code.unique' => 'Mã nhận diện này đã tồn tại, vui lòng chọn mã khác.',
        ]);

        Grade::create($request->all());

        return redirect()->route('grades.index')->with('success', 'Đã thêm khối lớp thành công!');
    }

    public function edit(Grade $grade)
    {
        return view('grades.edit', compact('grade'));
    }

    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Bỏ qua check unique cho chính bản ghi đang sửa
            'code' => 'required|string|max:50|unique:grades,code,' . $grade->id, 
        ], [
            'name.required' => 'Vui lòng nhập tên khối lớp.',
            'code.required' => 'Vui lòng nhập mã nhận diện.',
            'code.unique' => 'Mã nhận diện này đã tồn tại.',
        ]);

        $grade->update($request->all());

        return redirect()->route('grades.index')->with('success', 'Cập nhật khối lớp thành công!');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grades.index')->with('success', 'Đã xóa khối lớp khỏi hệ thống.');
    }
}