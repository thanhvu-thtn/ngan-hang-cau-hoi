<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SharedContext extends Model
{
    use HasFactory;
    //use HasFactory, SoftDeletes; // 2. Thêm vào đây

    protected $fillable = [
        'code',
        'content',
        'description',
    ];

    /**
     * Một ngữ cảnh dùng chung có thể có nhiều câu hỏi đi kèm
     */
    public function questions()
    {
        // Giả sử sau này bạn có bảng 'questions'
        return $this->hasMany(Question::class, 'shared_context_id');
    }
}