<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerifikasiLapangan extends Model
{
    protected $table = 'verifikasi_lapangan';

    protected $fillable = [
        'umkm_id',
        'petugas_id',
        'tanggal_kunjungan',
        'status_kunjungan',
        'kondisi_usaha',
        'catatan_kunjungan',
        'foto_kunjungan',
        'latitude_kunjungan',
        'longitude_kunjungan',
    ];

    protected $casts = [
        'foto_kunjungan' => 'array',
        'tanggal_kunjungan' => 'date',
        'latitude_kunjungan' => 'decimal:7',
        'longitude_kunjungan' => 'decimal:7',
    ];

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(UMKM::class, 'umkm_id', 'id_umkm');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    /**
     * Badge warna untuk status kunjungan.
     */
    public function getStatusBadgeAttribute(): string
    {
        $map = [
            'belum_dikunjungi' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'dot' => 'bg-red-500', 'label' => 'Belum Dikunjungi'],
            'dikunjungi' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500', 'label' => 'Dikunjungi'],
            'butuh_tindak_lanjut' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'dot' => 'bg-amber-500', 'label' => 'Perlu Tindak Lanjut'],
        ];

        $s = $map[$this->status_kunjungan] ?? $map['belum_dikunjungi'];

        return '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold ' . $s['bg'] . ' ' . $s['text'] . '">'
            . '<span class="w-1.5 h-1.5 rounded-full ' . $s['dot'] . '"></span>' . e($s['label'])
            . '</span>';
    }

    /**
     * Badge warna untuk kondisi usaha.
     */
    public function getKondisiBadgeAttribute(): string
    {
        $map = [
            'sesuai' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Sesuai'],
            'tidak_sesuai' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Tidak Sesuai'],
            'tutup_sementara' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Tutup Sementara'],
            'tidak_ditemukan' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'label' => 'Tidak Ditemukan'],
        ];

        $s = $map[$this->kondisi_usaha] ?? $map['sesuai'];

        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ' . $s['bg'] . ' ' . $s['text'] . '">'
            . e($s['label'])
            . '</span>';
    }
}
