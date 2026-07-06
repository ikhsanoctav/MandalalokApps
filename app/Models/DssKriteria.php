<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DssKriteria extends Model
{
    use HasFactory;

    protected $table = 'dss_kriterias';

    protected $fillable = [
        'kode_kriteria',
        'nama_kriteria',
        'tipe_dss',
        'jenis',
        'bobot',
        'aktif',
    ];

    protected $casts = [
        'bobot' => 'float',
        'aktif' => 'boolean',
    ];
}
