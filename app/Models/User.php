<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Encryption\DecryptException;

class User extends Authenticatable
{
    // HasApiTokens wajib untuk Laravel Sanctum (token API)
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'kode_user',
        'nik',
        'nik_hash',
        'no_hp',
        'alamat',
        'foto',
        'jabatan',
        'id_kelurahan',
        'rw',
        'rt',
        'kelurahan',
        'password_reset_status',
        'password_reset_requested_at',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Standar keamanan terbaru Laravel
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user's profile photo URL or a default avatar.
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->foto) {
            if (str_starts_with($this->foto, 'http://') || str_starts_with($this->foto, 'https://')) {
                return $this->foto;
            }
            if (str_starts_with($this->foto, 'storage/')) {
                return asset($this->foto);
            }
            return asset('storage/' . $this->foto);
        }

        // Check associated Pemilik record for foto_ktp
        if ($this->nik_hash) {
            $pemilik = \App\Models\Pemilik::where('nik_hash', $this->nik_hash)->first();
            if ($pemilik && $pemilik->foto_ktp) {
                $ktpPath = $pemilik->foto_ktp;
                if (str_starts_with($ktpPath, 'http://') || str_starts_with($ktpPath, 'https://')) {
                    return $ktpPath;
                }
                if (str_starts_with($ktpPath, 'storage/')) {
                    return asset($ktpPath);
                }
                return asset('storage/' . $ktpPath);
            }
        }

        $role = $this->roles->first()->name ?? 'super_admin';
        
        $color = '1d4ed8'; // blue-700
        $background = 'dbeafe'; // blue-100

        if ($role === 'admin_kecamatan') {
            $color = '047857'; // emerald-700
            $background = 'd1fae5'; // emerald-100
        } elseif ($role === 'operator_lapangan') {
            $color = 'b45309'; // amber-700
            $background = 'fef3c7'; // amber-100
        } elseif ($role === 'pelaku_umkm') {
            $color = '6d28d9'; // purple-700
            $background = 'ede9fe'; // purple-100
        }

        $name = urlencode($this->name ?? 'User');
        return "https://ui-avatars.com/api/?name={$name}&color={$color}&background={$background}&bold=true";
    }

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
        static::saving(function ($user) {
            // Only hash NIK if it was actually changed and is not already encrypted
            if ($user->isDirty('nik') && $user->getAttributes()['nik'] ?? null) {
                // getAttributes() bypasses the accessor to get the raw encrypted value
                // We need to decrypt first, then hash the plaintext
                try {
                    $plainNik = \Illuminate\Support\Facades\Crypt::decryptString($user->getAttributes()['nik']);
                    $user->nik_hash = hash('sha256', $plainNik);
                } catch (\Exception $e) {
                    // If decryption fails, the value might already be plaintext (edge case)
                    $user->nik_hash = hash('sha256', $user->getAttributes()['nik']);
                }
            }

            // Auto-resolve id_kelurahan from kelurahan name whenever kelurahan is dirty
            if ($user->isDirty('kelurahan') && $user->getAttributes()['kelurahan'] ?? null) {
                $kelurahanName = $user->getAttributes()['kelurahan'];
                $kel = \App\Models\Kelurahan::where('nama_kelurahan', $kelurahanName)->first();
                if ($kel) {
                    $user->id_kelurahan = $kel->id;
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

    /**
     * Compute id_kelurahan from the kelurahan name relationship.
     * Use this method explicitly instead of accessing ->id_kelurahan directly.
     */
    public function getResolvedKelurahanIdAttribute()
    {
        if ($this->attributes['kelurahan'] ?? null) {
            $kel = \App\Models\Kelurahan::where('nama_kelurahan', $this->attributes['kelurahan'])->first();
            return $kel ? $kel->id : null;
        }
        return $this->attributes['id_kelurahan'] ?? null;
    }

    /**
     * Relasi ke Kelurahan via FK (id_kelurahan) – gunakan ini dari controller/query builder.
     */
    public function kelurahanRel()
    {
        return $this->belongsTo(\App\Models\Kelurahan::class, 'id_kelurahan');
    }

    /**
     * Accessor agar $user->kelurahan SELALU mengembalikan string nama_kelurahan.
     */
    protected function kelurahan(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($this->relationLoaded('kelurahanRel') && $this->kelurahanRel) {
                    return $this->kelurahanRel->nama_kelurahan;
                }
                if ($value) {
                    return $value;
                }
                if ($this->id_kelurahan) {
                    static $cache = [];
                    if (!array_key_exists($this->id_kelurahan, $cache)) {
                        $cache[$this->id_kelurahan] = \App\Models\Kelurahan::find($this->id_kelurahan)?->nama_kelurahan;
                    }
                    if ($cache[$this->id_kelurahan]) {
                        return $cache[$this->id_kelurahan];
                    }
                }
                return '-';
            },
            set: fn ($value) => $value,
        );
    }

    /**
     * Relasi ke Pemilik (berdasarkan nik_hash)
     */
    public function pemilik()
    {
        return $this->hasOne(\App\Models\Pemilik::class, 'nik_hash', 'nik_hash');
    }

    /**
     * Relasi ke UMKM melalui Pemilik
     */
    public function umkm()
    {
        return $this->hasManyThrough(
            \App\Models\UMKM::class,
            \App\Models\Pemilik::class,
            'nik_hash', // Foreign key on Pemiliks table
            'id_pemilik', // Foreign key on UMKMs table
            'nik_hash', // Local key on Users table
            'id_pemilik' // Local key on Pemiliks table
        );
    }
}
