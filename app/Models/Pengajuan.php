<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuans';

    protected $primaryKey = 'id_pengajuan';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pengajuan',
        'umkm_id',
        'jenis_pengajuan',
        'nama_program',
        'nominal',
        'keterangan',
        'status',
        'tanggal_pengajuan',
        'tanggal_disetujui',
        'catatan',
        'berkas',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_pengajuan' => 'date',
        'tanggal_disetujui' => 'date',
        'berkas' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_pengajuan)) {
                $model->id_pengajuan = (string) Str::uuid();
            }
        });
    }

    // Relasi ke UMKM
    public function umkm()
    {
        return $this->belongsTo(UMKM::class, 'umkm_id', 'id_umkm');
    }

    // Accessor untuk badge status
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'menunggu' => '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700"><span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>Menunggu</span>',
            'proses' => '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700"><span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>Diproses</span>',
            'disetujui' => '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Disetujui</span>',
            'ditolak' => '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700"><span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>Ditolak</span>',
            'dicairkan' => '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700"><span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>Dicairkan</span>',
            default => '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">'.ucfirst($this->status).'</span>'
        };
    }
}
