<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Import Model User
use Illuminate\Support\Facades\Hash; // Import thư viện Hash mật khẩu

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Nguyễn Thanh Vũ',
            'email' => 'thanhvu@thtn.info',
            // Mật khẩu bắt buộc phải được mã hóa bằng Hash::make thì Laravel mới cho phép đăng nhập
            'password' => Hash::make('12345678'), 
        ]);
    }
}