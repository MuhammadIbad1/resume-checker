<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScanResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'scan_id',
        'category',
        'rule_code',
        'rule_name',
        'passed',
        'score_awarded',
        'max_score',
        'message',
        'recommendation',
        'metadata',
    ];

    protected $casts = [
        'passed' => 'boolean',
        'metadata' => 'array',
    ];

    public function scan(): BelongsTo
    {
        return $this->belongsTo(Scan::class);
    }
}
