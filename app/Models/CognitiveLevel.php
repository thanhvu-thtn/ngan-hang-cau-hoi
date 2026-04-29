<?php

// app/Models/CognitiveLevel.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CognitiveLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'order_index',
    ];

    // Gợi ý: Luôn sắp xếp theo order_index khi truy vấn
    protected static function booted()
    {
        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('order_index', 'asc');
        });
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'cognitive_level_id');
    }
}
