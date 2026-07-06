<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmkmRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'umkm_id',
        'nama_reviewer',
        'rating',
        'ulasan',
        'likes',
        'dislikes',
        'is_hidden',
    ];

    public function umkm()
    {
        return $this->belongsTo(UMKM::class, 'umkm_id');
    }
}
