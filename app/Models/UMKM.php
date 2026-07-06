<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UMKM extends Model
{
    use HasFactory;

    protected $table = 'umkms';

    protected $primaryKey = 'id_umkm';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_umkm',
        'no_pendaftaran',
        'nama_usaha',
        'id_pemilik',
        'id_kategori',
        'id_sektor',
        'status_usaha',
        'tahun_berdiri',
        'no_izin_usaha',
        'jenis_izin',
        'npwp_usaha',
        'telp_usaha',
        'email_usaha',
        'website',
        'media_sosial',
        'jumlah_tenaga_kerja',
        'tenaga_kerja_laki',
        'tenaga_kerja_perempuan',
        'riwayat_bantuan',
        'alamat_usaha',
        'latitude',
        'longitude',
        'deskripsi',
        'foto_utama',
        'foto_gallery',
        'dokumen_ktp',
        'dokumen_nib',
        'dokumen_lainnya',
        'id_petugas',
        'tanggal_pendataan',
        'tanggal_verifikasi',
        'status_verifikasi',
        'catatan_penolakan',
        'bentuk_jualan',
        'perkiraan_omset',
    ];

    protected $casts = [
        'media_sosial' => 'array',
        'foto_gallery' => 'array',
        'tanggal_pendataan' => 'date',
        'tanggal_verifikasi' => 'datetime',
        'tahun_berdiri' => 'integer',
        'id_petugas' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_umkm)) {
                $model->id_umkm = (string) Str::uuid();
            }
            if ($model->status_verifikasi === 'terverifikasi' && empty($model->no_pendaftaran)) {
                $model->no_pendaftaran = $model->generateNoPendaftaran();
            }
        });

        static::updating(function ($model) {
            // Generate no_pendaftaran jika status_verifikasi berubah menjadi terverifikasi
            if ($model->isDirty('status_verifikasi') && $model->status_verifikasi === 'terverifikasi') {
                if (empty($model->no_pendaftaran)) {
                    $model->no_pendaftaran = $model->generateNoPendaftaran();
                }
            }
        });
    }

    public function generateNoPendaftaran()
    {
        $pemilik = Pemilik::find($this->id_pemilik);
        
        $rwClean = $pemilik && $pemilik->rw ? preg_replace('/[^0-9]/', '', $pemilik->rw) : '00';
        $rtClean = $pemilik && $pemilik->rt ? preg_replace('/[^0-9]/', '', $pemilik->rt) : '00';
        
        // Pastikan masing-masing maksimal 2 digit, ambil 2 digit terakhir jika lebih
        $rw = str_pad(substr($rwClean, -2), 2, '0', STR_PAD_LEFT);
        $rt = str_pad(substr($rtClean, -2), 2, '0', STR_PAD_LEFT);
        
        $kodeRWRT = $rw.$rt;

        $kodeKelurahan = '000';
        if ($pemilik && $pemilik->kelurahan) {
            $kelurahan = Kelurahan::where('nama_kelurahan', $pemilik->kelurahan)->first();
            if ($kelurahan) {
                // Ambil 3 digit angka terakhir dari kode kelurahan
                $kodeKel = preg_replace('/[^0-9]/', '', $kelurahan->kode_kelurahan);
                if (!empty($kodeKel)) {
                    $kodeKelurahan = str_pad(substr($kodeKel, -3), 3, '0', STR_PAD_LEFT);
                } else {
                    $kodeKelurahan = substr($kelurahan->kode_kelurahan, 0, 3);
                }
            }
        }

        // Gunakan format 'ym' (tahun 2 digit) agar total panjang string <= 28 karakter
        $tahunBulan = date('ym');

        // Ambil urutan tertinggi secara absolut dari database
        $lastSequence = self::selectRaw('MAX(CAST(SUBSTRING_INDEX(no_pendaftaran, "-", -1) AS UNSIGNED)) as max_seq')->value('max_seq');
        
        $sequence = $lastSequence ? $lastSequence + 1 : 1;

        $nomorUrut = str_pad($sequence, 4, '0', STR_PAD_LEFT);

        return 'UMKM-MDJ-'.$tahunBulan.'-'.$kodeKelurahan.'-'.$kodeRWRT.'-'.$nomorUrut;
    }

    public function pemilik()
    {
        return $this->belongsTo(Pemilik::class, 'id_pemilik', 'id_pemilik');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriUMKM::class, 'id_kategori');
    }

    public function sektor()
    {
        return $this->belongsTo(SektorUmkm::class, 'id_sektor');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas', 'id');
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'umkm_id', 'id_umkm');
    }

    public function verifikasiLapangan()
    {
        return $this->hasMany(VerifikasiLapangan::class, 'umkm_id', 'id_umkm');
    }

    public function latestVerifikasiLapangan()
    {
        return $this->hasOne(VerifikasiLapangan::class, 'umkm_id', 'id_umkm')->latestOfMany();
    }

    public function scopeTerverifikasi($query)
    {
        return $query->where('status_verifikasi', 'terverifikasi');
    }

    public function scopeMenunggu($query)
    {
        return $query->where('status_verifikasi', 'terkirim');
    }

    public function scopeAktif($query)
    {
        return $query->where('status_usaha', 'aktif');
    }

    public function getStatusVerifikasiBadgeAttribute()
    {
        $badges = [
            'draft' => 'secondary',
            'terkirim' => 'warning',
            'terverifikasi' => 'success',
            'ditolak' => 'danger',
        ];

        return '<span class="badge bg-'.($badges[$this->status_verifikasi] ?? 'secondary').'">'.ucfirst($this->status_verifikasi).'</span>';
    }

    public function getStatusUsahaBadgeAttribute()
    {
        $badges = [
            'aktif' => 'success',
            'non_aktif' => 'danger',
            'tutup' => 'secondary',
            'pindah' => 'warning',
        ];

        return '<span class="badge bg-'.($badges[$this->status_usaha] ?? 'secondary').'">'.ucfirst($this->status_usaha).'</span>';
    }

    public function produks()
    {
        return $this->hasMany(Produk::class, 'id_umkm', 'id_umkm');
    }

    public function ratings()
    {
        return $this->hasMany(UmkmRating::class, 'umkm_id', 'id_umkm');
    }

    public function getAverageRatingAttribute()
    {
        $avg = $this->ratings()->where('is_hidden', false)->avg('rating');
        return $avg ? round($avg, 1) : 0;
    }

    public function bantuans()
    {
        return $this->hasMany(UmkmBantuan::class, 'umkm_id', 'id_umkm');
    }
}
