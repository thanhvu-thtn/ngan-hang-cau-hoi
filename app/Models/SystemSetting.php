<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemSetting extends Model
{
    use HasFactory;

    // Khai báo các trường được phép mass-assignment
    protected $fillable = [
        'key',
        'value',
    ];
}
