<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::latest()->paginate(10);

        return view('system_settings.index', compact('settings'));
    }

    public function create()
    {
        return view('system_settings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:system_settings,key|max:255',
            'value' => 'nullable|string',
        ], [
            'key.required' => 'Vui lòng nhập tên biến (Key).',
            'key.unique' => 'Biến này đã tồn tại trong hệ thống.',
        ]);

        SystemSetting::create($request->all());

        return redirect()->route('system_settings.index')->with('success', 'Đã thêm cấu hình mới thành công!');
    }

    public function edit(SystemSetting $systemSetting)
    {
        return view('system_settings.edit', compact('systemSetting'));
    }

    public function update(Request $request, SystemSetting $systemSetting)
    {
        // Khi update, bỏ qua việc check trùng lặp key của chính nó (dùng id để loại trừ)
        $request->validate([
            'key' => 'required|string|max:255|unique:system_settings,key,'.$systemSetting->id,
            'value' => 'nullable|string',
        ]);

        $systemSetting->update($request->all());

        return redirect()->route('system_settings.index')->with('success', 'Cập nhật cấu hình thành công!');
    }

    public function destroy(SystemSetting $systemSetting)
    {
        $systemSetting->delete();

        return redirect()->route('system_settings.index')->with('success', 'Đã xóa cấu hình khỏi hệ thống.');
    }
}
