<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionStatus extends Model
{
    use HasFactory;

    // Khai báo các cột có thể chèn dữ liệu hàng loạt (Mass Assignment)
    protected $fillable = [
        'code',
        'name',
        'description',
        'color',
        'order_index',
    ];

    /**
     * Global Scope để luôn sắp xếp theo thứ tự quy trình mặc định
     */
    protected static function booted()
    {
        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('order_index', 'asc');
        });
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'question_status_id');
    }
}
