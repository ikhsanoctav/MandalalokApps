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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_usaha', 'like', "%{$search}%")
                  ->orWhere('nomor_pendaftaran', 'like', "%{$search}%")
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
        $settings = Setting::all()->filter(fn($s) => $s->group !== 'kop_surat');

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
            'type'    => 'success',
            'title'   => 'Pengaturan Disimpan!',
            'message' => 'Konfigurasi sistem berhasil diperbarui.',
        ]);

        return redirect()->route('superadmin.settings');
    }

    /**
     * Simpan pengaturan kop surat (termasuk upload logo).
     */
    public function settingsKopSurat(Request $request)
    {
        $request->validate([
            'kop_nama_instansi' => 'nullable|string|max:255',
            'kop_nama_unit'     => 'nullable|string|max:255',
            'kop_alamat'        => 'nullable|string|max:500',
            'kop_telepon'       => 'nullable|string|max:50',
            'kop_email'         => 'nullable|email|max:100',
            'kop_website'       => 'nullable|string|max:255',
            'kop_logo'          => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $kopKeys = ['kop_nama_instansi', 'kop_nama_unit', 'kop_alamat', 'kop_telepon', 'kop_email', 'kop_website'];

        foreach ($kopKeys as $key) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                $setting->value = $request->input($key, '');
                $setting->save();
            }
        }

        // Handle logo upload
        if ($request->hasFile('kop_logo')) {
            $file     = $request->file('kop_logo');
            $filename = 'kop_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $path     = $file->storeAs('kop_logo', $filename, 'public');

            $logoSetting = Setting::where('key', 'kop_logo_path')->first();
            if ($logoSetting) {
                // Hapus logo lama jika ada
                if (! empty($logoSetting->value) && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoSetting->value)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($logoSetting->value);
                }
                $logoSetting->value = $path;
                $logoSetting->save();
            }
        }

        AktivitasLogger::log(
            'Memperbarui kop surat laporan & dokumen cetak.',
            'settings',
            null,
            $request
        );

        session()->flash('toast', [
            'type'    => 'success',
            'title'   => 'Kop Surat Disimpan!',
            'message' => 'Template kop surat berhasil diperbarui.',
        ]);

        return redirect()->route('superadmin.settings')->with('active_tab', 'kop_surat');
    }
}
