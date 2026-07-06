<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriUMKM extends Model
{
    use HasFactory;

    protected $table = 'kategori_umkms';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_kategori',
    ];

    public function umkms()
    {
        return $this->hasMany(UMKM::class, 'id_kategori', 'id');
    }
}
