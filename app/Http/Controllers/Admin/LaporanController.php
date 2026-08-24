<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriUMKM;
use App\Models\UMKM;
use App\Services\ReportGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $kategoriList = KategoriUMKM::all();

        $query = UMKM::query();

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        if ($request->filled('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        if ($request->filled('periode_awal') && $request->filled('periode_akhir')) {
            $query->whereBetween('tanggal_pendataan', [$request->periode_awal, $request->periode_akhir]);
        } elseif ($request->filled('periode_awal')) {
            $query->where('tanggal_pendataan', '>=', $request->periode_awal);
        } elseif ($request->filled('periode_akhir')) {
            $query->where('tanggal_pendataan', '<=', $request->periode_akhir);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_usaha', 'like', "%{$search}%")
                  ->orWhere('no_pendaftaran', 'like', "%{$search}%")
                  ->orWhereHas('pemilik', fn($pq) => $pq->where('nama_lengkap', 'like', "%{$search}%"));
            });
        }

        $totalUmkm = $query->count();
        $totalTenagaKerja = (clone $query)->sum('jumlah_tenaga_kerja');

        $chartData = (clone $query)
            ->select(DB::raw('DATE(tanggal_pendataan) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartLabels = $chartData->pluck('date')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('d M');
        });
        $chartValues = $chartData->pluck('count');

        $perPage = (int) $request->get('per_page', 10);
        $umkms = (clone $query)->with(['pemilik', 'kategori', 'sektor'])->latest()->paginate($perPage)->withQueryString();

        if ($request->ajax() || $request->has('ajax')) {
            $html = view('admin.laporan.table-data', compact('umkms'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'totalUmkm' => number_format($totalUmkm),
                'totalTenagaKerja' => number_format($totalTenagaKerja),
                'chartLabels' => $chartLabels,
                'chartValues' => $chartValues
            ]);
        }

        return view('admin.laporan.index', compact('totalUmkm', 'totalTenagaKerja', 'kategoriList', 'chartLabels', 'chartValues', 'umkms'));
    }

    public function export(Request $request, ReportGenerator $generator)
    {
        $request->validate([
            'format' => 'required|in:pdf,excel,csv',
        ]);

        return $generator->generate('umkm', $request->format, $request->all());
    }
}
