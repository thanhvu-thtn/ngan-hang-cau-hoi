<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Xử lý dữ liệu khi submit form
    public function login(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào (Validation)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Kiểm tra xem người dùng có tick vào ô "Ghi nhớ đăng nhập" không
        $remember = $request->has('remember');

        // Hàm Auth::attempt sẽ tự động tìm email trong DB và so sánh mật khẩu mã hóa
        if (Auth::attempt($credentials, $remember)) {
            // Đăng nhập thành công: Tạo session mới để tránh lỗi bảo mật (Session Fixation)
            $request->session()->regenerate();

            // Chuyển hướng người dùng về trang chính (ví dụ: /main hoặc /dashboard)
            return redirect()->intended('main');
        }

        // Đăng nhập thất bại: Trả về trang cũ kèm thông báo lỗi
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email'); // Giữ lại email người dùng vừa nhập cho đỡ mất công gõ lại
    }

    // 3. Xử lý đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();

        // Xóa sạch session hiện tại
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
