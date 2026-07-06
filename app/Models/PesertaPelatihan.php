<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaPelatihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelatihan_id',
        'user_id',
        'dokumen_syarat',
        'kode_tiket',
        'status_kehadiran',
        'waktu_hadir',
    ];

    protected $casts = [
        'waktu_hadir' => 'datetime',
        'dokumen_syarat' => 'array',
    ];

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
