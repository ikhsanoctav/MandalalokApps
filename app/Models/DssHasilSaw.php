<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DssHasilSaw extends Model
{
    protected $fillable = [
        'umkm_id',
        'kriteria_id',
        'nilai_normalisasi',
        'bobot',
        'kontribusi_skor',
        'total_skor',
        'ranking',
        'periode',
        'profil'
    ];

    public function umkm()
    {
        return $this->belongsTo(UMKM::class, 'umkm_id', 'id_umkm');
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
