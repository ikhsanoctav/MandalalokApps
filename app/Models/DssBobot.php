<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DssBobot extends Model
{
    protected $fillable = [
        'kriteria_id',
        'bobot',
        'cr_value',
        'profil'
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
