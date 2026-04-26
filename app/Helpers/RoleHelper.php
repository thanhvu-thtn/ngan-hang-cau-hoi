<?php

use App\Models\SystemSetting;

if (! function_exists('role_dictionary')) {
    /**
     * Dịch tên role (tiếng Anh) sang tiếng Việt
     *
     * @param  string  $roleName
     * @return string
     */
    function role_dictionary($roleName)
    {
        $dictionary = [
            'admin' => 'Quản trị viên',
            'team-leader' => 'Tổ trưởng chuyên môn',
            'teacher' => 'Giáo viên',
            'student' => 'Học sinh',
            'staff' => 'Nhân viên',
            // Bạn có thể thêm bao nhiêu từ tuỳ thích vào đây
        ];

        // Nếu key tồn tại trong từ điển thì trả về tiếng Việt,
        // Nếu không có, nó sẽ trả về lại tên gốc (và viết hoa chữ cái đầu cho đẹp)
        return $dictionary[$roleName] ?? ucfirst($roleName);
    }

    if (! function_exists('permission_dictionary')) {
        /**
         * Dịch tên quyền (tiếng Anh) sang tiếng Việt
         *
         * @param  string  $permissionName
         * @return string
         */
        function permission_dictionary($permissionName)
        {
            // Tuỳ thuộc vào bước trước bạn chọn kiểu đặt tên nào (dấu gạch ngang hay dấu chấm)
            // thì bạn dùng key tương ứng ở bên dưới nhé. Ở đây tôi ví dụ cả 2 trường hợp.
            $dictionary = [
                // Nếu bạn dùng dấu gạch ngang (Kebab-case)
                'create-questions' => 'Soạn câu hỏi',
                'approve-questions' => 'Thẩm định câu hỏi',
                'import-questions' => 'Tải câu hỏi từ máy tính',
                'create-exercises' => 'Soạn bài tập',
                'create-exams' => 'Soạn đề thi',
                'approve-exams' => 'Thẩm định đề thi',

            ];

            // Nếu có trong từ điển thì in ra tiếng Việt.
            // Nếu không có, nó sẽ tự động thay dấu gạch ngang/dấu chấm thành dấu cách và viết hoa chữ đầu.
            // Ví dụ: 'delete-posts' -> 'Delete posts'
            return $dictionary[$permissionName] ?? ucfirst(str_replace(['-', '.'], ' ', $permissionName));
        }
    }

    if (! function_exists('system_setting')) {
        /**
         * Lấy giá trị từ bảng system_settings theo key.
         *
         * @param  string  $key
         * @param  mixed  $default  Giá trị mặc định nếu không tìm thấy key
         * @return mixed
         */
        function system_setting($key, $default = null)
        {
            // Nhớ import Model ở đầu file Helper nếu cần, hoặc gọi trực tiếp bằng namespace như dưới đây:
            $systemSetting = SystemSetting::where('key', $key)->first();

            return $systemSetting ? $systemSetting->value : $default;
        }
    }
}
