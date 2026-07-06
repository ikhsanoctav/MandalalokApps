<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DssAnalysis extends Model
{
    protected $table = 'dss_analyses';

    protected $fillable = [
        'user_id',
        'analysis_data',
    ];

    protected $casts = [
        'analysis_data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
