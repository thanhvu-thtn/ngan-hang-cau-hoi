<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    use HasFactory;

    // Cho phép thêm dữ liệu hàng loạt vào 2 cột này
    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * Quan hệ: Một Khối lớp có nhiều Chuyên đề
     */
    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }
}
