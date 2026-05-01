<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory;
    //use HasFactory, SoftDeletes; // 2. Thêm vào đây

    protected $fillable = [
        'code',
        'description',
        'question_type_id',
        'cognitive_level_id',
        'question_status_id',
        'question_layout_id',
        'shared_context_id',
        'stem',
        'explanation',
        'layout_ratio',
        'order_index',
        'created_by_id',
        'reviewer_id',
    ];

    // --- CÁC QUAN HỆ BELONGS TO ---

    public function type(): BelongsTo
    {
        return $this->belongsTo(QuestionType::class, 'question_type_id');
    }

    public function cognitiveLevel(): BelongsTo
    {
        return $this->belongsTo(CognitiveLevel::class, 'cognitive_level_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(QuestionStatus::class, 'question_status_id');
    }

    public function layout(): BelongsTo
    {
        return $this->belongsTo(QuestionLayout::class, 'question_layout_id');
    }

    public function sharedContext(): BelongsTo
    {
        return $this->belongsTo(SharedContext::class, 'shared_context_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // --- CÁC QUAN HỆ NHIỀU - NHIỀU & MỘT - NHIỀU ---

    /**
     * Yêu cầu cần đạt (Objectives) mà câu hỏi này phục vụ
     */
    public function objectives(): BelongsToMany
    {
        return $this->belongsToMany(Objective::class, 'objective_question');
    }

    /**
     * Các lựa chọn / Đáp án của câu hỏi
     */
    public function choices(): HasMany
    {
        return $this->hasMany(Choice::class)->orderBy('order_index', 'asc');
    }
}