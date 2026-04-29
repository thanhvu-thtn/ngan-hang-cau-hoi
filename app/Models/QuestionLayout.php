<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionLayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'order_index',
    ];

    // Luôn sắp xếp theo order_index
    protected static function booted()
    {
        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('order_index', 'asc');
        });
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'question_layout_id');
    }
}    //
