<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::latest()->paginate(10);

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ], [
            'name.required' => 'Vui lòng nhập tên chức vụ.',
            'name.unique' => 'Tên chức vụ này đã tồn tại trong hệ thống.',
        ]);

        // Spatie sẽ tự động thêm guard_name là 'web'
        Role::create(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Đã thêm chức vụ mới thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            // Bỏ qua ID hiện tại khi check trùng lặp
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
        ], [
            'name.required' => 'Vui lòng nhập tên chức vụ.',
            'name.unique' => 'Tên chức vụ này đã tồn tại trong hệ thống.',
        ]);

        $role->update(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Đã cập nhật chức vụ thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Đã xóa chức vụ khỏi hệ thống!');
    }

    /**
     * Hiển thị form gán quyền cho Role
     */
    public function assignPermissions(Role $role)
    {
        $permissions = Permission::all();
        // Lấy danh sách ID của các quyền mà Role này đang có để check vào checkbox
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('roles.assign-permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Xử lý lưu các quyền được chọn
     */
    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
        ]);

        // Đồng bộ quyền: Xoá hết quyền cũ và thêm các quyền mới được chọn
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Đã cập nhật quyền cho vai trò thành công!');
    }
}
