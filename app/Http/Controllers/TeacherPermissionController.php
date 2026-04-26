<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class TeacherPermissionController extends Controller
{
    public function index()
    {
        // Spatie hỗ trợ scope 'role' để lấy ĐÚNG những user có role là 'teacher'
        $teachers = User::role('teacher')->paginate(10);

        return view('teacher_permissions.index', compact('teachers'));
    }

    public function edit(User $teacher)
    {
        if (! $teacher->hasRole('teacher')) {
            abort(403, 'Bạn chỉ được phép phân quyền cho Giáo viên.');
        }

        // Lấy TẤT CẢ các quyền có trong hệ thống
        $permissions = Permission::all();

        $userDirectPermissions = $teacher->getDirectPermissions()->pluck('name')->toArray();

        return view('teacher_permissions.edit', compact('teacher', 'permissions', 'userDirectPermissions'));
    }

    public function update(Request $request, User $teacher)
    {
        if (! $teacher->hasRole('teacher')) {
            abort(403);
        }

        // syncPermissions sẽ tự động xóa các quyền cũ và cập nhật quyền mới theo mảng gửi lên.
        // Nếu không check ô nào ($request->permissions là null), ta truyền mảng rỗng [] để xóa hết.
        $teacher->syncPermissions($request->permissions ?? []);

        return redirect()->route('teacher-permissions.index')
            ->with('success', 'Đã cập nhật quyền cho giáo viên '.$teacher->name);
    }
}
