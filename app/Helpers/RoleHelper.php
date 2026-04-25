<?php
if (!function_exists('role_dictionary')) {
    /**
     * Dịch tên role (tiếng Anh) sang tiếng Việt
     *
     * @param string $roleName
     * @return string
     */
    function role_dictionary($roleName)
    {
        $dictionary = [
            'admin'       => 'Quản trị viên',
            'team-leader' => 'Tổ trưởng chuyên môn',
            'teacher'     => 'Giáo viên',
            'student'     => 'Học sinh',
            'staff'       => 'Nhân viên',
            // Bạn có thể thêm bao nhiêu từ tuỳ thích vào đây
        ];

        // Nếu key tồn tại trong từ điển thì trả về tiếng Việt, 
        // Nếu không có, nó sẽ trả về lại tên gốc (và viết hoa chữ cái đầu cho đẹp)
        return $dictionary[$roleName] ?? ucfirst($roleName);
    }
}