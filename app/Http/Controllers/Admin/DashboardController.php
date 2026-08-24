<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\DashboardChartTrait;
use App\Models\Aktivitas;
use App\Models\UMKM;
use Illuminate\Http\Request;

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

        $totalUmkm = $stats['totalUmkm'];
        $menungguVerifikasi = $stats['menungguVerifikasi'];
        $umkmAktif = $stats['umkmAktif'];
        $pendingCount = $stats['pendingCount'];
        
        $pendingAkunCount = \App\Models\Pemilik::where('status_verifikasi_ktp', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalUmkm', 'menungguVerifikasi', 'umkmAktif',
            'growthUmkm', 'chartLabels', 'chartPendaftaran', 'chartVerifikasi',
            'distribusiWilayah', 'distribusiSektor', 'distribusiKategori', 'umkmTerbaru', 'aktivitasTerbaru', 'pendingCount', 'pendingAkunCount',
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
        $chartData = $this->getChartDataByFilter($filter);
        $distribusiWilayah = $this->getDistribusiWilayah(request('filter_sektor'));
        $distribusiSektor = $this->getDistribusiSektor(request('filter_kelurahan'));
        $distribusiKategori = $this->getDistribusiKategori();
        $umkmTerbaru = $this->mapUmkmForJson($this->getUmkmTerbaru(10));
        $sektors = \App\Models\SektorUmkm::all();
        $kelurahans = \App\Models\Kelurahan::all();

        return response()->json([
            'success' => true,
            'totalUmkm' => $stats['totalUmkm'],
            'umkmAktif' => $stats['umkmAktif'],
            'umkmPending' => $stats['menungguVerifikasi'],
            'growthUmkm' => $growthUmkm,
            'labels' => $chartData['labels'],
            'pendaftaran' => $chartData['pendaftaran'],
            'verifikasi' => $chartData['verifikasi'],
            'distribusiWilayah' => $distribusiWilayah,
            'distribusiSektor' => $distribusiSektor,
            'distribusiKategori' => $distribusiKategori,
            'umkmTerbaru' => $umkmTerbaru,
            'pendingCount' => $stats['pendingCount'],
        ]);
    }
}
