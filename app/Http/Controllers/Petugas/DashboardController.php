<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\UMKM;
use App\Models\VerifikasiLapangan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard for petugas lapangan (operator_lapangan).
     */
    public function dashboard(Request $request)
    {
        $user = auth()->user();

        // Get counts for dashboard stats specific to this operator
        $totalInputted = UMKM::where('id_petugas', $user->id ?? 0)->count();
        $totalVerified = UMKM::where('id_petugas', $user->id ?? 0)->where('status_verifikasi', 'terverifikasi')->count();
        $totalRejected = UMKM::where('id_petugas', $user->id ?? 0)->where('status_verifikasi', 'ditolak')->count();

        // Statistik Verifikasi Lapangan
        $kelurahan = $user->kelurahan;
        $totalPerluKunjungan = 0;
        $totalSudahDikunjungi = 0;

        if ($kelurahan && $kelurahan !== '-') {
            $umkmWilayah = UMKM::with('latestVerifikasiLapangan')
                ->where('status_verifikasi', 'terverifikasi')
                ->whereHas('pemilik', function ($q) use ($kelurahan) {
                    $q->where('kelurahan', $kelurahan);
                })->get();

            $totalPerluKunjungan = $umkmWilayah->filter(fn ($u) => ! $u->latestVerifikasiLapangan)->count();
            $totalSudahDikunjungi = $umkmWilayah->filter(fn ($u) => $u->latestVerifikasiLapangan)->count();
        }

        // Recent UMKM inputted by this operator
        $recentUmkm = UMKM::where('id_petugas', $user->id ?? 0)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // For AJAX polling (if needed for realtime updates)
        if ($request->ajax()) {
            return response()->json([
                'counts' => [
                    'total' => $totalInputted,
                    'verified' => $totalVerified,
                    'rejected' => $totalRejected,

                    'perlu_kunjungan' => $totalPerluKunjungan,
                    'sudah_dikunjungi' => $totalSudahDikunjungi,
                ],
            ]);
        }

        return view('petugas.dashboard', compact(
            'totalInputted',
            'totalVerified',
            'totalRejected',
            'totalPerluKunjungan',
            'totalSudahDikunjungi',
            'recentUmkm'
        ));
    }
}
