<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Objective extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_content_id',
        'code',
        'description'
    ];

    /**
     * Quan hệ: Một yêu cầu cần đạt thuộc về một Nội dung chuyên đề
     */
    public function topicContent(): BelongsTo
    {
        return $this->belongsTo(TopicContent::class, 'topic_content_id');
    }
}