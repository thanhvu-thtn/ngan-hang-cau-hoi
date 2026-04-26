<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    // Cho phép thêm dữ liệu hàng loạt vào 2 cột này
    protected $fillable = [
        'name',
        'code',
    ];
}