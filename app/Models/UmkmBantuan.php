<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmkmBantuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'umkm_id',
        'nama_bantuan',
        'sumber_bantuan',
        'tahun_bantuan',
        'keterangan',
    ];

    public function umkm()
    {
        return $this->belongsTo(UMKM::class, 'umkm_id', 'id_umkm');
    }
}
