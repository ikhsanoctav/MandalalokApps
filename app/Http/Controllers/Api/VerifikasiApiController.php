<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UMKM;
use App\Models\User;
use App\Notifications\SystemNotification;
use App\Services\AktivitasLogger;
use Illuminate\Http\Request;

/**
 * API Verifikasi Controller
 * Untuk Admin Kecamatan menyetujui / menolak data UMKM
 */
class VerifikasiApiController extends Controller
{
    /**
     * GET /api/verifikasi
     * List UMKM untuk kompatibilitas endpoint lama.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (! $user->hasRole(['admin_kecamatan', 'super_admin'])) {
            return response()->json(['message' => 'Tidak memiliki akses.'], 403);
        }

        $query = UMKM::with(['kategori', 'pemilik', 'petugas'])
            ->where('status_verifikasi', 'menunggu_verifikasi');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_usaha', 'like', "%{$request->search}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$request->search}%");
            });
        }

        $umkms = $query->latest()->paginate(15);

        return response()->json([
            'data' => $umkms->map(fn ($u) => [
                'id_umkm' => $u->id_umkm,
                'no_pendaftaran' => $u->no_pendaftaran,
                'nama_usaha' => $u->nama_usaha,
                'status_usaha' => $u->status_usaha,
                'kategori' => $u->kategori?->nama_kategori,
                'pemilik_nama' => $u->pemilik?->nama_lengkap,
                'pemilik_no_hp' => $u->pemilik?->no_hp,
                'petugas' => $u->petugas?->name,
                'foto_utama' => $u->foto_utama ? asset('storage/'.$u->foto_utama) : null,
                'tanggal_pendataan' => $u->tanggal_pendataan,
                'created_at' => $u->created_at,
            ]),
            'pagination' => [
                'current_page' => $umkms->currentPage(),
                'last_page' => $umkms->lastPage(),
                'total' => $umkms->total(),
            ],
        ]);
    }

    /**
     * GET /api/verifikasi/{id}
     * Detail UMKM untuk verifikasi
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        if (! $user->hasRole(['admin_kecamatan', 'super_admin'])) {
            return response()->json(['message' => 'Tidak memiliki akses.'], 403);
        }

        $umkm = UMKM::with(['kategori', 'sektor', 'pemilik', 'petugas'])->find($id);

        if (! $umkm) {
            return response()->json(['message' => 'Data UMKM tidak ditemukan.'], 404);
        }

        return response()->json([
            'data' => [
                'id_umkm' => $umkm->id_umkm,
                'no_pendaftaran' => $umkm->no_pendaftaran,
                'nama_usaha' => $umkm->nama_usaha,
                'status_usaha' => $umkm->status_usaha,
                'status_verifikasi' => $umkm->status_verifikasi,
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
                'foto_utama' => $umkm->foto_utama ? asset('storage/'.$umkm->foto_utama) : null,
                'foto_gallery' => collect($umkm->foto_gallery ?: [])
                    ->map(fn ($f) => asset('storage/'.$f))->values(),
                'kategori' => $umkm->kategori?->nama_kategori,
                'sektor' => $umkm->sektor?->nama_sektor ?? null,
                'pemilik' => [
                    'nama_lengkap' => $umkm->pemilik?->nama_lengkap,
                    'no_hp' => $umkm->pemilik?->no_hp,
                    'jenis_kelamin' => $umkm->pemilik?->jenis_kelamin,
                    'alamat' => $umkm->pemilik?->alamat,
                    'kelurahan' => $umkm->pemilik?->kelurahan,
                    'nik_masked' => $umkm->pemilik?->nik_masked,
                ],
                'petugas' => $umkm->petugas?->name,
                'tanggal_pendataan' => $umkm->tanggal_pendataan,
                'created_at' => $umkm->created_at,
            ],
        ]);
    }

    /**
     * POST /api/verifikasi/{id}/verify
     * Setujui / verifikasi UMKM
     */
    public function verify(Request $request, $id)
    {
        $user = $request->user();
        if (! $user->hasRole(['admin_kecamatan', 'super_admin'])) {
            return response()->json(['message' => 'Tidak memiliki akses.'], 403);
        }

        $umkm = UMKM::find($id);
        if (! $umkm) {
            return response()->json(['message' => 'Data UMKM tidak ditemukan.'], 404);
        }

        $umkm->update([
            'status_verifikasi' => 'terverifikasi',
            'tanggal_verifikasi' => $umkm->tanggal_verifikasi ?? now(),
            'catatan_penolakan' => null,
        ]);

        AktivitasLogger::log(
            'Admin Kecamatan memverifikasi UMKM '.$umkm->nama_usaha,
            'verifikasi',
            $id
        );

        $verificationUrl = $user->hasRole('super_admin')
            ? route('superadmin.umkm.show', $umkm->id_umkm)
            : route('admin.verifikasi.show', $umkm->id_umkm);

        $user->notify(new SystemNotification(
            '✅ UMKM Diverifikasi',
            'Pengajuan UMKM "'.$umkm->nama_usaha.'" berhasil disetujui (via Aplikasi).',
            'success',
            $verificationUrl
        ));

        if ($umkm->id_petugas) {
            $petugas = User::find($umkm->id_petugas);
            if ($petugas) {
                $petugas->notify(new SystemNotification(
                    '✅ UMKM Anda Diverifikasi!',
                    'UMKM "'.$umkm->nama_usaha.'" yang Anda daftarkan telah diverifikasi & disetujui oleh Admin Kecamatan.',
                    'success',
                    route('operator.umkm.show', $umkm->id_umkm)
                ));
            }
        }

        $superAdmins = User::role('super_admin')->get();
        foreach ($superAdmins as $superAdmin) {
            $superAdmin->notify(new SystemNotification(
                '✅ UMKM Diverifikasi',
                'UMKM "'.$umkm->nama_usaha.'" telah diverifikasi oleh Admin Kecamatan.',
                'success',
                route('superadmin.umkm.show', $umkm->id_umkm)
            ));
        }

        return response()->json([
            'message' => "UMKM '{$umkm->nama_usaha}' berhasil diverifikasi.",
            'data' => [
                'id_umkm' => $umkm->id_umkm,
                'status_verifikasi' => $umkm->status_verifikasi,
                'tanggal_verifikasi' => $umkm->tanggal_verifikasi,
            ],
        ]);
    }

    /**
     * POST /api/verifikasi/{id}/reject
     * Tolak UMKM dengan catatan
     */
    public function reject(Request $request, $id)
    {
        $user = $request->user();
        if (! $user->hasRole(['admin_kecamatan', 'super_admin'])) {
            return response()->json(['message' => 'Tidak memiliki akses.'], 403);
        }

        $request->validate([
            'catatan' => 'required|string|min:10|max:500',
        ]);

        $umkm = UMKM::find($id);
        if (! $umkm) {
            return response()->json(['message' => 'Data UMKM tidak ditemukan.'], 404);
        }

        $umkm->update([
            'status_verifikasi' => 'ditolak',
            'tanggal_verifikasi' => null,
            'catatan_penolakan' => $request->catatan,
        ]);

        AktivitasLogger::log(
            'Admin Kecamatan menolak UMKM '.$umkm->nama_usaha.' dengan alasan: '.$request->catatan,
            'penolakan',
            $id
        );

        $verificationUrl = $user->hasRole('super_admin')
            ? route('superadmin.umkm.show', $umkm->id_umkm)
            : route('admin.verifikasi.show', $umkm->id_umkm);

        $user->notify(new SystemNotification(
            '❌ UMKM Ditolak',
            'Pengajuan UMKM "'.$umkm->nama_usaha.'" telah ditolak (via Aplikasi).',
            'warning',
            $verificationUrl
        ));

        if ($umkm->id_petugas) {
            $petugas = User::find($umkm->id_petugas);
            if ($petugas) {
                $petugas->notify(new SystemNotification(
                    '❌ UMKM Anda Ditolak',
                    'UMKM "'.$umkm->nama_usaha.'" yang Anda daftarkan ditolak oleh Admin Kecamatan. Alasan: '.$request->catatan,
                    'danger',
                    route('operator.umkm.show', $umkm->id_umkm)
                ));
            }
        }

        $superAdmins = User::role('super_admin')->get();
        foreach ($superAdmins as $superAdmin) {
            $superAdmin->notify(new SystemNotification(
                '❌ UMKM Ditolak',
                'UMKM "'.$umkm->nama_usaha.'" telah ditolak oleh Admin Kecamatan dengan alasan: '.$request->catatan,
                'warning',
                route('superadmin.umkm.show', $umkm->id_umkm)
            ));
        }

        return response()->json([
            'message' => "UMKM '{$umkm->nama_usaha}' berhasil ditolak.",
            'data' => [
                'id_umkm' => $umkm->id_umkm,
                'status_verifikasi' => $umkm->status_verifikasi,
                'catatan_penolakan' => $umkm->catatan_penolakan,
            ],
        ]);
    }
}
