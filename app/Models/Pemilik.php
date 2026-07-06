<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class Pemilik extends Model
{
    use HasFactory;

    protected $table = 'pemiliks';

    protected $primaryKey = 'id_pemilik';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pemilik',
        'nik',
        'nik_hash',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_hp',
        'email',
        'alamat',
        'rt',
        'rw',
        'kelurahan',
        'id_kelurahan',
        'kecamatan',
        'kota_kab',
        'provinsi',
        'kode_pos',
        'status_verifikasi_ktp',
        'foto_ktp',
        'catatan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Get and set the NIK attribute safely handling decryption errors.
     */
    protected function nik(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) {
                    return $value;
                }
                
                try {
                    return Crypt::decryptString($value);
                } catch (DecryptException $e) {
                    return $value; // Fallback to raw value if not encrypted
                }
            },
            set: fn ($value) => $value ? Crypt::encryptString($value) : null,
        );
    }

    protected static function booted()
    {
        static::saving(function ($pemilik) {
            // Only hash NIK if it was actually changed
            if ($pemilik->isDirty('nik') && $pemilik->getAttributes()['nik'] ?? null) {
                try {
                    $plainNik = \Illuminate\Support\Facades\Crypt::decryptString($pemilik->getAttributes()['nik']);
                    $pemilik->nik_hash = hash('sha256', $plainNik);
                } catch (\Exception $e) {
                    $pemilik->nik_hash = hash('sha256', $pemilik->getAttributes()['nik']);
                }
            }

            // Auto-resolve id_kelurahan from kelurahan name whenever kelurahan is dirty
            if ($pemilik->isDirty('kelurahan') && $pemilik->getAttributes()['kelurahan'] ?? null) {
                $kelurahanName = $pemilik->getAttributes()['kelurahan'];
                $kel = \App\Models\Kelurahan::where('nama_kelurahan', $kelurahanName)->first();
                if ($kel) {
                    $pemilik->id_kelurahan = $kel->id;
                }
            }
        });
    }

    public function getNikMaskedAttribute()
    {
        if (! $this->nik) {
            return '-';
        }
        $length = strlen($this->nik);
        if ($length <= 8) {
            return str_repeat('*', $length > 0 ? $length : 8);
        }

        return substr($this->nik, 0, 4).str_repeat('*', $length - 8).substr($this->nik, -4);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_pemilik)) {
                $model->id_pemilik = (string) Str::uuid();
            }
        });
    }

    // Relasi ke UMKM
    public function umkm()
    {
        return $this->hasMany(UMKM::class, 'id_pemilik', 'id_pemilik');
    }

    // Relasi ke Kelurahan via FK (id_kelurahan) – gunakan ini dari controller/query builder
    public function kelurahanRel()
    {
        return $this->belongsTo(Kelurahan::class, 'id_kelurahan');
    }

    // Fallback: lookup kelurahan by nama (string match) jika id_kelurahan belum terisi
    public function kelurahanByName()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan', 'nama_kelurahan');
    }

    /**
     * Accessor agar $pemilik->kelurahan SELALU mengembalikan string nama_kelurahan.
     * Memungkinkan semua view yang ada tetap bekerja tanpa perubahan.
     */
    protected function kelurahan(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                // Jika kelurahanRel sudah di-load dari FK, ambil nama_kelurahan-nya
                if ($this->relationLoaded('kelurahanRel') && $this->kelurahanRel) {
                    return $this->kelurahanRel->nama_kelurahan;
                }
                // Jika id_kelurahan ada, lazy-load dari tabel kelurahans
                if ($this->id_kelurahan) {
                    $kel = \App\Models\Kelurahan::find($this->id_kelurahan);
                    if ($kel) {
                        return $kel->nama_kelurahan;
                    }
                }
                // Fallback: raw string dari kolom kelurahan
                return $value ?: '-';
            },
            set: fn ($value) => $value,
        );
    }
}
