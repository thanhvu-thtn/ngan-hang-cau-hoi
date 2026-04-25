<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- Bạn cần thêm dòng này

class Test extends Model
{
    use HasFactory;

    // Khai báo các trường được phép lưu vào database
    protected $fillable = [
        'content',
    ];
}
