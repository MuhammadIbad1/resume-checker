<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuleSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'rule_code',
        'category',
        'name',
        'description',
        'max_points',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];
}
