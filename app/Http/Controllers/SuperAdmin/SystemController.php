<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Aktivitas;
use App\Models\Setting;
use App\Services\AktivitasLogger;
use Illuminate\Http\Request;

use App\Models\KategoriUMKM;
use App\Models\UMKM;
use App\Services\ReportGenerator;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    public function laporan(Request $request)
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

        $umkms = (clone $query)->with(['pemilik', 'kategori', 'sektor'])->latest()->paginate(10)->withQueryString();

        return view('superadmin.laporan.index', compact('totalUmkm', 'totalTenagaKerja', 'kategoriList', 'chartLabels', 'chartValues', 'umkms'));
    }

    public function laporanExport(Request $request, ReportGenerator $generator)
    {
        $request->validate([
            'format' => 'required|in:pdf,excel,csv',
        ]);

        return $generator->generate('umkm', $request->format, $request->all());
    }

    public function logs(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = Aktivitas::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $logs = $query->latest()->paginate($perPage)->withQueryString();

        if ($request->ajax()) {
            $html = view('superadmin.master.logs.table-data', compact('logs'))->render();

            return response()->json(['success' => true, 'html' => $html]);
        }

        return view('superadmin.master.logs', compact('logs'));
    }

    public function settings()
    {
        $settings = Setting::all()->groupBy('group');

        return view('superadmin.settings', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        $settings = Setting::all();

        foreach ($settings as $setting) {
            $key = $setting->key;
            if ($request->has($key)) {
                $value = $request->input($key);

                if ($setting->type === 'boolean') {
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
                }

                $setting->value = $value;
                $setting->save();
            } else {
                if ($setting->type === 'boolean') {
                    $setting->value = 'false';
                    $setting->save();
                }
            }
        }

        AktivitasLogger::log(
            'Memperbarui konfigurasi & pengaturan sistem global Mandalaloka.',
            'settings',
            null,
            $request
        );

        session()->flash('toast', [
            'type' => 'success',
            'title' => 'Pengaturan Disimpan!',
            'message' => 'Konfigurasi sistem berhasil diperbarui.',
        ]);

        return redirect()->route('superadmin.settings');
    }
}
