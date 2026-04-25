<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy danh sách users, phân trang 10 người/trang và sắp xếp mới nhất lên đầu
        $users = User::latest()->paginate(10);
        
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        // 1. Kiểm tra dữ liệu đầu vào (Validation)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // unique:users đảm bảo email không bị trùng
            'password' => 'required|string|min:8|confirmed', // confirmed bắt buộc phải có trường password_confirmation khớp nhau
        ], [
            // Tùy chỉnh câu thông báo lỗi sang tiếng Việt (Tùy chọn)
            'name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp.',
        ]);

        // 2. Lưu vào Database
        // Nhờ dòng 'password' => 'hashed' trong model User, Laravel sẽ tự động băm mật khẩu
        User::create($validated);

        // 3. Quay về trang danh sách và báo thành công
        return redirect()->route('users.index')->with('success', 'Đã thêm người dùng mới thành công!');
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
    public function edit(User $user)
    {
        // Trả về view và truyền biến $user mang thông tin của người dùng cần sửa
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // 1. Kiểm tra dữ liệu
        $rules = [
            'name' => 'required|string|max:255',
            // Bỏ qua kiểm tra trùng lặp với chính ID của user hiện tại
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, 
        ];

        // Nếu người dùng có gõ mật khẩu mới thì mới kiểm tra validate mật khẩu
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules, [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email này đã được sử dụng bởi người khác.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp.',
        ]);

        // 2. Cập nhật dữ liệu
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        // Nếu có nhập mật khẩu mới thì mới cập nhật (tự động hash nhờ Model)
        if ($request->filled('password')) {
            $user->password = $validated['password'];
        }

        $user->save();

        // 3. Quay về trang danh sách và báo thành công
        return redirect()->route('users.index')->with('success', 'Đã cập nhật thông tin người dùng thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // 1. Kiểm tra bảo mật: Không cho phép tự xoá chính mình
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Bạn không thể tự xoá tài khoản của chính mình!');
        }

        // 2. Thực hiện xoá
        $user->delete();

        // 3. Quay lại trang danh sách với thông báo thành công
        return redirect()->route('users.index')->with('success', 'Người dùng đã được xoá khỏi hệ thống.');
    }
}
