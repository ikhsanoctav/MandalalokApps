<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemilik;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;

class VerifikasiAkunController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $query = Pemilik::where('status_verifikasi_ktp', 'pending');

        if ($search) {
            $nikHash = hash('sha256', $search);
            $query->where(function ($q) use ($search, $nikHash) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik_hash', $nikHash);
            });
        }

        $pemiliks = $query->orderBy('created_at', 'desc')->paginate(10);
        $pendingCount = Pemilik::where('status_verifikasi_ktp', 'pending')->count();

        if ($request->ajax() || $request->has('ajax')) {
            $html = view('admin.verifikasi_akun.table-data', compact('pemiliks', 'pendingCount'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'pendingCount' => $pendingCount
            ]);
        }

        return view('admin.verifikasi_akun.index', compact('pemiliks', 'pendingCount'));
    }

    public function show($id)
    {
        $pemilik = Pemilik::findOrFail($id);
        
        // Find the user associated with this pemilik using hash comparison
        $user = User::where('nik_hash', $pemilik->nik_hash)->first();

        return view('admin.verifikasi_akun.show', compact('pemilik', 'user'));
    }

    public function verify($id)
    {
        try {
            $pemilik = Pemilik::findOrFail($id);
            $routePrefix = auth()->user()->hasRole('super_admin') ? 'superadmin' : 'admin';

            if ($pemilik->status_verifikasi_ktp === 'terverifikasi') {
                return redirect()->route("{$routePrefix}.verifikasi_akun.index")->with('toast', [
                    'type' => 'warning',
                    'title' => 'Perhatian!',
                    'message' => 'Akun sudah diverifikasi sebelumnya.',
                ]);
            }

            $pemilik->update([
                'status_verifikasi_ktp' => 'terverifikasi',
                'catatan' => null
            ]);

            // Notify user
            $user = User::where('nik_hash', $pemilik->nik_hash)->first();
            if ($user) {
                $user->notify(new SystemNotification(
                    '✅ Akun Anda Diverifikasi!',
                    'Profil dan identitas Anda telah diverifikasi. Anda sekarang dapat mendaftarkan usaha UMKM Anda.',
                    'success',
                    route('pelaku.dashboard')
                ));
            }

            return redirect()->route("{$routePrefix}.verifikasi_akun.index")->with('toast', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Akun '.$pemilik->nama_lengkap.' berhasil diverifikasi.',
            ]);

        } catch (\Exception $e) {
            $routePrefix = auth()->user()->hasRole('super_admin') ? 'superadmin' : 'admin';
            return redirect()->route("{$routePrefix}.verifikasi_akun.index")->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ]);
        }
    }

    public function reject(Request $request, $id)
    {
        $routePrefix = auth()->user()->hasRole('super_admin') ? 'superadmin' : 'admin';

        try {
            $pemilik = Pemilik::findOrFail($id);
            $alasan = $request->input('reason');

            if (!$alasan) {
                return redirect()->back()->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Alasan penolakan harus diisi.',
                ]);
            }

            $pemilik->update([
                'status_verifikasi_ktp' => 'ditolak',
                'catatan' => $alasan
            ]);

            // Notify user
            $user = User::where('nik_hash', $pemilik->nik_hash)->first();
            if ($user) {
                $user->notify(new SystemNotification(
                    '❌ Verifikasi Akun Ditolak',
                    'Profil Anda gagal diverifikasi karena: '.$alasan.'. Harap perbaiki data profil Anda.',
                    'danger',
                    route('pelaku.profil.edit')
                ));
            }

            return redirect()->route("{$routePrefix}.verifikasi_akun.index")->with('toast', [
                'type' => 'warning',
                'title' => 'Berhasil!',
                'message' => 'Akun '.$pemilik->nama_lengkap.' ditolak.',
            ]);

        } catch (\Exception $e) {
            return redirect()->route("{$routePrefix}.verifikasi_akun.index")->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ]);
        }
    }

    public function showKtp($id)
    {
        $user = auth()->user();
        if (!$user || (!$user->hasRole('admin_kecamatan') && !$user->hasRole('super_admin'))) {
            abort(403, 'Akses ditolak.');
        }

        $pemilik = Pemilik::findOrFail($id);

        if (!$pemilik->foto_ktp) {
            abort(404, 'Foto KTP tidak ditemukan.');
        }

        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($pemilik->foto_ktp)) {
            // Fallback for files placed under storage/app/
            $pathFallback = storage_path('app/' . $pemilik->foto_ktp);
            if (file_exists($pathFallback)) {
                $path = $pathFallback;
            } else {
                abort(404, 'File KTP tidak ditemukan di server.');
            }
        } else {
            $path = \Illuminate\Support\Facades\Storage::disk('local')->path($pemilik->foto_ktp);
        }

        return response()->file($path);
    }
}
