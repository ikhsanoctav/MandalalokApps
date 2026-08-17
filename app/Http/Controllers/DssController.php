<?php

namespace App\Http\Controllers;

use App\Models\UMKM;
use App\Models\DssAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Services\DssService;

class DssController extends Controller
{
    /**
     * Display the DSS page with pre-loaded chart data from the database.
     */
    public function index(DssService $dssService)
    {
        $user   = auth()->user();
        $layout = 'layouts.petugas';

        if ($user->hasRole('super_admin')) {
            $layout = 'layouts.superadmin';
        } elseif ($user->hasRole('admin_kecamatan')) {
            $layout = 'layouts.admin';
        }

        // Ambil riwayat analisis DSS terdahulu dan format datanya
        $history = DssAnalysis::with('user')->latest()->get()->map(function($h) {
            return [
                'id'         => $h->id,
                'user_id'    => $h->user_id,
                'created_at' => $h->created_at->translatedFormat('d M Y, H:i'),
                'creator'    => $h->user ? $h->user->name : 'Sistem',
                'role'       => $h->user ? ($h->user->hasRole('super_admin') ? 'Super Admin' : 'Admin Kecamatan') : 'Sistem',
            ];
        });

        [$cachedAnalysis, $cachedAt] = $this->getCachedOrLatestAnalysis();

        // Pre-load chart data for instant rendering (no AI needed for charts)
        $chartData = $this->buildChartData();

        $isGenerating = Cache::get('dss_is_generating', false);

        $allRankingPelatihan = $dssService->calculateSAW('pelatihan');

        // Calculate Wilayah Stats
        $tabelWilayah = $allRankingPelatihan->groupBy(function($item) {
            return $item['umkm']->pemilik->kelurahan ?? 'Lainnya';
        })->map(function($items, $kelurahan) {
            $total = $items->count();
            $tinggi = $items->where('skor_akhir', '>=', 80)->count();
            $avgSkor = $items->avg('skor_akhir');
            return [
                'area' => $kelurahan,
                'layak_intervensi' => $total,
                'prioritas_tinggi' => $tinggi,
                'persentase_tinggi' => $total > 0 ? round(($tinggi / $total) * 100) : 0,
                'skor_urgensi' => round($avgSkor, 1),
                'rekomendasi' => $avgSkor >= 80 ? 'Sangat Direkomendasikan' : ($avgSkor >= 60 ? 'Direkomendasikan' : 'Perlu Pendampingan'),
            ];
        })->sortByDesc('skor_urgensi')->values();

        // Calculate Sektor Stats
        $tabelSektor = $allRankingPelatihan->groupBy(function($item) {
            return $item['umkm']->sektor->nama_sektor ?? 'Lainnya';
        })->map(function($items, $sektor) {
            $total = $items->count();
            $tinggi = $items->where('skor_akhir', '>=', 80)->count();
            $avgSkor = $items->avg('skor_akhir');
            return [
                'area' => $sektor,
                'layak_intervensi' => $total,
                'prioritas_tinggi' => $tinggi,
                'persentase_tinggi' => $total > 0 ? round(($tinggi / $total) * 100) : 0,
                'skor_urgensi' => round($avgSkor, 1),
                'rekomendasi' => $avgSkor >= 80 ? 'Sangat Direkomendasikan' : ($avgSkor >= 60 ? 'Direkomendasikan' : 'Perlu Pendampingan'),
            ];
        })->sortByDesc('skor_urgensi')->values();

        $rankingPelatihan = $allRankingPelatihan->take(10); // Ambil Top 10

        return view('dss.index', compact('cachedAnalysis', 'cachedAt', 'layout', 'chartData', 'history', 'isGenerating', 'rankingPelatihan', 'tabelWilayah', 'tabelSektor'));
    }

    /**
     * Build chart data from the database for real-time visualization.
     */
    private function buildChartData(): array
    {
        // 1. Verifikasi Status Distribution
        $total         = UMKM::count();
        $terverifikasi = UMKM::where('status_verifikasi', 'terverifikasi')->count();
        $pending       = UMKM::where('status_verifikasi', 'menunggu_verifikasi')->count();
        $ditolak       = UMKM::where('status_verifikasi', 'ditolak')->count();
        $draft         = UMKM::where('status_verifikasi', 'draft')->count();

        // 2. Top Sektor (Sektor Usaha: Kuliner, Fashion, dll)
        $sektorRaw = UMKM::select('id_sektor', DB::raw('count(*) as total'))
            ->with('sektor')
            ->groupBy('id_sektor')
            ->orderByDesc('total')
            ->take(8)
            ->get()
            ->map(fn($i) => ['label' => $i->sektor?->nama_sektor ?? 'Lainnya', 'value' => $i->total]);

        // 3. Per Kelurahan (Optimized using SQL JOIN)
        $kelurahanRawQuery = DB::table('umkms')
            ->join('pemiliks', 'umkms.id_pemilik', '=', 'pemiliks.id_pemilik')
            ->select(DB::raw('COALESCE(pemiliks.kelurahan, "Lainnya") as kelurahan'), DB::raw('count(*) as total'))
            ->groupBy('kelurahan')
            ->orderByDesc('total')
            ->take(8)
            ->get();
            
        $kelurahanRaw = $kelurahanRawQuery->pluck('total', 'kelurahan');

        // 4. Pertumbuhan Bulanan (6 bulan terakhir)
        $monthlyGrowth = collect(range(5, 0))->map(function ($i) {
            $date  = now()->subMonths($i);
            $label = $date->locale('id')->translatedFormat('M Y');
            $count = UMKM::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            return ['label' => $label, 'value' => $count];
        });

        // 5. Summary stats
        $avgTenagaKerja = UMKM::where('status_verifikasi', 'terverifikasi')
            ->whereNotNull('jumlah_tenaga_kerja')
            ->avg('jumlah_tenaga_kerja');

        $kelurahanTop = $kelurahanRawQuery->first()->kelurahan ?? '-';

        return [
            'summary' => [
                'total'            => $total,
                'terverifikasi'    => $terverifikasi,
                'pending'          => $pending,
                'ditolak'          => $ditolak,
                'tingkat_verifikasi' => $total > 0 ? round(($terverifikasi / $total) * 100, 1) : 0,
                'avg_tenaga_kerja' => round($avgTenagaKerja ?? 0, 1),
                'kelurahan_top'    => $kelurahanTop ?? '-',
                'sektor_top'       => $sektorRaw->first() ? $sektorRaw->first()['label'] : '-',
                'sektor_top_val'   => $sektorRaw->first() ? $sektorRaw->first()['value'] : 0,
            ],
            'status_chart' => [
                'labels' => ['Terverifikasi', 'Menunggu', 'Ditolak', 'Draft'],
                'values' => [$terverifikasi, $pending, $ditolak, $draft],
                'colors' => ['#10b981', '#f59e0b', '#ef4444', '#94a3b8'],
            ],
            'sektor_chart' => [
                'labels' => $sektorRaw->pluck('label')->toArray(),
                'values' => $sektorRaw->pluck('value')->toArray(),
            ],
            'kelurahan_chart' => [
                'labels' => $kelurahanRaw->keys()->toArray(),
                'values' => $kelurahanRaw->values()->toArray(),
            ],
            'growth_chart' => [
                'labels' => $monthlyGrowth->pluck('label')->toArray(),
                'values' => $monthlyGrowth->pluck('value')->toArray(),
            ],
        ];
    }

    /**
     * Generate fresh DSS AI analysis.
     */
    public function generate(Request $request, \App\Services\OllamaService $ollamaService)
    {
        if ($request->input('force') === 'true') {
            Cache::forget('dss_analysis');
            Cache::forget('dss_analysis_time');
        }

        $intervention = $request->input('intervention_focus');
        $intervention = !empty(trim($intervention)) ? trim($intervention) : null;

        // Mencegah proses AI terhenti jika user melakukan refresh/pindah halaman
        ignore_user_abort(true);
        set_time_limit(260);
        Cache::put('dss_is_generating', true, 300);

        try {
            if (config('services.n8n.dss_async', true)) {
                $jobId = (string) \Illuminate\Support\Str::uuid();
                $token = \Illuminate\Support\Str::random(32);
                $stats = $this->buildDssStats();

                Cache::put("dss_job_token_{$jobId}", $token, 900);
                Cache::put("dss_job_user_{$jobId}", auth()->id(), 900);
                Cache::put("dss_job_stats_{$jobId}", $stats, 900);
                if ($intervention) {
                    Cache::put("dss_job_intervention_{$jobId}", $intervention, 900);
                }

                // Send webhook request to n8n
                $webhookUrl = config('services.n8n.dss_webhook_url');
                $callbackUrl = config('services.n8n.dss_callback_url');
                $apiKey = config('services.n8n.api_key');

                \Illuminate\Support\Facades\Http::withHeaders([
                    'X-N8N-API-KEY' => $apiKey
                ])->post($webhookUrl, [
                    'job_id' => $jobId,
                    'callback_url' => $callbackUrl,
                    'callback_token' => $token,
                    'decision_focus' => $intervention ?? '',
                    'stats_json' => json_encode($stats),
                ]);

                return response()->json([
                    'success' => true,
                    'processing' => true,
                    'job_id' => $jobId,
                    'message' => 'Analisis DSS sedang diproses oleh sistem Ollama secara lokal.',
                ], 202);
            }

            $stats = $this->buildDssStats();
            $reply = $ollamaService->generateDssReport($stats, $intervention);
            
            if (is_null($reply) || empty($reply)) {
                throw new \Exception('Ollama connection failed');
            }

            $analysis = $this->storeDssAnalysis($reply, $stats, auth()->id(), $intervention);

            Cache::forget('dss_is_generating');

            return response()->json([
                'success'   => true,
                'analysis'  => $analysis,
                'cached_at' => Cache::get('dss_analysis_time'),
                'chartData' => $this->buildChartData(),
            ]);

        } catch (\Exception $e) {
            Log::error('DssController: Error generating analysis: ' . $e->getMessage(), ['exception' => $e]);

            Cache::forget('dss_is_generating');

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat analisis kebijakan AI. Detail: ' . $e->getMessage()
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        $jobId = (string) $request->input('job_id');
        $token = (string) $request->input('token');
        $reply = $request->input('output');

        if (!$jobId || !$token || !$reply) {
            return response()->json(['success' => false, 'message' => 'Payload callback DSS tidak lengkap.'], 422);
        }

        $expectedToken = Cache::get("dss_job_token_{$jobId}");
        if (!$expectedToken || !hash_equals($expectedToken, $token)) {
            return response()->json(['success' => false, 'message' => 'Token callback DSS tidak valid.'], 403);
        }

        try {
            $stats = Cache::get("dss_job_stats_{$jobId}") ?? $this->buildDssStats();
            $userId = Cache::get("dss_job_user_{$jobId}");
            $intervention = Cache::get("dss_job_intervention_{$jobId}");
            $analysis = $this->storeDssAnalysis($reply, $stats, $userId, $intervention);

            $this->forgetDssJob($jobId);
            Cache::forget('dss_is_generating');

            return response()->json([
                'success' => true,
                'analysis_length' => strlen($analysis),
            ]);
        } catch (\Exception $e) {
            Log::error('DssController: Error storing async DSS callback: ' . $e->getMessage(), ['exception' => $e]);
            Cache::forget('dss_is_generating');

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function buildDssStats(): array
    {
        $totalUMKM     = UMKM::count();
        $terverifikasi = UMKM::where('status_verifikasi', 'terverifikasi')->count();
        $pending       = UMKM::where('status_verifikasi', 'menunggu_verifikasi')->count();
        $ditolak       = UMKM::where('status_verifikasi', 'ditolak')->count();

        $kategoriStats = UMKM::with('kategori')
            ->get()
            ->groupBy(fn($i) => $i->kategori?->nama_kategori ?? 'Tanpa Kategori')
            ->map->count()
            ->sortDesc()
            ->toArray();

        $sektorStats = UMKM::with('sektor')
            ->get()
            ->groupBy(fn($i) => $i->sektor?->nama_sektor ?? 'Tanpa Sektor')
            ->map->count()
            ->sortDesc()
            ->toArray();

        $kelurahanStats = DB::table('umkms')
            ->join('pemiliks', 'umkms.id_pemilik', '=', 'pemiliks.id_pemilik')
            ->select(DB::raw('COALESCE(pemiliks.kelurahan, "Lainnya") as kelurahan'), DB::raw('count(*) as total'))
            ->groupBy('kelurahan')
            ->orderByDesc('total')
            ->get()
            ->pluck('total', 'kelurahan')
            ->toArray();

        $monthlyGrowth = collect(range(5, 0))->mapWithKeys(function ($i) {
            $date = now()->subMonths($i);
            return [
                $date->locale('id')->translatedFormat('F Y') => UMKM::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            ];
        })->toArray();

        $avgTenagaKerja = UMKM::where('status_verifikasi', 'terverifikasi')
            ->whereNotNull('jumlah_tenaga_kerja')
            ->avg('jumlah_tenaga_kerja');

        // Ambil Top 5 UMKM dari AHP-SAW
        $topSaw = \App\Models\DssHasilSaw::with('umkm', 'umkm.pemilik')
            ->whereNull('kriteria_id')
            ->where('profil', 'default')
            ->orderBy('ranking')
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'ranking' => $item->ranking,
                    'nama_usaha' => $item->umkm->nama_usaha ?? 'Unknown',
                    'skor' => round($item->total_skor, 4),
                    'kelurahan' => $item->umkm->pemilik->kelurahan ?? 'Lainnya'
                ];
            })->toArray();

        return [
            'Ringkasan Sistem' => [
                'Total Seluruh UMKM Terdaftar'  => $totalUMKM,
                'UMKM Terverifikasi'            => $terverifikasi,
                'UMKM Menunggu Verifikasi'      => $pending,
                'UMKM Ditolak'                  => $ditolak,
                'Tingkat Verifikasi (%)'        => $totalUMKM > 0 ? round(($terverifikasi / $totalUMKM) * 100, 1) : 0,
                'Rata-rata Tenaga Kerja per UMKM' => round($avgTenagaKerja ?? 0, 1),
            ],
            'Distribusi Berdasarkan Kategori Usaha' => $kategoriStats,
            'Distribusi Berdasarkan Sektor Usaha'   => $sektorStats,
            'Distribusi Berdasarkan Kelurahan'      => $kelurahanStats,
            'Pertumbuhan UMKM 6 Bulan Terakhir'     => $monthlyGrowth,
            'Kelurahan Kritis (UMKM < 5)'           => collect($kelurahanStats)->filter(fn($v) => $v < 5)->keys()->toArray(),
            'Top 5 Prioritas UMKM (Hasil SAW)'      => $topSaw,
        ];
    }

    private function dssDecisionFocusInstruction(?string $intervention = null): string
    {
        $prompt = <<<PROMPT
Anda adalah staf ahli ekonomi dan analis kebijakan publik (PNS) di lingkungan Kecamatan / Dinas Pemerintahan. Tugas utama Anda adalah merumuskan STRATEGI PEMBINAAN UMKM dan PROGRAM KERJA PEMERINTAH.
Sistem DSS ini ditujukan untuk memetakan kondisi ekonomi warga, BUKAN untuk mengembangkan aplikasi atau software.

ATURAN MUTLAK (DILARANG DILANGGAR):
1. DILARANG KERAS memberikan rekomendasi teknis IT, seperti: "kembangkan sistem", "buat aplikasi", "perbaiki UI/UX", "optimalkan database", "integrasi platform", atau hal teknis software lainnya.
2. Semua program yang diusulkan harus spesifik pada ranah pemerintah/pembinaan (misal: "Pelatihan Kemasan Produk", "Bantuan Modal Mesin Jahit", "Pameran/Bazar UMKM Tingkat Kecamatan", "Pelatihan Higienitas Kuliner").
3. Fokus analisis ini HANYA untuk tingkat KECAMATAN secara LOKAL. JANGAN PERNAH menyarankan kebijakan tingkat Provinsi, Nasional, atau menyebut kata "Indonesia". Anda hanya melayani satu wilayah kelurahan/kecamatan berdasarkan data ini.
4. Analisis HANYA berdasarkan data statistik yang diberikan. Gunakan angka literal hasil perhitungan akhir Anda.
5. Kembalikan HANYA JSON valid. Jangan menggunakan teks pembuka, markdown di luar nilai JSON, atau catatan tambahan.
PROMPT;

        if ($intervention) {
            $prompt .= <<<EOF

PERINTAH INTERVENSI KEBIJAKAN DARI USER: "{$intervention}"

INSTRUKSI UTAMA:
Anda WAJIB menjawab perintah intervensi di atas secara LENGKAP, MENDETAIL, dan SPESIFIK.
Jawaban Anda harus BENAR-BENAR menjawab apa yang diminta user, bukan sekadar mengulang analisis umum.
Gunakan data statistik yang diberikan sebagai landasan faktual.
Ekstrak secara cerdas sasaran, hasil yang diharapkan, atau pendekatan JIKA pengguna sudah menyebutkannya secara eksplisit atau implisit di teks intervensinya. Gunakan untuk mengisi field `sasaran_program`, `hasil_diharapkan`, dan `pendekatan`.

Contoh bentuk jawaban yang diharapkan (sesuaikan dengan permintaan user):
- Jika user minta program kerja → buatkan tabel program kerja lengkap (nama program, sasaran, anggaran estimasi, timeline, indikator keberhasilan)
- Jika user minta stimulus kegiatan → buatkan daftar kegiatan stimulus yang konkret
- Jika user minta analisis kelurahan tertentu → analisis mendalam kelurahan tersebut
- Jika user minta rencana anggaran → buatkan matriks anggaran per program

Format JSON yang WAJIB dan HARUS dikembalikan secara absolut (TIDAK BOLEH DIBUNGKUS DALAM OBJEK LAIN, HARUS STRUKTUR ROOT):
PERHATIAN SUPER PENTING: Di akhir prompt ini Anda mungkin melihat sistem n8n menempelkan "schema" default. Anda WAJIB MENGABAIKAN schema default n8n tersebut dan HANYA GUNAKAN STRUKTUR JSON DI BAWAH INI (karena struktur ini memiliki key 'intervensi_kebijakan' yang sangat penting):
{
  "ringkasan_eksekutif": "2-3 kalimat yang merangkum jawaban Anda terhadap permintaan intervensi user",
  "intervensi_kebijakan": {
    "fokus": "Tuliskan fokus dari intervensi ini",
    "tujuan_utama": "Tujuan utama berdasarkan intervensi",
    "sasaran_program": "Sasaran program",
    "pendekatan": "Pendekatan strategis yang disarankan",
    "hasil_diharapkan": "Target akhir atau hasil yang diharapkan"
  },
  "sparklines": {
    "kesiapan_digital": [35, 38, 36, 40, 41, 42],
    "penggunaan_instagram": [22, 23, 25, 25, 27, 28],
    "penggunaan_marketplace": [12, 14, 15, 14, 15, 15],
    "kualitas_konten": [4.5, 4.8, 5.0, 5.1, 5.3, 5.4],
    "branding_produk": [5.1, 5.3, 5.5, 5.8, 6.0, 6.2]
  },
  "hasil_intervensi_khusus": "[Isi dengan jawaban lengkap dalam format Markdown. Buat tabel Markdown atau grafik mermaid.js (```mermaid) jika diminta pengguna. Sesuaikan dengan detail permintaan intervensi.]",
  "keputusan_strategis_utama": ["Keputusan 1", "Keputusan 2", "Keputusan 3"],
  "indikator_kunci": {
    "jumlah_kelurahan_kritis": 0,
    "rata_rata_tenaga_kerja": 0,
    "kategori_paling_dominan": "",
    "sektor_paling_dominan": "",
    "kelurahan_paling_aktif": ""
  },
  "analisis_swot": {
    "kekuatan": ["poin", "poin"],
    "kelemahan": ["poin", "poin"],
    "peluang": ["poin", "poin"],
    "ancaman": ["poin", "poin"]
  },
  "analisis_kategori": {
    "kategori_dominan": "",
    "persentase_dominan": 0,
    "insight_ekosistem": "Implikasi dominasi kategori terhadap arah program pembinaan dan bantuan",
    "potensi_kolaborasi": "Kolaborasi program dengan kelurahan, komunitas usaha, pasar, koperasi, BUMD, kampus, atau pelaku swasta"
  },
  "analisis_sektor": {
    "sektor_dominan": "",
    "insight_dan_risiko": "Implikasi konsentrasi sektor terhadap ekonomi wilayah",
    "rekomendasi_diversifikasi": "Keputusan program untuk mendorong sektor pelengkap, akses pasar, kemitraan, atau pelatihan usaha"
  },
  "analisis_wilayah": {
    "kelurahan_terkuat": "",
    "kelurahan_perlu_perhatian": ["nama"],
    "insight_ketimpangan": "Ketimpangan sebaran UMKM dan konsekuensinya bagi prioritas pembinaan wilayah",
    "strategi_pemerataan": "Keputusan pemerataan: prioritas kelurahan, jadwal intervensi, dan bentuk dukungan"
  },
  "analisis_pertumbuhan": {
    "tren_umum": "Makna tren pertumbuhan bagi momentum kebijakan UMKM",
    "bulan_terbaik": "",
    "insight_musiman": "Peluang kalender program, event pasar, atau musim usaha jika terlihat dari data",
    "proyeksi": "Arah kebijakan 3-6 bulan berdasarkan tren data"
  },
  "rekomendasi_kebijakan": {
    "jangka_pendek_1_3_bulan": [
      "Keputusan cepat yang bisa dipimpin langsung oleh kecamatan/dinas",
      "Prioritas wilayah/sektor yang perlu dieksekusi segera",
      "Arahan koordinasi petugas/kelurahan/mitra"
    ],
    "jangka_menengah_3_6_bulan": [
      "Program pembinaan atau kemitraan yang butuh persiapan",
      "Paket intervensi wilayah/sektor berbasis data"
    ],
    "jangka_panjang_6_12_bulan": [
      "Agenda penguatan ekosistem UMKM lintas wilayah",
      "Kebijakan berulang/tahunan berbasis hasil evaluasi"
    ]
  },
  "program_kerja_kategori": [
    {"kategori": "", "skor_kebutuhan_pelatihan": 80, "usulan_program": "Nama program pembinaan/akses pasar/kemitraan yang spesifik", "alasan": "Alasan dampak kebijakan berdasarkan data"},
    {"kategori": "", "skor_kebutuhan_pelatihan": 75, "usulan_program": "Nama program lain yang spesifik", "alasan": "Alasan dampak kebijakan berdasarkan data"}
  ],
  "prioritas_intervensi": [
    {"area": "Kelurahan/sektor/kategori prioritas", "alasan": "Alasan berbasis data", "skor_urgensi": 9, "aksi_konkret": "Keputusan tindakan lapangan atau program yang harus dijalankan"},
    {"area": "Kelurahan/sektor prioritas 2", "alasan": "Alasan berbasis data", "skor_urgensi": 8, "aksi_konkret": "Keputusan tindakan lapangan kedua"}
  ],
  "catatan_metodologi": "Analisis ini difokuskan pada intervensi kebijakan yang diminta oleh pemegang keputusan."
}
EOF;
        } else {
            $prompt .= <<<EOF

Kembalikan JSON dengan struktur ini:
{
  "ringkasan_eksekutif": "2-3 kalimat ringkasan untuk pimpinan",
  "intervensi_kebijakan": {
    "fokus": "Pengembangan Ekosistem UMKM Menyeluruh",
    "tujuan_utama": "Tujuan utama berdasarkan kondisi terkini",
    "sasaran_program": "Sasaran program secara umum",
    "pendekatan": "Pendekatan strategis yang disarankan",
    "hasil_diharapkan": "Target akhir atau hasil yang diharapkan"
  },
  "sparklines": {
    "kesiapan_digital": [35, 38, 36, 40, 41, 42],
    "penggunaan_instagram": [22, 23, 25, 25, 27, 28],
    "penggunaan_marketplace": [12, 14, 15, 14, 15, 15],
    "kualitas_konten": [4.5, 4.8, 5.0, 5.1, 5.3, 5.4],
    "branding_produk": [5.1, 5.3, 5.5, 5.8, 6.0, 6.2]
  },
  "hasil_intervensi_khusus": "",
  "keputusan_strategis_utama": ["Keputusan 1", "Keputusan 2", "Keputusan 3"],
  "indikator_kunci": {
    "jumlah_kelurahan_kritis": 0,
    "rata_rata_tenaga_kerja": 0,
    "kategori_paling_dominan": "",
    "sektor_paling_dominan": "",
    "kelurahan_paling_aktif": ""
  },
  "analisis_swot": {"kekuatan": [], "kelemahan": [], "peluang": [], "ancaman": []},

  "analisis_kategori": {"kategori_dominan": "", "persentase_dominan": 0, "insight_ekosistem": "", "potensi_kolaborasi": ""},
  "analisis_sektor": {"sektor_dominan": "", "insight_dan_risiko": "", "rekomendasi_diversifikasi": ""},
  "analisis_wilayah": {"kelurahan_terkuat": "", "kelurahan_perlu_perhatian": [], "insight_ketimpangan": "", "strategi_pemerataan": ""},
  "analisis_pertumbuhan": {"tren_umum": "", "bulan_terbaik": "", "insight_musiman": "", "proyeksi": ""},
  "rekomendasi_kebijakan": {
    "jangka_pendek_1_3_bulan": [],
    "jangka_menengah_3_6_bulan": [],
    "jangka_panjang_6_12_bulan": []
  },
  "program_kerja_kategori": [
    {"kategori": "", "skor_kebutuhan_pelatihan": 80, "usulan_program": "", "alasan": ""}
  ],
  "prioritas_intervensi": [
    {"area": "", "alasan": "", "skor_urgensi": 9, "aksi_konkret": ""}
  ],
  "catatan_metodologi": "Analisis dibuat dari statistik database Mandalaloka."
}
EOF;
        }

        return $prompt;
    }

    private function forgetDssJob(string $jobId): void
    {
        Cache::forget("dss_job_token_{$jobId}");
        Cache::forget("dss_job_user_{$jobId}");
        Cache::forget("dss_job_stats_{$jobId}");
        Cache::forget("dss_job_intervention_{$jobId}");
    }

    private function storeDssAnalysis(string $reply, array $stats, ?int $userId, ?string $intervention = null): string
    {
        $decoded = json_decode($reply, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            \Illuminate\Support\Facades\Log::error('Format respons AI rusak atau terpotong', ['reply' => $reply]);
            throw new \Exception('Format respons AI rusak atau terpotong (Bukan JSON valid).');
        }

        // Cari tahu apakah response dibungkus oleh object lain (e.g. data atau output)
        foreach (['data', 'output', 'response', 'json'] as $key) {
            if (isset($decoded[$key])) {
                if (is_string($decoded[$key])) {
                    $parsed = json_decode($decoded[$key], true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
                        $decoded = array_merge($decoded, $parsed);
                        break;
                    }
                } elseif (is_array($decoded[$key])) {
                    $decoded = array_merge($decoded, $decoded[$key]);
                    break;
                }
            }
        }
        
        \Illuminate\Support\Facades\Log::info('DSS AI Decoded Keys:', array_keys($decoded));

        $ringkasan = $stats['Ringkasan Sistem'] ?? [];
        $kategoriStats = $stats['Distribusi Berdasarkan Kategori Usaha'] ?? [];
        $sektorStats = $stats['Distribusi Berdasarkan Sektor Usaha'] ?? [];
        $kelurahanStats = $stats['Distribusi Berdasarkan Kelurahan'] ?? [];
        $kelurahanKritis = $stats['Kelurahan Kritis (UMKM < 5)'] ?? [];

        $decoded['indikator_kunci'] = array_merge(
            [
                'tingkat_verifikasi_persen' => $ringkasan['Tingkat Verifikasi (%)'] ?? $ringkasan['tingkat_verifikasi'] ?? 0,
                'rasio_penolakan_persen'    => $ringkasan['Rasio Penolakan (%)'] ?? $ringkasan['rasio_penolakan'] ?? 0,
            ],
            $decoded['indikator_kunci'] ?? [],
            [
                'jumlah_kelurahan_kritis'   => count($kelurahanKritis),
                'rata_rata_tenaga_kerja'    => $ringkasan['Rata-rata Tenaga Kerja per UMKM'] ?? 0,
                'kategori_paling_dominan'   => count($kategoriStats) > 0 ? array_key_first($kategoriStats) : '-',
                'sektor_paling_dominan'     => count($sektorStats) > 0 ? array_key_first($sektorStats) : '-',
                'kelurahan_paling_aktif'    => count($kelurahanStats) > 0 ? array_key_first($kelurahanStats) : '-',
            ]
        );

        if ($intervention) {
            if (!isset($decoded['intervensi_kebijakan']) || !is_array($decoded['intervensi_kebijakan'])) {
                try {
                    $ollamaUrl = rtrim(config('services.ollama.base_url', 'http://host.docker.internal:11434'), '/') . '/api/generate';
                    $prompt = "Ekstrak sasaran program, pendekatan, dan hasil yang diharapkan dari teks intervensi kebijakan berikut:\n\"$intervention\"\n\nKembalikan HANYA JSON murni (tanpa markdown) dengan format tepat seperti ini:\n{\n  \"tujuan_utama\": \"...\",\n  \"sasaran_program\": \"...\",\n  \"pendekatan\": \"...\",\n  \"hasil_diharapkan\": \"...\"\n}";
                    
                    $response = \Illuminate\Support\Facades\Http::timeout(30)->post($ollamaUrl, [
                        'model' => 'qwen2.5:1.5b',
                        'prompt' => $prompt,
                        'stream' => false,
                        'format' => 'json'
                    ]);
                    
                    if ($response->successful()) {
                        $rawText = $response->json('response');
                        if (is_string($rawText)) {
                            // Hapus blok markdown ```json ... ```
                            $rawText = preg_replace('/```json\s*(.*?)\s*```/is', '$1', $rawText);
                            $rawText = preg_replace('/```\s*(.*?)\s*```/is', '$1', $rawText);
                            $rawText = trim($rawText);
                            
                            $extracted = json_decode($rawText, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($extracted)) {
                                $decoded['intervensi_kebijakan'] = array_merge(['fokus' => $intervention], $extracted);
                            } else {
                                \Illuminate\Support\Facades\Log::warning('Fallback auto-extract JSON invalid', ['raw' => $rawText]);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('Gagal auto-extract intervensi_kebijakan', ['error' => $e->getMessage()]);
                }

                if (!isset($decoded['intervensi_kebijakan'])) {
                    $decoded['intervensi_kebijakan'] = [
                        'fokus' => $intervention,
                        'tujuan_utama' => "Tujuan terkait: " . $intervention,
                    ];
                }
            } else {
                $decoded['intervensi_kebijakan']['fokus'] = $intervention;
            }
        }

        $record = DssAnalysis::create([
            'user_id' => $userId,
            'analysis_data' => $decoded
        ]);

        $decoded['id'] = $record->id;
        $decoded['created_at'] = $record->created_at->translatedFormat('d M Y, H:i');
        $decoded['creator'] = $record->user ? $record->user->name : 'Sistem';

        $analysis = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        Cache::put('dss_analysis', $analysis, 43200);
        Cache::put('dss_analysis_time', now()->format('d M Y, H:i') . ' WIB', 43200);

        return $analysis;
    }

    private function getCachedOrLatestAnalysis(): array
    {
        $cachedAnalysis = Cache::get('dss_analysis');
        $cachedAt = Cache::get('dss_analysis_time');

        if ($cachedAnalysis) {
            return [$cachedAnalysis, $cachedAt];
        }

        $latest = DssAnalysis::with('user')->latest()->first();
        if (!$latest) {
            return [null, null];
        }

        $analysis = $this->formatAnalysisRecord($latest);
        $cachedAt = $latest->created_at->translatedFormat('d M Y, H:i') . ' WIB';

        Cache::put('dss_analysis', $analysis, 43200);
        Cache::put('dss_analysis_time', $cachedAt, 43200);

        return [$analysis, $cachedAt];
    }

    private function formatAnalysisRecord(DssAnalysis $analysis): string
    {
        $data = $analysis->analysis_data;
        if (!is_array($data)) {
            $data = [];
        }

        $data['id'] = $analysis->id;
        $data['created_at'] = $analysis->created_at->translatedFormat('d M Y, H:i');
        $data['creator'] = $analysis->user ? $analysis->user->name : 'Sistem';

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Load specific history analysis from the database.
     */
    public function loadHistory($id)
    {
        try {
            $analysis = DssAnalysis::with('user')->findOrFail($id);

            return response()->json([
                'success'   => true,
                'analysis'  => $this->formatAnalysisRecord($analysis),
                'cached_at' => $analysis->created_at->translatedFormat('d M Y, H:i') . ' WIB',
                'creator'   => $analysis->user ? $analysis->user->name : 'Sistem',
                'id'        => $analysis->id,
            ]);
        } catch (\Exception $e) {
            Log::error('DssController: Error loading history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail riwayat analisis.'
            ], 500);
        }
    }

    /**
     * Delete specific history analysis.
     */
    public function deleteHistory($id)
    {
        try {
            $analysis = DssAnalysis::findOrFail($id);

            // Check if this matches the currently cached analysis
            $cachedAnalysis = Cache::get('dss_analysis');
            if ($cachedAnalysis) {
                $decoded = json_decode($cachedAnalysis, true);
                if (isset($decoded['id']) && $decoded['id'] == $id) {
                    Cache::forget('dss_analysis');
                    Cache::forget('dss_analysis_time');
                }
            }

            $analysis->delete();

            return response()->json([
                'success' => true,
                'message' => 'Riwayat analisis berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            Log::error('DssController: Error deleting history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus riwayat analisis.'
            ], 500);
        }
    }

    /**
     * Check if DSS is currently generating.
     */
    public function checkStatus()
    {
        if (!Cache::has('dss_analysis') && DssAnalysis::exists()) {
            $this->getCachedOrLatestAnalysis();
        }

        return response()->json([
            'is_generating' => Cache::get('dss_is_generating', false),
            'has_analysis' => Cache::has('dss_analysis')
        ]);
    }
}
