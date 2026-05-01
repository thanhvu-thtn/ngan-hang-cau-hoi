<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Choice extends Model
{
    use HasFactory;
    //use HasFactory, SoftDeletes; // 2. Thêm vào đây
    // BẮT BUỘC THÊM DÒNG NÀY ĐỂ MAP ĐÚNG VỚI CSDL
    //protected $table = 'question_choices';
    protected $fillable = [
        'question_id',
        'content',
        'is_true',
        'order_index',
    ];

    protected $casts = [
        'is_true' => 'boolean',
    ];

    /**
     * Lựa chọn này thuộc về câu hỏi nào
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}