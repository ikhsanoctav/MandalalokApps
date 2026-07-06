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

class DssController extends Controller
{
    /**
     * Display the DSS page with pre-loaded chart data from the database.
     */
    public function index()
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

        return view('dss.index', compact('cachedAnalysis', 'cachedAt', 'layout', 'chartData', 'history', 'isGenerating'));
    }

    /**
     * Build chart data from the database for real-time visualization.
     */
    private function buildChartData(): array
    {
        // 1. Verifikasi Status Distribution
        $total         = UMKM::count();
        $terverifikasi = UMKM::where('status_verifikasi', 'terverifikasi')->count();
        $pending       = UMKM::where('status_verifikasi', 'terkirim')->count();
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

        // 3. Per Kelurahan
        $kelurahanRaw = UMKM::with('pemilik')
            ->get()
            ->groupBy(fn($i) => ($i->pemilik && !empty($i->pemilik->kelurahan)) ? $i->pemilik->kelurahan : 'Lainnya')
            ->map->count()
            ->sortDesc()
            ->take(8);

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

        $kelurahanTop = UMKM::with('pemilik')
            ->get()
            ->groupBy(fn($i) => ($i->pemilik && !empty($i->pemilik->kelurahan)) ? $i->pemilik->kelurahan : 'Lainnya')
            ->map->count()
            ->sortDesc()
            ->keys()
            ->first();

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
                $jobId = (string) Str::uuid();
                $token = Str::random(48);
                $stats = $this->buildDssStats();

                Cache::put("dss_job_token_{$jobId}", $token, 900);
                Cache::put("dss_job_user_{$jobId}", auth()->id(), 900);
                Cache::put("dss_job_stats_{$jobId}", $stats, 900);
                if ($intervention) {
                    Cache::put("dss_job_intervention_{$jobId}", $intervention, 900);
                }

                $webhookUrl = config('services.n8n.dss_webhook_url', 'http://n8n:5678/webhook/dss-api');
                $callbackUrl = config('services.n8n.dss_callback_url', url('/api/dss/callback'));

                $response = Http::timeout(15)->post($webhookUrl, [
                    'job_id' => $jobId,
                    'callback_url' => $callbackUrl,
                    'callback_token' => $token,
                    'decision_focus' => $this->dssDecisionFocusInstruction($intervention),
                    'stats_json' => json_encode($stats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                ]);

                if (!$response->successful()) {
                    $this->forgetDssJob($jobId);
                    Cache::forget('dss_is_generating');
                    throw new \Exception('n8n gagal memulai job DSS. Status: ' . $response->status());
                }

                return response()->json([
                    'success' => true,
                    'processing' => true,
                    'job_id' => $jobId,
                    'message' => 'Analisis DSS sedang diproses oleh n8n dan Ollama.',
                ], 202);
            }

            $analysis = Cache::remember('dss_analysis', 43200, function () use ($ollamaService, $intervention) {
                $stats = $this->buildDssStats();

                $reply = $ollamaService->generateDssReport($stats, $intervention);

                if (is_null($reply) || empty($reply)) {
                    throw new \Exception('Respons kosong dari AI. Harap ulangi.');
                }

                return $this->storeDssAnalysis($reply, $stats, auth()->id(), $intervention);
            });

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
        $pending       = UMKM::where('status_verifikasi', 'terkirim')->count();
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

        $kelurahanStats = UMKM::with('pemilik')
            ->get()
            ->groupBy(fn($i) => ($i->pemilik && !empty($i->pemilik->kelurahan)) ? $i->pemilik->kelurahan : 'Lainnya')
            ->map->count()
            ->sortDesc()
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
        $instructions = [
            'DSS Mandalaloka harus menghasilkan bahan keputusan strategis untuk pimpinan.',
            'ATURAN KELURAHAN: Jika sebuah kelurahan memiliki UMKM TERBANYAK, maka rekomendasikan peningkatan KUALITAS, BUKAN menambah jumlah UMKM. Jika kelurahan memiliki sedikit UMKM, baru rekomendasikan penambahan/sosialisasi.',
            'ATURAN PRIORITAS UMKM: Gunakan data "Top 5 Prioritas UMKM (Hasil SAW)" sebagai landasan utama untuk merekomendasikan UMKM spesifik (atau kelurahannya) yang paling butuh disuntik bantuan/pembinaan.',
            'Jangan memberi rekomendasi teknis pengembangan sistem/aplikasi/database.',
        ];

        if ($intervention) {
            $instructions[] = "PERINTAH INTERVENSI KEBIJAKAN DARI USER: \"{$intervention}\"";
            $instructions[] = "PENTING: Anda WAJIB memprioritaskan perintah intervensi di atas dalam analisis Anda. Jadikan ini sebagai fokus utama laporan strategi, sambil tetap mendasarkan analisis pada data yang diberikan.";
        } else {
            $instructions[] = 'Berikan 3 rekomendasi aksi eksekusi yang spesifik dan masuk akal berdasarkan data di atas.';
        }

        return implode("\n", $instructions);
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
            throw new \Exception('Format respons AI rusak atau terpotong (Bukan JSON valid).');
        }

        $ringkasan = $stats['Ringkasan Sistem'] ?? [];
        $kategoriStats = $stats['Distribusi Berdasarkan Kategori Usaha'] ?? [];
        $sektorStats = $stats['Distribusi Berdasarkan Sektor Usaha'] ?? [];
        $kelurahanStats = $stats['Distribusi Berdasarkan Kelurahan'] ?? [];
        $kelurahanKritis = $stats['Kelurahan Kritis (UMKM < 5)'] ?? [];

        $totalUMKM = (int) ($ringkasan['Total Seluruh UMKM Terdaftar'] ?? 0);
        $terverifikasi = (int) ($ringkasan['UMKM Terverifikasi'] ?? 0);
        $ditolak = (int) ($ringkasan['UMKM Ditolak'] ?? 0);

        $decoded['indikator_kunci'] = [
            'tingkat_verifikasi_persen' => $ringkasan['Tingkat Verifikasi (%)'] ?? ($totalUMKM > 0 ? round(($terverifikasi / $totalUMKM) * 100, 1) : 0),
            'rasio_penolakan_persen'    => $totalUMKM > 0 ? round(($ditolak / $totalUMKM) * 100, 1) : 0,
            'jumlah_kelurahan_kritis'   => count($kelurahanKritis),
            'rata_rata_tenaga_kerja'    => $ringkasan['Rata-rata Tenaga Kerja per UMKM'] ?? 0,
            'kategori_paling_dominan'   => count($kategoriStats) > 0 ? array_key_first($kategoriStats) : '-',
            'sektor_paling_dominan'     => count($sektorStats) > 0 ? array_key_first($sektorStats) : '-',
            'kelurahan_paling_aktif'    => count($kelurahanStats) > 0 ? array_key_first($kelurahanStats) : '-',
        ];

        if ($intervention) {
            $decoded['intervensi_kebijakan'] = $intervention;
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
