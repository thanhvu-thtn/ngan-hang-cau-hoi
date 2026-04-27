<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicContent extends Model
{
    use HasFactory;

    protected $fillable = ['topic_id', 'code', 'name'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function objectives()
    {
        return $this->hasMany(Objective::class, 'topic_content_id');
    }
}
