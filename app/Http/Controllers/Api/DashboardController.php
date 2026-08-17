<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemilik;
use App\Models\UMKM;
use Illuminate\Http\Request;

/**
 * API Dashboard Controller
 * Statistik ringkas untuk halaman dashboard MandalalokaApk
 */
class DashboardController extends Controller
{
    /**
     * GET /api/dashboard
     * Return statistik berdasarkan role user
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('operator_lapangan')) {
            return $this->dashboardOperator($user);
        }

        if ($user->hasRole('pelaku_umkm')) {
            return $this->dashboardPelaku($user);
        }

        if ($user->hasRole('admin_kecamatan')) {
            return $this->dashboardAdmin($user);
        }

        if ($user->hasRole('super_admin')) {
            return $this->dashboardSuperAdmin();
        }

        return response()->json(['message' => 'Role tidak dikenali.'], 403);
    }

    /**
     * Dashboard untuk Operator Lapangan
     * Hanya menampilkan data UMKM yang dia input sendiri
     */
    private function dashboardOperator($user)
    {
        $base = UMKM::where('id_petugas', $user->id);

        return response()->json([
            'role' => 'operator_lapangan',
            'stats' => [
                'total_umkm' => (clone $base)->count(),
                'draft' => (clone $base)->where('status_verifikasi', 'draft')->count(),
                'menunggu' => (clone $base)->where('status_verifikasi', 'menunggu_verifikasi')->count(),
                'terverifikasi' => (clone $base)->where('status_verifikasi', 'terverifikasi')->count(),
                'ditolak' => (clone $base)->where('status_verifikasi', 'ditolak')->count(),
            ],
            'recent_umkm' => UMKM::where('id_petugas', $user->id)
                ->with(['kategori', 'pemilik'])
                ->latest()
                ->take(5)
                ->get()
                ->map(fn ($u) => $this->formatUmkm($u)),
        ]);
    }

    /**
     * Dashboard untuk Pelaku UMKM
     * Menampilkan data UMKM miliknya sendiri
     */
    private function dashboardPelaku($user)
    {
        $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();

        if (! $pemilik) {
            return response()->json([
                'role' => 'pelaku_umkm',
                'stats' => [
                    'total_umkm' => 0,
                    'draft' => 0,
                    'menunggu' => 0,
                    'terverifikasi' => 0,
                    'ditolak' => 0,
                ],
                'recent_umkm' => [],
            ]);
        }

        $base = UMKM::where('id_pemilik', $pemilik->id_pemilik);

        return response()->json([
            'role' => 'pelaku_umkm',
            'stats' => [
                'total_umkm' => (clone $base)->count(),
                'draft' => (clone $base)->where('status_verifikasi', 'draft')->count(),
                'menunggu' => (clone $base)->where('status_verifikasi', 'menunggu_verifikasi')->count(),
                'terverifikasi' => (clone $base)->where('status_verifikasi', 'terverifikasi')->count(),
                'ditolak' => (clone $base)->where('status_verifikasi', 'ditolak')->count(),
            ],
            'recent_umkm' => UMKM::where('id_pemilik', $pemilik->id_pemilik)
                ->with(['kategori', 'pemilik'])
                ->latest()
                ->take(5)
                ->get()
                ->map(fn ($u) => $this->formatUmkm($u)),
        ]);
    }

    /**
     * Dashboard untuk Admin Kecamatan
     * Menampilkan semua data UMKM di wilayahnya
     */
    private function dashboardAdmin($user)
    {
        return response()->json([
            'role' => 'admin_kecamatan',
            'stats' => [
                'total_umkm' => UMKM::count(),
                'menunggu' => UMKM::where('status_verifikasi', 'menunggu_verifikasi')->count(),
                'terverifikasi' => UMKM::where('status_verifikasi', 'terverifikasi')->count(),
                'ditolak' => UMKM::where('status_verifikasi', 'ditolak')->count(),
                'aktif' => UMKM::where('status_usaha', 'aktif')->count(),
            ],
            'recent_pending' => UMKM::where('status_verifikasi', 'menunggu_verifikasi')
                ->with(['kategori', 'pemilik', 'petugas'])
                ->latest()
                ->take(5)
                ->get()
                ->map(fn ($u) => $this->formatUmkm($u)),
        ]);
    }

    /**
     * Dashboard Super Admin (semua data)
     */
    private function dashboardSuperAdmin()
    {
        return response()->json([
            'role' => 'super_admin',
            'stats' => [
                'total_umkm' => UMKM::count(),
                'menunggu' => UMKM::where('status_verifikasi', 'menunggu_verifikasi')->count(),
                'terverifikasi' => UMKM::where('status_verifikasi', 'terverifikasi')->count(),
                'ditolak' => UMKM::where('status_verifikasi', 'ditolak')->count(),
                'aktif' => UMKM::where('status_usaha', 'aktif')->count(),
            ],
        ]);
    }

    private function formatUmkm($u)
    {
        return [
            'id_umkm' => $u->id_umkm,
            'no_pendaftaran' => $u->no_pendaftaran,
            'nama_usaha' => $u->nama_usaha,
            'status_verifikasi' => $u->status_verifikasi,
            'status_usaha' => $u->status_usaha,
            'kategori' => $u->kategori?->nama_kategori,
            'pemilik' => $u->pemilik?->nama_lengkap,
            'foto_utama' => $u->foto_utama ? asset('storage/'.$u->foto_utama) : null,
            'created_at' => $u->created_at,
        ];
    }
}
