<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UMKM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PublicStatistikController extends Controller
{
    /**
     * Data untuk Chart di Halaman Depan
     * Menerima parameter ?filter=sektor atau ?filter=kategori
     */
    public function getChartData(Request $request)
    {
        $filter = $request->query('filter', 'kategori'); // default kategori

        // Use Cache to prevent database overload (30 seconds cache)
        $cacheKey = 'public_chart_data_' . $filter;
        
        $responseData = Cache::remember($cacheKey, 30, function() use ($filter) {
            $labels = [];
            $totals = [];
            $percentages = [];
            $colors = ['#0f2e5c', '#1a498b', '#2b65b6', '#d4af37', '#b8902d', '#708090', '#475569', '#10b981', '#3b82f6'];

            $totalAll = UMKM::where('status_verifikasi', 'terverifikasi')->count();
            if ($totalAll == 0) {
                return [
                    'success' => false,
                    'message' => 'Belum ada data',
                ];
            }

            if ($filter === 'sektor') {
                $data = UMKM::where('status_verifikasi', 'terverifikasi')
                    ->select('id_sektor', DB::raw('count(*) as total'))
                    ->with('sektor')
                    ->groupBy('id_sektor')
                    ->orderByDesc('total')
                    ->get();

                foreach ($data as $item) {
                    $labels[] = $item->sektor ? $item->sektor->nama_sektor : 'Tidak Diketahui';
                    $totals[] = $item->total;
                    $percentages[] = round(($item->total / $totalAll) * 100, 1);
                }
            } else {
                // Default: Kategori
                $data = UMKM::where('status_verifikasi', 'terverifikasi')
                    ->select('id_kategori', DB::raw('count(*) as total'))
                    ->with('kategori')
                    ->groupBy('id_kategori')
                    ->orderByDesc('total')
                    ->get();

                foreach ($data as $item) {
                    $labels[] = $item->kategori ? $item->kategori->nama_kategori : 'Tidak Diketahui';
                    $totals[] = $item->total;
                    $percentages[] = round(($item->total / $totalAll) * 100, 1);
                }
            }

            return [
                'success' => true,
                'labels' => $labels,
                'totals' => $totals,
                'percentages' => $percentages,
                'colors' => array_slice($colors, 0, count($labels)),
            ];
        });

        return response()->json($responseData);
    }

    /**
     * Data untuk Stat Cards di Halaman Depan
     */
    public function getStatistics()
    {
        $responseData = Cache::remember('public_statistics_data', 30, function() {
            $totalUmkm = UMKM::where('status_verifikasi', 'terverifikasi')->count();

            $thisMonth = UMKM::where('status_verifikasi', 'terverifikasi')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $growth = $thisMonth > 0 ? "+{$thisMonth} Baru" : 'Stabil';

            $top = UMKM::where('status_verifikasi', 'terverifikasi')
                ->select('id_kategori', DB::raw('count(*) as total'))
                ->with('kategori')
                ->groupBy('id_kategori')
                ->orderByDesc('total')
                ->first();

            $totalSektor = \App\Models\SektorUmkm::count();

            return [
                'success' => true,
                'total_umkm' => $totalUmkm,
                'growth' => $growth,
                'top_kategori' => $top && $top->kategori ? $top->kategori->nama_kategori : '-',
                'top_kategori_total' => $top ? $top->total : 0,
                'total_sektor' => $totalSektor . ' Sektor',
            ];
        });

        return response()->json($responseData);
    }

    /**
     * Data untuk Peta Interaktif UMKM di Halaman Depan
     * Mengembalikan UMKM terverifikasi yang memiliki koordinat
     */
    public function getMapData(Request $request)
    {
        $responseData = Cache::remember('public_map_data', 60, function () {
            $umkms = UMKM::where('status_verifikasi', 'terverifikasi')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->where('latitude', '!=', 0)
                ->where('longitude', '!=', 0)
                ->with(['kategori', 'sektor', 'pemilik'])
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id_umkm,
                        'nama_usaha' => $item->nama_usaha,
                        'kategori' => $item->kategori?->nama_kategori ?? 'Lainnya',
                        'sektor' => $item->sektor?->nama_sektor ?? 'Lainnya',
                        'pemilik' => $item->pemilik?->nama_lengkap ?? '-',
                        'kelurahan' => $item->pemilik?->kelurahan ?? '-',
                        'alamat' => $item->alamat_usaha ?? '-',
                        'telp' => $item->telp_usaha ?? '-',
                        'latitude' => (float) $item->latitude,
                        'longitude' => (float) $item->longitude,
                        'foto' => $item->foto_utama 
                            ? asset('storage/' . $item->foto_utama) 
                            : asset('images/Logo_Mandalaloka.png'),
                        'status_usaha' => $item->status_usaha,
                    ];
                });

            // Ambil daftar filter dari tabel master agar dropdown selalu terisi
            $sektorList = \App\Models\SektorUmkm::orderBy('nama_sektor')
                ->pluck('nama_sektor')
                ->toArray();

            $kelurahanList = \App\Models\Kelurahan::orderBy('nama_kelurahan')
                ->pluck('nama_kelurahan')
                ->toArray();

            return [
                'success' => true,
                'total' => $umkms->count(),
                'data' => $umkms->values()->all(),
                'filters' => [
                    'sektor' => $sektorList,
                    'kelurahan' => $kelurahanList,
                ],
            ];
        });

        return response()->json($responseData);
    }

    /**
     * Get real-time system context (statistics, distribution, news) for chatbot.
     */
    public function getChatbotContext()
    {
        try {
            $totalUmkm = UMKM::where('status_verifikasi', 'terverifikasi')->count();

            $thisMonth = UMKM::where('status_verifikasi', 'terverifikasi')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $growth = $thisMonth > 0 ? "+{$thisMonth} UMKM Baru" : 'Stabil';

            $top = UMKM::where('status_verifikasi', 'terverifikasi')
                ->select('id_kategori', DB::raw('count(*) as total'))
                ->with('kategori')
                ->groupBy('id_kategori')
                ->orderByDesc('total')
                ->first();

            // Get categories distribution
            $kategoriDist = UMKM::where('status_verifikasi', 'terverifikasi')
                ->select('id_kategori', DB::raw('count(*) as total'))
                ->with('kategori')
                ->groupBy('id_kategori')
                ->get()
                ->map(function($item) {
                    return [
                        'nama' => $item->kategori ? $item->kategori->nama_kategori : 'Lainnya',
                        'jumlah' => $item->total
                    ];
                });

            // Get latest news/warta
            $news = \App\Models\Berita::where('status', 'published')
                ->where('published_at', '<=', now())
                ->latest('published_at')
                ->take(3)
                ->get()
                ->map(function($item) {
                    return [
                        'judul' => $item->judul,
                        'penulis' => $item->penulis,
                        'tanggal' => date('d-m-Y', strtotime($item->published_at)),
                        'ringkasan' => \Illuminate\Support\Str::limit(strip_tags($item->konten), 120)
                    ];
                });

            // Get full list of verified UMKMs for AI deep analysis
            $umkmList = UMKM::where('status_verifikasi', 'terverifikasi')
                ->with(['kategori', 'sektor', 'pemilik'])
                ->get()
                ->map(function($item) {
                    return [
                        'nama_usaha' => $item->nama_usaha,
                        'no_pendaftaran' => $item->no_pendaftaran,
                        'kategori' => $item->kategori ? $item->kategori->nama_kategori : 'Lainnya',
                        'sektor' => $item->sektor ? $item->sektor->nama_sektor : 'Lainnya',
                        'nama_pemilik' => $item->pemilik ? $item->pemilik->nama_lengkap : 'Tidak diketahui',
                        'kelurahan' => $item->pemilik ? $item->pemilik->kelurahan : 'Lainnya',
                        'status_usaha' => $item->status_usaha,
                        'tahun_berdiri' => $item->tahun_berdiri,
                        'jenis_izin' => $item->jenis_izin,
                        'no_izin_usaha' => $item->no_izin_usaha,
                        'npwp_usaha' => $item->npwp_usaha,
                        'telp_usaha' => $item->telp_usaha,
                        'email_usaha' => $item->email_usaha,
                        'alamat_usaha' => $item->alamat_usaha,
                        'deskripsi' => $item->deskripsi,
                        'jumlah_tenaga_kerja' => $item->jumlah_tenaga_kerja,
                        'tenaga_kerja_laki' => $item->tenaga_kerja_laki,
                        'tenaga_kerja_perempuan' => $item->tenaga_kerja_perempuan,
                        'media_sosial' => $item->media_sosial,
                    ];
                });
        } catch (\Throwable $e) {
            report($e);

            $totalUmkm = 0;
            $growth = 'Stabil';
            $top = null;
            $kategoriDist = collect();
            $news = collect();
            $umkmList = collect();
        }

        return response()->json([
            'success' => true,
            'statistics' => [
                'total_umkm' => $totalUmkm,
                'growth' => $growth,
                'top_kategori' => $top && $top->kategori ? $top->kategori->nama_kategori : '-',
                'top_kategori_total' => $top ? $top->total : 0,
            ],
            'kategori_distribusi' => $kategoriDist,
            'berita_terbaru' => $news,
            'daftar_umkm' => $umkmList
        ]);
    }
}
