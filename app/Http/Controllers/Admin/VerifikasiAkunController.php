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
        $search  = $request->get('search');
        $status  = $request->get('status', 'pending'); // Default to pending
        $perPage = (int) $request->get('per_page', 10);
        
        $query = Pemilik::query();

        if ($status && $status !== 'semua') {
            $query->where('status_verifikasi_ktp', $status);
        }

        if ($search) {
            $nikHash = hash('sha256', $search);
            $query->where(function ($q) use ($search, $nikHash) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik_hash', $nikHash);
            });
        }

        $pemiliks = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
        
        // Counts for cards
        $countPending = Pemilik::where('status_verifikasi_ktp', 'pending')->count();
        $countTerverifikasi = Pemilik::where('status_verifikasi_ktp', 'terverifikasi')->count();
        $countDitolak = Pemilik::where('status_verifikasi_ktp', 'ditolak')->count();
        $countTotal = Pemilik::count();

        // Keep this for backwards compatibility with the layout sidebar if needed
        $pendingCount = $countPending; 

        if ($request->ajax() || $request->has('ajax')) {
            $html = view('admin.verifikasi_akun.table-data', compact('pemiliks', 'pendingCount'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'pendingCount' => $pendingCount
            ]);
        }

        return view('admin.verifikasi_akun.index', compact('pemiliks', 'pendingCount', 'countPending', 'countTerverifikasi', 'countDitolak', 'countTotal', 'status'));
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

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($pemilik->foto_ktp)) {
            $path = \Illuminate\Support\Facades\Storage::disk('public')->path($pemilik->foto_ktp);
        } elseif (\Illuminate\Support\Facades\Storage::disk('local')->exists($pemilik->foto_ktp)) {
            $path = \Illuminate\Support\Facades\Storage::disk('local')->path($pemilik->foto_ktp);
        } else {
            // Fallback for files placed under storage/app/
            $pathFallback = storage_path('app/' . $pemilik->foto_ktp);
            $pathPublicFallback = storage_path('app/public/' . $pemilik->foto_ktp);
            
            if (file_exists($pathPublicFallback)) {
                $path = $pathPublicFallback;
            } elseif (file_exists($pathFallback)) {
                $path = $pathFallback;
            } else {
                abort(404, 'File KTP tidak ditemukan di server.');
            }
        }

        return response()->file($path);
    }
}
