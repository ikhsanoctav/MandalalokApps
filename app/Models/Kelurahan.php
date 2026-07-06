<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_kelurahan',
        'nama_kelurahan',
        'kecamatan',
        'kota_kab',
        'provinsi',
    ];

    public function rws()
    {
        return $this->hasMany(Rw::class, 'kelurahan_id');
    }

    // Relasi ke UMKM melalui pemilik
    public function umkm()
    {
        return $this->hasManyThrough(
            UMKM::class,
            Pemilik::class,
            'kelurahan',  // Foreign key di pemiliks
            'id_pemilik', // Foreign key di umkms
            'nama_kelurahan', // Local key di kelurahans
            'kelurahan'      // Local key di pemiliks
        );
    }
}
