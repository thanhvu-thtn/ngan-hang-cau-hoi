<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_id',
        'topic_type_id',
        'name',
        'code',
    ];

    /**
     * Quan hệ: Một chuyên đề thuộc về một Khối lớp
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Quan hệ: Một chuyên đề thuộc về một Kiểu chuyên đề
     */
    public function topicType(): BelongsTo
    {
        return $this->belongsTo(TopicType::class);
    }
}