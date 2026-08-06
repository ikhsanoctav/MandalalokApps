<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Pemilik;
use Illuminate\Support\Facades\Auth;

class EnsureProfilCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Cek hanya untuk role pelaku_umkm
        if ($user && $user->hasRole('pelaku_umkm')) {
            $pemilik = null;
            if ($user->nik_hash) {
                $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();
            }
            
            $isComplete = false;
            $isVerified = false;
            if ($pemilik) {
                // Periksa apakah field-field wajib sudah terisi
                $isComplete = !empty($pemilik->nama_lengkap) &&
                              !empty($pemilik->jenis_kelamin) &&
                              !empty($pemilik->alamat) &&
                              !empty($pemilik->kelurahan) &&
                              !empty($pemilik->foto_ktp);
                $isVerified = $pemilik->status_verifikasi_ktp === 'terverifikasi';
            }
            
            // Jika belum ada data pemilik, data belum lengkap, atau belum diverifikasi, arahkan ke halaman profil
            if (!$pemilik || !$isComplete || !$isVerified) {
                // Hindari infinite loop jika sedang di halaman profil
                if (!$request->routeIs('pelaku.profil.*')) {
                    $message = 'Silakan lengkapi data diri (Profil) Anda terlebih dahulu sebelum menggunakan fitur lainnya.';
                    if ($isComplete && !$isVerified) {
                        $message = 'Akun Anda sedang menunggu verifikasi dari Admin Kecamatan. Harap tunggu sebelum menggunakan fitur lainnya.';
                        if ($pemilik && $pemilik->status_verifikasi_ktp === 'ditolak') {
                            $message = 'Verifikasi akun Anda ditolak. Silakan perbaiki data profil Anda terlebih dahulu.';
                        }
                    }
                    return redirect()->route('pelaku.profil.edit')
                        ->with('info', $message);
                }
            }
        }
        
        return $next($request);
    }
}
