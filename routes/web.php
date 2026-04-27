<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QuestionTypeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\TeacherPermissionController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TopicContentController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TopicTypeController;
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
        // Gán quyền cho Role (Permissions to Role)
        Route::get('roles/{role}/assign-permissions', [RoleController::class, 'assignPermissions'])->name('roles.assign-permissions');
        Route::put('roles/{role}/assign-permissions', [RoleController::class, 'updatePermissions'])->name('roles.update-permissions');
        Route::resource('roles', RoleController::class);

        // Quản lý permissions
        Route::resource('permissions', PermissionController::class);

        // Quản lý System SystemSettings
        Route::resource('system_settings', SystemSettingController::class);

        // Quản lý Khối lớp
        Route::resource('grades', GradeController::class);

        // Quản lý Kiểu chuyên đề
        Route::resource('topic-types', TopicTypeController::class);

        // Quản lý Loại câu hỏi
        Route::resource('question-types', QuestionTypeController::class);

    });
    // =========================================================
    // =========================================================
    // KHU VỰC DÀNH RIÊNG CHO tổ trưởng (Bảo vệ bằng middleware role)
    // =========================================================
    Route::middleware(['role:team-leader'])->group(function () {
        Route::get('/teacher-permissions', [TeacherPermissionController::class, 'index'])->name('teacher-permissions.index');
        Route::get('/teacher-permissions/{teacher}/edit', [TeacherPermissionController::class, 'edit'])->name('teacher-permissions.edit');
        Route::put('/teacher-permissions/{teacher}', [TeacherPermissionController::class, 'update'])->name('teacher-permissions.update');

        // Import Excel
        Route::get('/topics/export', [TopicController::class, 'export'])->name('topics.export');
        Route::get('topics/import', [TopicController::class, 'importForm'])->name('topics.import.form');
        Route::post('topics/import', [TopicController::class, 'importExcel'])->name('topics.import.process');
        Route::post('/topics/import-save', [TopicController::class, 'importSave'])->name('topics.import.save');
        // Quản lý Chuyên đề
        Route::resource('topics', TopicController::class);
        // Quản lý Nội dung chuyên đề
        Route::prefix('topic-contents')->name('topic-contents.')->group(function () {
            // Các route Resource khác (index, create, store...) đã có sẵn

            // Nhóm route Import
            Route::get('import', [TopicContentController::class, 'importForm'])->name('import.form');
            Route::post('import-preview', [TopicContentController::class, 'importPreview'])->name('import.preview');
            Route::post('import-save', [TopicContentController::class, 'importSave'])->name('import.save');
            Route::get('export', [TopicContentController::class, 'export'])->name('export'); // Thêm dòng này
            Route::get('import', [TopicContentController::class, 'importForm'])->name('import.form');
        });
        Route::resource('topic-contents', TopicContentController::class);

        // Quản lý Yêu cầu cần đạt
        Route::get('objectives-import/word', [ObjectiveController::class, 'importWord'])->name('objectives.import.word');
        Route::post('objectives-import/preview', [ObjectiveController::class, 'previewWord'])->name('objectives.preview.word');
        Route::post('objectives-import/save', [ObjectiveController::class, 'saveFromWord'])->name('objectives.save.word');
        Route::post('objectives-import/cancel', [ObjectiveController::class, 'cancelFromWord'])->name('objectives.cancel.word');
        Route::resource('objectives', ObjectiveController::class);
    });
});
