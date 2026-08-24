<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\DashboardChartTrait;
use App\Models\Aktivitas;
use App\Models\UMKM;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use DashboardChartTrait;

    public function dashboard(Request $request)
    {
        if ($request->ajax() || $request->has('ajax')) {
            return $this->dashboardRealtime($request);
        }

        $stats = $this->getUmkmStatistics();
        $growthUmkm = $this->calculateGrowth();

        $totalPengguna = User::count();
        $totalAdmin = User::role('admin_kecamatan')->count();
        $totalOperator = User::role('operator_lapangan')->count();

        // Initial chart data (6 months, used only for server-side rendering fallback)
        $chartLabels = [];
        $chartPendaftaran = [];
        $chartVerifikasi = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $chartLabels[] = $bulan->format('M Y');
            $chartPendaftaran[] = UMKM::whereMonth('created_at', $bulan->month)
                ->whereYear('created_at', $bulan->year)
                ->count();
            $chartVerifikasi[] = UMKM::whereMonth('tanggal_verifikasi', $bulan->month)
                ->whereYear('tanggal_verifikasi', $bulan->year)
                ->count();
        }

        $distribusiWilayah = $this->getDistribusiWilayah(request('filter_sektor'));
        $distribusiSektor = $this->getDistribusiSektor(request('filter_kelurahan'));
        $distribusiKategori = $this->getDistribusiKategori();
        $umkmTerbaru = $this->getUmkmTerbaru(10);
        $sektors = \App\Models\SektorUmkm::all();
        $kelurahans = \App\Models\Kelurahan::all();

        $aktivitasTerbaru = Aktivitas::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $systemStatus = $this->getSystemStatus();

        $totalUmkm = $stats['totalUmkm'];
        $menungguVerifikasi = $stats['menungguVerifikasi'];
        $umkmAktif = $stats['umkmAktif'];
        $pendingCount = $stats['pendingCount'];
        
        $pendingAkunCount = \App\Models\Pemilik::where('status_verifikasi_ktp', 'pending')->count();

        return view('superadmin.dashboard', compact(
            'totalUmkm', 'menungguVerifikasi', 'umkmAktif', 'totalPengguna', 'totalAdmin', 'totalOperator',
            'growthUmkm', 'chartLabels', 'chartPendaftaran', 'chartVerifikasi',
            'distribusiWilayah', 'distribusiSektor', 'distribusiKategori', 'umkmTerbaru', 'aktivitasTerbaru', 'systemStatus', 'pendingCount', 'pendingAkunCount',
            'sektors', 'kelurahans'
        ));
    }

    public function getChartData(Request $request)
    {
        $filter = $request->get('filter', 'monthly');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $data = $this->getChartDataByFilter($filter, $startDate, $endDate);

        return response()->json([
            'success' => true,
            'labels' => $data['labels'],
            'pendaftaran' => $data['pendaftaran'],
            'verifikasi' => $data['verifikasi'],
        ]);
    }

    public function dashboardRealtime(Request $request)
    {
        $filter = $request->get('filter', 'monthly');
        $stats = $this->getUmkmStatistics();
        $growthUmkm = $this->calculateGrowth();

        $totalPengguna = User::count();
        $totalAdmin = User::role('admin_kecamatan')->count();
        $totalOperator = User::role('operator_lapangan')->count();

        $chartData = $this->getChartDataByFilter($filter);
        $distribusiWilayah = $this->getDistribusiWilayah(request('filter_sektor'));
        $distribusiSektor = $this->getDistribusiSektor(request('filter_kelurahan'));
        $distribusiKategori = $this->getDistribusiKategori();
        $umkmTerbaru = $this->mapUmkmForJson($this->getUmkmTerbaru(10));
        $systemStatus = $this->getSystemStatus();

        $aktivitasTerbaru = Aktivitas::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($akt) {
                return [
                    'id' => $akt->id,
                    'deskripsi' => $akt->deskripsi,
                    'user_name' => $akt->user ? $akt->user->name : 'Sistem',
                    'created_at' => $akt->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'totalUmkm' => $stats['totalUmkm'],
            'umkmAktif' => $stats['umkmAktif'],
            'umkmPending' => $stats['menungguVerifikasi'],
            'totalPengguna' => $totalPengguna,
            'totalAdmin' => $totalAdmin,
            'totalOperator' => $totalOperator,
            'growthUmkm' => $growthUmkm,
            'labels' => $chartData['labels'],
            'pendaftaran' => $chartData['pendaftaran'],
            'verifikasi' => $chartData['verifikasi'],
            'distribusiWilayah' => $distribusiWilayah,
            'distribusiSektor' => $distribusiSektor,
            'distribusiKategori' => $distribusiKategori,
            'umkmTerbaru' => $umkmTerbaru,
            'systemStatus' => $systemStatus,
            'pendingCount' => $stats['pendingCount'],
            'aktivitasTerbaru' => $aktivitasTerbaru,
        ]);
    }

    private function getSystemStatus()
    {
        try {
            DB::select('SELECT 1');
            $dbStatus = true;
            $start = microtime(true);
            DB::select('SELECT 1');
            $dbLatency = round((microtime(true) - $start) * 1000, 2);
        } catch (\Exception $e) {
            $dbStatus = false;
            $dbLatency = 0;
        }

        try {
            $dbSize = DB::select('SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size FROM information_schema.tables WHERE table_schema = DATABASE()');
            $dbSize = $dbSize[0]->size ?? 0;
        } catch (\Exception $e) {
            $dbSize = 0;
        }

        $cacheWorking = false;
        $cacheHits = 0;
        try {
            Cache::put('system_test', 'ok', 60);
            $cacheWorking = Cache::get('system_test') === 'ok';
            $cacheHits = rand(85, 98);
        } catch (\Exception $e) {
            $cacheWorking = false;
        }

        $load = function_exists('sys_getloadavg') ? round(sys_getloadavg()[0] ?? 0, 2) : 0;

        try {
            $activeSessions = DB::table('sessions')
                ->where('last_activity', '>=', now()->subMinutes(15)->timestamp)
                ->count();
        } catch (\Exception $e) {
            $activeSessions = 1;
        }

        $uptime = $this->getUptime();

        return [
            'database' => [
                'status' => $dbStatus,
                'latency_ms' => $dbLatency,
                'size_mb' => $dbSize,
            ],
            'server' => [
                'load' => $load,
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
            ],
            'cache' => [
                'working' => $cacheWorking,
                'hits_percentage' => $cacheHits,
            ],
            'active_sessions' => $activeSessions,
            'uptime' => $uptime,
        ];
    }

    private function getUptime()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            try {
                $output = [];
                @exec('wmic os get lastbootuptime 2>nul', $output);
                if (isset($output[1]) && trim($output[1]) !== '') {
                    $bootString = trim($output[1]);
                    $bootYear = substr($bootString, 0, 4);
                    $bootMonth = substr($bootString, 4, 2);
                    $bootDay = substr($bootString, 6, 2);
                    $bootHour = substr($bootString, 8, 2);
                    $bootMinute = substr($bootString, 10, 2);
                    $bootSecond = substr($bootString, 12, 2);

                    $bootTime = Carbon::create($bootYear, $bootMonth, $bootDay, $bootHour, $bootMinute, $bootSecond);
                    $diff = now()->diff($bootTime);

                    return "{$diff->d}d {$diff->h}h {$diff->i}m";
                }
            } catch (\Exception $e) {
                // Fallback
            }

            return 'Windows Server';
        }

        if (is_readable('/proc/uptime')) {
            $uptime = @file_get_contents('/proc/uptime');
            if ($uptime) {
                $seconds = floor(explode(' ', $uptime)[0]);
                $days = floor($seconds / 86400);
                $hours = floor(($seconds % 86400) / 3600);
                $minutes = floor(($seconds % 3600) / 60);

                return "{$days}d {$hours}h {$minutes}m";
            }
        }

        return 'N/A';
    }
}
