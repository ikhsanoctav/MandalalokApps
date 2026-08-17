<?php

namespace App\Http\Traits;

use App\Models\Kelurahan;
use App\Models\SektorUmkm;
use App\Models\UMKM;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Shared dashboard chart and statistics logic used by both
 * Admin\DashboardController and SuperAdmin\DashboardController.
 */
trait DashboardChartTrait
{
    /**
     * Get chart data arrays (labels, pendaftaran, verifikasi) for a given filter period.
     *
     * @return array{labels: array, pendaftaran: array, verifikasi: array}
     */
    protected function getChartDataByFilter(string $filter, ?string $startDate = null, ?string $endDate = null): array
    {
        $labels = [];
        $pendaftaran = [];
        $verifikasi = [];

        switch ($filter) {
            case 'daily':
                $end = $endDate ? Carbon::parse($endDate)->endOfDay() : now()->endOfDay();
                $start = $startDate ? Carbon::parse($startDate)->startOfDay() : now()->subDays(29)->startOfDay();

                for ($date = clone $start; $date->startOfDay() <= $end; $date->addDay()) {
                    $labels[] = $date->format('d M');
                    $pendaftaran[] = UMKM::whereDate('created_at', $date)->count();
                    $verifikasi[] = UMKM::whereDate('tanggal_verifikasi', $date)->count();
                }
                break;

            case 'weekly':
                for ($i = 11; $i >= 0; $i--) {
                    $startWeek = now()->subWeeks($i)->startOfWeek();
                    $endWeek = now()->subWeeks($i)->endOfWeek();
                    $labels[] = 'Minggu ' . now()->subWeeks($i)->weekOfYear;
                    $pendaftaran[] = UMKM::whereBetween('created_at', [$startWeek, $endWeek])->count();
                    $verifikasi[] = UMKM::whereBetween('tanggal_verifikasi', [$startWeek, $endWeek])->count();
                }
                break;

            case 'monthly':
                for ($i = 11; $i >= 0; $i--) {
                    $bulan = now()->subMonths($i);
                    $labels[] = $bulan->format('M Y');
                    $pendaftaran[] = UMKM::whereMonth('created_at', $bulan->month)
                        ->whereYear('created_at', $bulan->year)
                        ->count();
                    $verifikasi[] = UMKM::whereMonth('tanggal_verifikasi', $bulan->month)
                        ->whereYear('tanggal_verifikasi', $bulan->year)
                        ->count();
                }
                break;

            case 'yearly':
                for ($i = 4; $i >= 0; $i--) {
                    $tahun = now()->subYears($i);
                    $labels[] = $tahun->format('Y');
                    $pendaftaran[] = UMKM::whereYear('created_at', $tahun->year)->count();
                    $verifikasi[] = UMKM::whereYear('tanggal_verifikasi', $tahun->year)->count();
                }
                break;
        }

        return compact('labels', 'pendaftaran', 'verifikasi');
    }

    /**
     * Get UMKM count statistics.
     *
     * @return array{totalUmkm: int, umkmAktif: int, menungguVerifikasi: int, pendingCount: int}
     */
    protected function getUmkmStatistics(): array
    {
        $totalUmkm = UMKM::count();
        $umkmAktif = UMKM::where('status_verifikasi', 'terverifikasi')->count();
        $menungguVerifikasi = UMKM::where('status_verifikasi', 'menunggu_verifikasi')->count();

        return [
            'totalUmkm' => $totalUmkm,
            'umkmAktif' => $umkmAktif,
            'menungguVerifikasi' => $menungguVerifikasi,
            'pendingCount' => $menungguVerifikasi,
        ];
    }

    /**
     * Calculate month-over-month growth percentage.
     */
    protected function calculateGrowth(): float
    {
        $bulanIni = UMKM::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $bulanLalu = UMKM::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        if ($bulanLalu > 0) {
            return round(($bulanIni - $bulanLalu) / $bulanLalu * 100, 1);
        }

        return $bulanIni > 0 ? 100 : 0;
    }

    /**
     * Get regional distribution data (UMKM count per kelurahan).
     * Uses id_kelurahan FK for reliable join, falls back to string match for un-backfilled records.
     * Only counts verified UMKM.
     */
    protected function getDistribusiWilayah($sektorId = null)
    {
        // Query 1: pemilik with valid id_kelurahan FK
        $byFK = DB::table('kelurahans')
            ->select('kelurahans.nama_kelurahan', DB::raw('COUNT(umkms.id_umkm) as total'))
            ->join('pemiliks', 'kelurahans.id', '=', 'pemiliks.id_kelurahan')
            ->leftJoin('umkms', function ($join) use ($sektorId) {
                $join->on('pemiliks.id_pemilik', '=', 'umkms.id_pemilik')
                     ->where('umkms.status_verifikasi', '=', 'terverifikasi');
                if ($sektorId) {
                    $join->where('umkms.id_sektor', '=', $sektorId);
                }
            })
            ->groupBy('kelurahans.nama_kelurahan');

        // Query 2: pemilik without id_kelurahan FK (fallback via string match)
        $byName = DB::table('kelurahans')
            ->select('kelurahans.nama_kelurahan', DB::raw('COUNT(umkms.id_umkm) as total'))
            ->join('pemiliks', function ($join) {
                $join->on('kelurahans.nama_kelurahan', '=', 'pemiliks.kelurahan')
                     ->whereNull('pemiliks.id_kelurahan');
            })
            ->leftJoin('umkms', function ($join) use ($sektorId) {
                $join->on('pemiliks.id_pemilik', '=', 'umkms.id_pemilik')
                     ->where('umkms.status_verifikasi', '=', 'terverifikasi');
                if ($sektorId) {
                    $join->where('umkms.id_sektor', '=', $sektorId);
                }
            })
            ->groupBy('kelurahans.nama_kelurahan');

        // Combine with UNION ALL, then aggregate
        $unionQuery = $byFK->unionAll($byName);

        // Wrap in a subquery and sum totals per kelurahan
        return Kelurahan::select('kelurahans.nama_kelurahan', DB::raw('COALESCE(SUM(combined.total), 0) as total'))
            ->leftJoinSub($unionQuery, 'combined', function ($join) {
                $join->on('kelurahans.nama_kelurahan', '=', 'combined.nama_kelurahan');
            })
            ->groupBy('kelurahans.id', 'kelurahans.nama_kelurahan')
            ->orderBy('total', 'desc')
            ->get();
    }

    /**
     * Get sector distribution data (UMKM count per sektor usaha).
     * Only counts verified UMKM.
     */
    protected function getDistribusiSektor($kelurahanId = null)
    {
        return SektorUmkm::select('sektor_umkms.nama_sektor', DB::raw('COUNT(umkms.id_umkm) as total'))
            ->leftJoin('umkms', function ($join) use ($kelurahanId) {
                $join->on('sektor_umkms.id', '=', 'umkms.id_sektor')
                     ->where('umkms.status_verifikasi', '=', 'terverifikasi');
            })
            ->leftJoin('pemiliks', 'umkms.id_pemilik', '=', 'pemiliks.id_pemilik')
            ->when($kelurahanId, function ($query) use ($kelurahanId) {
                $query->where(function ($q) use ($kelurahanId) {
                    $q->where('pemiliks.id_kelurahan', $kelurahanId)
                      ->orWhere(function ($q2) use ($kelurahanId) {
                          $kelurahan = Kelurahan::find($kelurahanId);
                          if ($kelurahan) {
                              $q2->whereNull('pemiliks.id_kelurahan')
                                 ->where('pemiliks.kelurahan', $kelurahan->nama_kelurahan);
                          }
                      });
                });
            })
            ->groupBy('sektor_umkms.id', 'sektor_umkms.nama_sektor')
            ->orderBy('total', 'desc')
            ->get();
    }
    /**
     * Get category distribution data (UMKM count per kategori usaha).
     * Only counts verified UMKM.
     */
    protected function getDistribusiKategori()
    {
        return \App\Models\KategoriUMKM::select('kategori_umkms.nama_kategori', DB::raw('COUNT(umkms.id_umkm) as total'))
            ->leftJoin('umkms', function ($join) {
                $join->on('kategori_umkms.id', '=', 'umkms.id_kategori')
                     ->where('umkms.status_verifikasi', '=', 'terverifikasi');
            })
            ->groupBy('kategori_umkms.id', 'kategori_umkms.nama_kategori')
            ->orderBy('total', 'desc')
            ->get();
    }
    /**
     * Get recent UMKM entries.
     */
    protected function getUmkmTerbaru(int $limit = 5)
    {
        return UMKM::with(['pemilik', 'kategori', 'sektor'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Map UMKM collection to a JSON-safe array (for AJAX responses).
     */
    protected function mapUmkmForJson($umkmCollection): array
    {
        return $umkmCollection->map(function ($umkm) {
            return [
                'id_umkm' => $umkm->id_umkm,
                'nama_usaha' => $umkm->nama_usaha,
                'no_pendaftaran' => $umkm->no_pendaftaran,
                'status_verifikasi' => $umkm->status_verifikasi,
                'pemilik' => $umkm->pemilik ? [
                    'nama_lengkap' => $umkm->pemilik->nama_lengkap,
                    'kelurahan' => $umkm->pemilik->kelurahan,
                ] : null,
            ];
        })->toArray();
    }
}
