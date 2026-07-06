<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SektorUmkm extends Model
{
    use HasFactory;

    protected $table = 'sektor_umkms';

    protected $fillable = [
        'nama_sektor',
        'deskripsi',
    ];

    public function umkms()
    {
        return $this->hasMany(UMKM::class, 'id_sektor', 'id');
    }
}
