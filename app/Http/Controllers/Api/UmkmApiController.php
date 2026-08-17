<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\InputGpsUmkmRequest;
use App\Http\Requests\Api\StoreUmkmRequest;
use App\Http\Requests\Api\UploadFotoUmkmRequest;
use App\Models\Pemilik;
use App\Models\UMKM;
use App\Models\User;
use App\Notifications\SystemNotification;
use App\Services\AktivitasLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * API UMKM Controller
 * CRUD data UMKM untuk Operator Lapangan & Admin Kecamatan
 */
class UmkmApiController extends Controller
{
    /**
     * GET /api/umkm
     * List UMKM (filter by role & pagination)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = UMKM::with(['kategori', 'sektor', 'pemilik', 'petugas']);

        // Operator hanya bisa lihat data yang dia input
        if ($user->hasRole('operator_lapangan')) {
            $query->where('id_petugas', $user->id);
        }

        // Pelaku UMKM hanya bisa lihat data miliknya sendiri
        if ($user->hasRole('pelaku_umkm')) {
            $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();
            if ($pemilik) {
                $query->where('id_pemilik', $pemilik->id_pemilik);
            } else {
                $query->whereRaw('1 = 0'); // Jika belum ada data pemilik, tidak tampil data apapun
            }
        }

        // Filter opsional
        if ($request->status_verifikasi) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }
        if ($request->status_usaha) {
            $query->where('status_usaha', $request->status_usaha);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_usaha', 'like', "%{$request->search}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$request->search}%");
            });
        }

        $umkms = $query->latest()->paginate(15);

        return response()->json([
            'data' => $umkms->map(fn ($u) => $this->formatUmkm($u)),
            'pagination' => [
                'current_page' => $umkms->currentPage(),
                'last_page' => $umkms->lastPage(),
                'per_page' => $umkms->perPage(),
                'total' => $umkms->total(),
            ],
        ]);
    }

    /**
     * POST /api/umkm
     * Buat data UMKM baru (Operator Lapangan)
     */
    public function store(StoreUmkmRequest $request)
    {
        $user = $request->user();
        if (! $user->hasRole(['operator_lapangan', 'super_admin'])) {
            return response()->json(['message' => 'Tidak memiliki akses.'], 403);
        }

        DB::beginTransaction();
        try {
            // Cek apakah pemilik sudah ada (berdasarkan NIK hash)
            $nikHash = hash('sha256', $request->nik);
            $pemilik = Pemilik::where('nik_hash', $nikHash)->first();

            if (! $pemilik) {
                $pemilik = Pemilik::create([
                    'nik' => $request->nik,
                    'nama_lengkap' => $request->nama_lengkap,
                    'no_hp' => $request->no_hp,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'alamat' => $request->alamat_pemilik,
                    'kelurahan' => $request->kelurahan,
                    'kecamatan' => 'Mandalajati',
                    'kota_kab' => 'Kota Bandung',
                    'provinsi' => 'Jawa Barat',
                ]);
            }

            $umkm = UMKM::create([
                'nama_usaha' => $request->nama_usaha,
                'id_pemilik' => $pemilik->id_pemilik,
                'id_kategori' => $request->id_kategori,
                'id_sektor' => $request->id_sektor,
                'status_usaha' => $request->status_usaha,
                'tahun_berdiri' => $request->tahun_berdiri,
                'no_izin_usaha' => $request->no_izin_usaha,
                'jenis_izin' => $request->jenis_izin,
                'npwp_usaha' => $request->npwp_usaha,
                'telp_usaha' => $request->telp_usaha,
                'email_usaha' => $request->email_usaha,
                'website' => $request->website,
                'jumlah_tenaga_kerja' => $request->jumlah_tenaga_kerja ?? 0,
                'tenaga_kerja_laki' => $request->tenaga_kerja_laki ?? 0,
                'tenaga_kerja_perempuan' => $request->tenaga_kerja_perempuan ?? 0,
                'alamat_usaha' => $request->alamat_usaha,
                'deskripsi' => $request->deskripsi,
                'id_petugas' => $user->id,
                'tanggal_pendataan' => now(),
                'status_verifikasi' => 'terverifikasi',
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Data UMKM berhasil disimpan.',
                'data' => $this->formatUmkm($umkm->load(['kategori', 'pemilik'])),
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Gagal menyimpan data: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/umkm/{id}
     * Detail UMKM
     */
    public function show(Request $request, $id)
    {
        $umkm = UMKM::with(['kategori', 'sektor', 'pemilik', 'petugas'])->find($id);

        if (! $umkm) {
            return response()->json(['message' => 'Data UMKM tidak ditemukan.'], 404);
        }

        $user = $request->user();
        // Operator hanya bisa lihat data sendiri
        if ($user->hasRole('operator_lapangan') && $umkm->id_petugas != $user->id) {
            return response()->json(['message' => 'Tidak memiliki akses.'], 403);
        }

        // Pelaku UMKM hanya bisa lihat data sendiri
        if ($user->hasRole('pelaku_umkm')) {
            $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();
            if (! $pemilik || $umkm->id_pemilik != $pemilik->id_pemilik) {
                return response()->json(['message' => 'Tidak memiliki akses.'], 403);
            }
        }

        return response()->json(['data' => $this->formatUmkm($umkm, true)]);
    }

    /**
     * PUT /api/umkm/{id}
     * Update data UMKM (hanya jika status draft atau ditolak)
     */
    public function update(Request $request, $id)
    {
        $umkm = UMKM::find($id);
        if (! $umkm) {
            return response()->json(['message' => 'Data UMKM tidak ditemukan.'], 404);
        }

        $user = $request->user();
        if ($user->hasRole('operator_lapangan') && $umkm->id_petugas != $user->id) {
            return response()->json(['message' => 'Tidak memiliki akses.'], 403);
        }

        if (! in_array($umkm->status_verifikasi, ['draft', 'ditolak', 'menunggu_verifikasi'])) {
            if (! $user->hasRole(['admin_kecamatan', 'super_admin'])) {
                return response()->json([
                    'message' => 'Data hanya bisa diedit saat status draft, ditolak, atau menunggu_verifikasi.',
                ], 422);
            }
        }

        $data = $request->only([
            'status_usaha', 'tahun_berdiri', 'no_izin_usaha', 'jenis_izin',
            'npwp_usaha', 'telp_usaha', 'email_usaha', 'website',
            'jumlah_tenaga_kerja', 'tenaga_kerja_laki', 'tenaga_kerja_perempuan',
            'alamat_usaha', 'deskripsi', 'media_sosial',
        ]);

        if (array_key_exists('jumlah_tenaga_kerja', $data)) {
            $data['jumlah_tenaga_kerja'] = $data['jumlah_tenaga_kerja'] ?? 0;
        }
        if (array_key_exists('tenaga_kerja_laki', $data)) {
            $data['tenaga_kerja_laki'] = $data['tenaga_kerja_laki'] ?? 0;
        }
        if (array_key_exists('tenaga_kerja_perempuan', $data)) {
            $data['tenaga_kerja_perempuan'] = $data['tenaga_kerja_perempuan'] ?? 0;
        }

        if ($request->nik) {
            $nikHash = hash('sha256', $request->nik);
            $pemilik = Pemilik::where('nik_hash', $nikHash)->first();

            if (! $pemilik) {
                $pemilik = Pemilik::create([
                    'nik' => $request->nik,
                    'nama_lengkap' => $request->nama_lengkap,
                    'no_hp' => $request->no_hp,
                    'jenis_kelamin' => $request->jenis_kelamin ?? 'L',
                    'alamat' => $request->alamat_pemilik,
                    'kelurahan' => $request->kelurahan,
                    'kecamatan' => 'Mandalajati',
                    'kota_kab' => 'Kota Bandung',
                    'provinsi' => 'Jawa Barat',
                ]);
            } else {
                $pemilik->update([
                    'nama_lengkap' => $request->nama_lengkap ?? $pemilik->nama_lengkap,
                    'no_hp' => $request->no_hp ?? $pemilik->no_hp,
                    'jenis_kelamin' => $request->jenis_kelamin ?? $pemilik->jenis_kelamin,
                    'alamat' => $request->alamat_pemilik ?? $pemilik->alamat,
                    'kelurahan' => $request->kelurahan ?? $pemilik->kelurahan,
                ]);
            }
            $data['id_pemilik'] = $pemilik->id_pemilik;
        }

        $umkm->update($data);

        if ($umkm->status_verifikasi !== 'terverifikasi') {
            $umkm->update([
                'status_verifikasi' => 'terverifikasi',
                'tanggal_verifikasi' => $umkm->tanggal_verifikasi ?? now(),
                'catatan_penolakan' => null,
            ]);
        }

        AktivitasLogger::log(
            "{$user->name} memperbarui data UMKM {$umkm->nama_usaha} melalui Aplikasi",
            'update',
            $umkm->id_umkm
        );

        $user->notify(new SystemNotification(
            'UMKM Diperbarui',
            'Data UMKM "'.$umkm->nama_usaha.'" berhasil Anda perbarui melalui Aplikasi.',
            'info',
            route('admin.umkm.show', $umkm->id_umkm)
        ));

        if ($umkm->id_petugas && $umkm->id_petugas != $user->id) {
            $petugas = User::find($umkm->id_petugas);
            if ($petugas) {
                $petugas->notify(new SystemNotification(
                    'Perubahan Data UMKM',
                    'Data UMKM "'.$umkm->nama_usaha.'" yang Anda daftarkan telah diperbarui oleh '.$user->name.'.',
                    'info',
                    route('operator.umkm.show', $umkm->id_umkm)
                ));
            }
        }

        $superAdmins = User::role('super_admin')->get();
        foreach ($superAdmins as $superAdmin) {
            if ($superAdmin->id != $user->id) {
                $superAdmin->notify(new SystemNotification(
                    'Perubahan Data UMKM',
                    'Data UMKM "'.$umkm->nama_usaha.'" telah diperbarui oleh '.$user->name.'.',
                    'info',
                    route('superadmin.umkm.show', $umkm->id_umkm)
                ));
            }
        }

        return response()->json([
            'message' => 'Data UMKM berhasil diperbarui.',
            'data' => $this->formatUmkm($umkm->fresh()->load(['kategori', 'pemilik'])),
        ]);
    }

    /**
     * POST /api/umkm/{id}/upload-foto
     * Upload foto utama UMKM
     */
    public function uploadFoto(UploadFotoUmkmRequest $request, $id)
    {

        $umkm = UMKM::find($id);
        if (! $umkm) {
            return response()->json(['message' => 'Data UMKM tidak ditemukan.'], 404);
        }

        $user = $request->user();
        if ($user->hasRole('operator_lapangan') && $umkm->id_petugas != $user->id) {
            return response()->json(['message' => 'Tidak memiliki akses.'], 403);
        }

        $file = $request->file('foto');
        $path = $file->store("umkm/{$id}", 'public');

        if ($request->tipe === 'utama') {
            // Hapus foto utama lama
            if ($umkm->foto_utama) {
                Storage::disk('public')->delete($umkm->foto_utama);
            }
            $umkm->update(['foto_utama' => $path]);
        } else {
            // Tambah ke gallery
            $gallery = $umkm->foto_gallery ?: [];
            $gallery[] = $path;
            $umkm->update(['foto_gallery' => $gallery]);
        }

        return response()->json([
            'message' => 'Foto berhasil diupload.',
            'url' => asset('storage/'.$path),
        ]);
    }

    /**
     * POST /api/umkm/{id}/input-gps
     * Input koordinat GPS lokasi usaha
     */
    public function inputGps(InputGpsUmkmRequest $request, $id)
    {

        $umkm = UMKM::find($id);
        if (! $umkm) {
            return response()->json(['message' => 'Data UMKM tidak ditemukan.'], 404);
        }

        $user = $request->user();
        if ($user->hasRole('operator_lapangan') && $umkm->id_petugas != $user->id) {
            return response()->json(['message' => 'Tidak memiliki akses.'], 403);
        }

        // Store GPS in the dedicated latitude/longitude columns
        $umkm->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'message' => 'Koordinat GPS berhasil disimpan.',
            'gps' => [
                'lat' => $umkm->latitude,
                'lng' => $umkm->longitude,
            ],
        ]);
    }

    /**
     * Format data UMKM untuk response API
     */
    private function formatUmkm($umkm, $detail = false)
    {
        $data = [
            'id_umkm' => $umkm->id_umkm,
            'no_pendaftaran' => $umkm->no_pendaftaran,
            'nama_usaha' => $umkm->nama_usaha,
            'status_usaha' => $umkm->status_usaha,
            'status_verifikasi' => $umkm->status_verifikasi,
            'catatan_penolakan' => $umkm->catatan_penolakan,
            'kategori' => $umkm->kategori?->nama_kategori,
            'sektor' => $umkm->sektor?->nama_sektor ?? null,
            'pemilik_nama' => $umkm->pemilik?->nama_lengkap,
            'pemilik_no_hp' => $umkm->pemilik?->no_hp,
            'pemilik_nik' => $umkm->pemilik?->nik,
            'pemilik_jenis_kelamin' => $umkm->pemilik?->jenis_kelamin,
            'pemilik_alamat' => $umkm->pemilik?->alamat,
            'pemilik_kelurahan' => $umkm->pemilik?->kelurahan,
            'foto_utama' => $umkm->foto_utama ? asset('storage/'.$umkm->foto_utama) : null,
            'tanggal_pendataan' => $umkm->tanggal_pendataan,
            'created_at' => $umkm->created_at,
        ];

        if ($detail) {
            $data = array_merge($data, [
                'tahun_berdiri' => $umkm->tahun_berdiri,
                'no_izin_usaha' => $umkm->no_izin_usaha,
                'jenis_izin' => $umkm->jenis_izin,
                'npwp_usaha' => $umkm->npwp_usaha,
                'telp_usaha' => $umkm->telp_usaha,
                'email_usaha' => $umkm->email_usaha,
                'website' => $umkm->website,
                'alamat_usaha' => $umkm->alamat_usaha,
                'deskripsi' => $umkm->deskripsi,
                'jumlah_tenaga_kerja' => $umkm->jumlah_tenaga_kerja,
                'tenaga_kerja_laki' => $umkm->tenaga_kerja_laki,
                'tenaga_kerja_perempuan' => $umkm->tenaga_kerja_perempuan,
                'media_sosial' => $umkm->media_sosial,
                'foto_gallery' => collect($umkm->foto_gallery ?: [])
                    ->map(fn ($f) => asset('storage/'.$f))->values(),
                'petugas' => $umkm->petugas?->name,
                'tanggal_verifikasi' => $umkm->tanggal_verifikasi,
                'id_kategori' => $umkm->id_kategori,
                'id_sektor' => $umkm->id_sektor,
            ]);
        }

        return $data;
    }
}
