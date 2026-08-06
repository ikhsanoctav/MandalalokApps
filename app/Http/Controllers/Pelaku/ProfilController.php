<?php

namespace App\Http\Controllers\Pelaku;

use App\Http\Controllers\Controller;
use App\Models\Kelurahan;
use App\Models\Pemilik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        $pemilik = null;
        if ($user->nik_hash) {
            $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();
            if ($pemilik) {
                // KTP validation requires admin verification now.
                app(\App\Services\UmkmService::class)->syncPemilikWilayah($pemilik);
            }
        }

        $kelurahan = Kelurahan::with('rws.rts')->get();

        return view('pelaku.profil.edit', compact('user', 'pemilik', 'kelurahan'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $pemilik = null;
        if ($user->nik_hash) {
            $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'tempat_lahir' => 'nullable|string|max:50',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'required|string',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'kelurahan' => 'required|string|max:100',
            'foto_ktp' => ($pemilik && $pemilik->foto_ktp) ? 'nullable|image|max:5120' : 'required|image|max:5120',
        ]);

        $kelurahan = Kelurahan::where('nama_kelurahan', $request->kelurahan)->first();

        // Process foto_ktp
        $fotoKtpPath = $pemilik ? $pemilik->foto_ktp : null;
        $needsVerification = false;

        if ($request->hasFile('foto_ktp')) {
            if ($pemilik && $pemilik->foto_ktp && \Illuminate\Support\Facades\Storage::disk('public')->exists($pemilik->foto_ktp)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($pemilik->foto_ktp);
            }
            $file = $request->file('foto_ktp');
            $filename = time() . '_ktp_' . $user->id . '.' . $file->getClientOriginalExtension();
            $fotoKtpPath = $file->storeAs('pemilik/ktp', $filename, 'public');
            $needsVerification = true;
        }

        if (!$pemilik) {
            $needsVerification = true;
        }

        $statusVerifikasi = $pemilik ? $pemilik->status_verifikasi_ktp : 'pending';
        $catatan = $pemilik ? $pemilik->catatan : null;

        if ($needsVerification) {
            $statusVerifikasi = 'pending';
            $catatan = null;
        }

        if ($pemilik) {
            $pemilik->update([
                'nama_lengkap' => $request->nama_lengkap,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'kelurahan' => $request->kelurahan,
                'kecamatan' => $kelurahan?->kecamatan ?? 'Mandalajati',
                'kota_kab' => $kelurahan?->kota_kab ?? 'Bandung',
                'provinsi' => $kelurahan?->provinsi ?? 'Jawa Barat',
                'foto_ktp' => $fotoKtpPath,
                'status_verifikasi_ktp' => $statusVerifikasi,
                'catatan' => $catatan,
            ]);
        } else {
            // Create new Pemilik
            Pemilik::create([
                'nik' => $user->nik,
                'nik_hash' => $user->nik_hash, // Save hash to speed up lookups
                'nama_lengkap' => $request->nama_lengkap,
                'tempat_lahir' => $request->tempat_lahir ?? '-',
                'tanggal_lahir' => $request->tanggal_lahir ?? '2000-01-01',
                'no_hp' => $request->no_hp ?? '-',
                'email' => $user->email,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'rt' => $request->rt ?? '000',
                'rw' => $request->rw ?? '000',
                'kelurahan' => $request->kelurahan,
                'kecamatan' => $kelurahan?->kecamatan ?? 'Mandalajati',
                'kota_kab' => $kelurahan?->kota_kab ?? 'Bandung',
                'provinsi' => $kelurahan?->provinsi ?? 'Jawa Barat',
                'kode_pos' => '40285',
                'foto_ktp' => $fotoKtpPath,
                'status_verifikasi_ktp' => $statusVerifikasi,
                'catatan' => $catatan,
            ]);
        }

        // Update user details to keep synced
        $userUpdateData = [
            'name' => $request->nama_lengkap,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'kelurahan' => $request->kelurahan,
            'rw' => $request->rw,
            'rt' => $request->rt,
        ];

        $matchedKel = Kelurahan::where('nama_kelurahan', $request->kelurahan)->first();
        if ($matchedKel) {
            $userUpdateData['id_kelurahan'] = $matchedKel->id;
        }

        $user->update($userUpdateData);

        return redirect()->route('pelaku.dashboard')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Profil Anda berhasil diperbarui.',
        ]);
    }
}
