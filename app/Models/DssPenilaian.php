<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DssPenilaian extends Model
{
    protected $fillable = [
        'umkm_id',
        'kriteria_id',
        'nilai',
        'periode'
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
