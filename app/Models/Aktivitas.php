<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aktivitas extends Model
{
    protected $table = 'aktivitas';

    protected $fillable = [
        'user_id',
        'umkm_id',
        'deskripsi',
        'type',
        'ip_address',
        'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(UMKM::class, 'umkm_id', 'id_umkm');
    }
}
