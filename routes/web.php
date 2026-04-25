<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Tạo đủ 7 routes: index, create, store, show, edit, update, destroy
// Trang hiển thị form upload
// Route::get('/tests/upload', [TestController::class, 'upload'])->name('tests.upload');

// Xử lý file Word sau khi nhấn nút Upload
// Route::post('/tests/upload', [TestController::class, 'importFromWord'])->name('tests.import-word');
// Route::get('/tests/{id}/pdf', [TestController::class, 'exportPdf'])->name('tests.pdf');
// Route::get('/tests/{id}/docx', [TestController::class, 'exportWord'])->name('tests.docx');
// Route::resource('tests', TestController::class);
// -----------------Không cần đăng nhập vẫn có thể truy cập các route này-----------------

Route::get('/login', function () {
    return view('auth.login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Các Route yêu cầu phải Đăng Nhập mới được vào (Bảo vệ bằng middleware 'auth')
Route::middleware('auth')->group(function () {
    // Nếu bạn đang dùng /main, hoặc /dashboard thì sửa lại cho khớp nhé
    Route::get('/main', function () {
        return view('welcome');
    })->name('main');

    Route::get('/', function () {
        return view('welcome');
    });
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // bảng user
    // =========================================================
    // KHU VỰC DÀNH RIÊNG CHO ADMIN (Bảo vệ bằng middleware role)
    // =========================================================
    Route::middleware(['role:admin'])->group(function () {

        // Nghiệp vụ phân quyền
        Route::get('users/{user}/assign-roles', [UserController::class, 'assignRoles'])->name('users.assign-roles');
        Route::put('users/{user}/assign-roles', [UserController::class, 'updateRoles'])->name('users.update-roles');

        // Quản lý users (7 routes)
        Route::resource('users', UserController::class);

        // Quản lý roles
        Route::resource('roles', RoleController::class);

    });
    // =========================================================
});
