<?php

namespace App\Services;

use App\Models\Kelurahan;
use App\Models\Pemilik;
use App\Models\UMKM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UmkmService
{
    /**
     * Resolve address components from kelurahan name.
     *
     * @return array{kecamatan: string, kota_kab: string, provinsi: string, kode_pos: string}
     */
    public function resolveAddress(string $kelurahanName): array
    {
        $kelurahan = Kelurahan::where('nama_kelurahan', $kelurahanName)->first();

        return [
            'kecamatan' => $kelurahan?->kecamatan ?? 'Mandalajati',
            'kota_kab' => $kelurahan?->kota_kab ?? 'Bandung',
            'provinsi' => $kelurahan?->provinsi ?? 'Jawa Barat',
            'kode_pos' => '40285',
        ];
    }

    public function normalizeWilayahNumber(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $digits = preg_replace('/\D/', '', $value);
        if ($digits === '') {
            return null;
        }

        return str_pad((string) ((int) $digits), 2, '0', STR_PAD_LEFT);
    }

    /**
     * Find or create a Pemilik from request data.
     */
    public function findOrCreatePemilik(Request $request): Pemilik
    {
        $nikHash = hash('sha256', $request->nik);
        $pemilik = Pemilik::where('nik_hash', $nikHash)->first();

        if (! $pemilik) {
            $address = $this->resolveAddress($request->kelurahan);
            $kelurahan = Kelurahan::where('nama_kelurahan', $request->kelurahan)->first();

            $pemilik = Pemilik::create([
                'id_pemilik' => (string) Str::uuid(),
                'nik' => $request->nik,
                'nama_lengkap' => $request->nama_lengkap ?? $request->nama_pemilik,
                'tempat_lahir' => $request->tempat_lahir ?? '-',
                'tanggal_lahir' => $request->tanggal_lahir ?? '2000-01-01',
                'jenis_kelamin' => $request->jenis_kelamin ?? 'L',
                'no_hp' => $request->no_hp ?? $request->no_telepon ?? '-',
                'email' => $request->email_pemilik ?? $request->email ?? '-',
                'alamat' => $request->alamat_pemilik ?? $request->alamat ?? '-',
                'rt' => $this->normalizeWilayahNumber($request->rt) ?? '00',
                'rw' => $this->normalizeWilayahNumber($request->rw) ?? '00',
                'kelurahan' => $request->kelurahan,
                'id_kelurahan' => $kelurahan?->id,
                'kecamatan' => $address['kecamatan'],
                'kota_kab' => $address['kota_kab'],
                'provinsi' => $address['provinsi'],
                'kode_pos' => $request->kode_pos ?? $address['kode_pos'],
                'status_verifikasi_ktp' => 'terverifikasi',
            ]);
        }

        return $pemilik;
    }

    /**
     * Handle foto utama and gallery uploads.
     *
     * @return array{foto_utama: string|null, foto_gallery: array|null}
     */
    public function handleFotoUploads(Request $request, ?UMKM $existingUmkm = null): array
    {
        $result = [
            'foto_utama' => $existingUmkm?->foto_utama,
            'foto_gallery' => $existingUmkm?->foto_gallery,
        ];

        // Foto Utama
        if ($request->hasFile('foto_utama')) {
            if ($existingUmkm?->foto_utama) {
                Storage::disk('public')->delete($existingUmkm->foto_utama);
            }
            $file = $request->file('foto_utama');
            $filename = time() . '_utama_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $result['foto_utama'] = $file->storeAs('umkm/foto_utama', $filename, 'public');
        }

        // Foto Gallery (append to existing)
        if ($request->hasFile('foto_gallery')) {
            $galleryPaths = $existingUmkm?->foto_gallery ?: [];

            foreach ($request->file('foto_gallery') as $i => $file) {
                $filename = time() . '_gallery_' . $i . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $galleryPaths[] = $file->storeAs('umkm/gallery', $filename, 'public');
            }
            $result['foto_gallery'] = ! empty($galleryPaths) ? $galleryPaths : null;
        }

        return $result;
    }

    /**
     * Build UMKM data array from request.
     *
     * @return array<string, mixed>
     */
    public function buildUmkmData(Request $request, string $idPemilik, ?int $idPetugas = null, string $statusVerifikasi = 'terkirim'): array
    {
        $fotos = $this->handleFotoUploads($request);

        return [
            'id_umkm' => (string) Str::uuid(),
            'nama_usaha' => $request->nama_usaha,
            'id_pemilik' => $idPemilik,
            'id_kategori' => $request->id_kategori,
            'id_sektor' => $request->id_sektor,
            'bentuk_jualan' => $request->bentuk_jualan,
            'status_usaha' => $request->status_usaha ?? 'aktif',
            'tahun_berdiri' => $request->tahun_berdiri,
            'no_izin_usaha' => $request->no_izin_usaha,
            'jenis_izin' => $request->jenis_izin,
            'npwp_usaha' => $request->npwp_usaha,
            'telp_usaha' => $request->telp_usaha,
            'email_usaha' => $request->email_usaha,
            'website' => $request->website,
            'media_sosial' => $request->media_sosial,
            'jumlah_tenaga_kerja' => $request->jumlah_tenaga_kerja ?? 0,
            'tenaga_kerja_laki' => $request->tenaga_kerja_laki ?? 0,
            'tenaga_kerja_perempuan' => $request->tenaga_kerja_perempuan ?? 0,
            'alamat_usaha' => $request->alamat_usaha,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'deskripsi' => $request->deskripsi,
            'foto_utama' => $fotos['foto_utama'],
            'foto_gallery' => $fotos['foto_gallery'],
            'id_petugas' => $idPetugas,
            'tanggal_pendataan' => now(),
            'status_verifikasi' => $statusVerifikasi,
        ];
    }

    /**
     * Update Pemilik data from request (for edit flows).
     */
    public function updatePemilikFromRequest(Pemilik $pemilik, Request $request): Pemilik
    {
        $address = $this->resolveAddress($request->kelurahan);
        $kelurahan = Kelurahan::where('nama_kelurahan', $request->kelurahan)->first();

        $pemilik->update([
            'nama_lengkap' => $request->nama_lengkap ?? $request->nama_pemilik,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp' => $request->no_hp ?? $request->no_telepon,
            'email' => $request->email_pemilik ?? $request->email,
            'kelurahan' => $request->kelurahan,
            'id_kelurahan' => $kelurahan?->id ?? $pemilik->id_kelurahan,
            'kecamatan' => $address['kecamatan'],
            'kota_kab' => $address['kota_kab'],
            'provinsi' => $address['provinsi'],
            'kode_pos' => $request->kode_pos,
            'rw' => $this->normalizeWilayahNumber($request->rw),
            'rt' => $this->normalizeWilayahNumber($request->rt),
            'alamat' => $request->alamat_pemilik ?? $request->alamat,
        ]);

        return $pemilik;
    }

    /**
     * Sync pemilik wilayah data: ensure kelurahan name <-> id_kelurahan are consistent.
     * Call this before rendering edit pages to guarantee dropdown values.
     */
    public function syncPemilikWilayah(Pemilik $pemilik): Pemilik
    {
        $dirty = false;

        // Fallback: If pemilik is missing wilayah data, try to pull it from the associated User account
        $user = \App\Models\User::where('nik_hash', $pemilik->nik_hash)->first();
        if ($user) {
            if (empty($pemilik->id_kelurahan) && $user->id_kelurahan) {
                $pemilik->id_kelurahan = $user->id_kelurahan;
                $dirty = true;
            }
            if (empty($pemilik->rw) && $user->rw) {
                $pemilik->rw = $user->rw;
                $dirty = true;
            }
            if (empty($pemilik->rt) && $user->rt) {
                $pemilik->rt = $user->rt;
                $dirty = true;
            }
        }

        // If id_kelurahan exists, ALWAYS ensure kelurahan name matches exactly (fixes case sensitivity)
        if ($pemilik->id_kelurahan) {
            $kel = Kelurahan::find($pemilik->id_kelurahan);
            if ($kel && $pemilik->kelurahan !== $kel->nama_kelurahan) {
                $pemilik->kelurahan = $kel->nama_kelurahan;
                $dirty = true;
            }
        }

        // If id_kelurahan is empty but kelurahan name exists, resolve the ID
        if (empty($pemilik->id_kelurahan) && !empty($pemilik->kelurahan)) {
            $kel = Kelurahan::where('nama_kelurahan', $pemilik->kelurahan)->first();
            if ($kel) {
                $pemilik->id_kelurahan = $kel->id;
                $pemilik->kelurahan = $kel->nama_kelurahan; // ensure exact casing
                $dirty = true;
            }
        }

        $normalizedRw = $this->normalizeWilayahNumber($pemilik->rw);
        if ($normalizedRw && $pemilik->rw !== $normalizedRw) {
            $pemilik->rw = $normalizedRw;
            $dirty = true;
        }

        $normalizedRt = $this->normalizeWilayahNumber($pemilik->rt);
        if ($normalizedRt && $pemilik->rt !== $normalizedRt) {
            $pemilik->rt = $normalizedRt;
            $dirty = true;
        }

        if ($dirty) {
            $pemilik->save();
        }

        return $pemilik;
    }

    /**
     * Delete UMKM and its associated files.
     */
    public function deleteUmkmWithFiles(UMKM $umkm): void
    {
        if ($umkm->foto_utama && Storage::disk('public')->exists($umkm->foto_utama)) {
            Storage::disk('public')->delete($umkm->foto_utama);
        }

        if ($umkm->foto_gallery) {
            $galleries = is_array($umkm->foto_gallery) ? $umkm->foto_gallery : [];
            foreach ($galleries as $gallery) {
                if (Storage::disk('public')->exists($gallery)) {
                    Storage::disk('public')->delete($gallery);
                }
            }
        }

        $umkm->delete();
    }
}
