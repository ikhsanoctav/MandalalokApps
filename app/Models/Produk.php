<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'id_umkm',
        'nama_produk',
        'deskripsi',
        'harga',
        'foto_produk',
    ];

    protected $casts = [
        'foto_produk' => 'array',
        'harga' => 'decimal:2',
    ];

    public function umkm()
    {
        return $this->belongsTo(UMKM::class, 'id_umkm', 'id_umkm');
    }
}
