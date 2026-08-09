<?php

namespace App\Http\Controllers\Pelaku;

use App\Http\Controllers\Controller;
use App\Models\KategoriUMKM;
use App\Models\Pemilik;
use App\Models\SektorUmkm;
use App\Models\UMKM;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UmkmController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        if (! $user->nik) {
            return redirect()->route('pelaku.dashboard')->with('error', 'NIK tidak ditemukan pada profil Anda.');
        }

        // Use nik_hash from the user record (already computed)
        $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();

        if (! $pemilik) {
            return redirect()->route('pelaku.dashboard')->with('error', 'Profil Pemilik belum lengkap. Harap isi profil terlebih dahulu.');
        }

        $kategori = KategoriUMKM::all();
        $sektor = SektorUmkm::all();

        return view('pelaku.umkm.create', compact('pemilik', 'kategori', 'sektor'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (! $user->nik) {
            return redirect()->route('pelaku.dashboard')->with('error', 'NIK tidak ditemukan pada profil Anda.');
        }

        // Use nik_hash from the user record (already computed)
        $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();

        if (! $pemilik) {
            return redirect()->route('pelaku.dashboard')->with('error', 'Profil Pemilik belum lengkap. Harap isi profil terlebih dahulu.');
        }

        $request->validate([
            'nama_usaha' => 'required|string|max:150',
            'id_sektor' => 'required|exists:sektor_umkms,id',
            'bentuk_jualan' => 'required|string|max:100',
            'perkiraan_omset' => 'nullable|numeric',
            'alamat_usaha' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'tahun_berdiri' => 'nullable|numeric|digits:4',
            'no_izin_usaha' => 'nullable|string|max:100',
            'npwp_usaha' => 'nullable|string|max:50',
            'telp_usaha' => 'nullable|string|max:20',
            'email_usaha' => 'nullable|email|max:255',
            'jumlah_tenaga_kerja' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'foto_utama' => 'required|image|mimes:jpeg,jpg,png,webp|max:25600',
            'dokumen_nib' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:25600',
            'dokumen_lainnya' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:25600',
        ]);

        $umkm = new UMKM();
        $umkm->id_pemilik = $pemilik->id_pemilik;
        $umkm->nama_usaha = strip_tags($request->nama_usaha);
        $umkm->id_sektor = $request->id_sektor;
        $umkm->bentuk_jualan = strip_tags($request->bentuk_jualan);
        $umkm->alamat_usaha = strip_tags($request->alamat_usaha);
        $umkm->tahun_berdiri = $request->tahun_berdiri;
        $umkm->no_izin_usaha = strip_tags($request->no_izin_usaha);
        $umkm->npwp_usaha = strip_tags($request->npwp_usaha);
        $umkm->telp_usaha = strip_tags($request->telp_usaha);
        $umkm->email_usaha = strip_tags($request->email_usaha);
        $umkm->jumlah_tenaga_kerja = $request->jumlah_tenaga_kerja ?? 0;
        $umkm->deskripsi = strip_tags($request->deskripsi);
        $umkm->perkiraan_omset = $request->perkiraan_omset;
        
        // Auto-assign category based on settings
        $omset = $request->perkiraan_omset ? (float) $request->perkiraan_omset : 0;
        $mikroMax = \App\Models\Setting::get('kategori_mikro_max_omset', 10000000);
        $kecilMax = \App\Models\Setting::get('kategori_kecil_max_omset', 50000000);

        $mikroKategori = \App\Models\KategoriUMKM::where('nama_kategori', 'like', '%mikro%')->first();
        $kecilKategori = \App\Models\KategoriUMKM::where('nama_kategori', 'like', '%kecil%')->first();
        $menengahKategori = \App\Models\KategoriUMKM::where('nama_kategori', 'like', '%menengah%')->first();

        if ($omset <= $mikroMax) {
            $umkm->id_kategori = $mikroKategori?->id ?? 1;
        } elseif ($omset <= $kecilMax) {
            $umkm->id_kategori = $kecilKategori?->id ?? 2;
        } else {
            $umkm->id_kategori = $menengahKategori?->id ?? 3;
        }
        
        $umkm->status_verifikasi = 'terkirim';
        $umkm->id_petugas = null; // Pendaftaran mandiri
        $umkm->tanggal_pendataan = now();

        if ($request->hasFile('foto_utama')) {
            $file = $request->file('foto_utama');
            $filename = time().'_pelaku_'.Auth::id().'.'.$file->extension();
            $umkm->foto_utama = $file->storeAs('umkm/foto_utama', $filename, 'public');
        }

        if ($request->hasFile('dokumen_nib')) {
            $file = $request->file('dokumen_nib');
            $filename = time().'_nib_'.Auth::id().'.'.$file->extension();
            $umkm->dokumen_nib = $file->storeAs('umkm/dokumen', $filename, 'public');
        }

        if ($request->hasFile('dokumen_lainnya')) {
            $file = $request->file('dokumen_lainnya');
            $filename = time().'_lainnya_'.Auth::id().'.'.$file->extension();
            $umkm->dokumen_lainnya = $file->storeAs('umkm/dokumen', $filename, 'public');
        }

        $umkm->save();

        // Simpan riwayat bantuan jika ada
        if ($request->has('bantuans')) {
            foreach ($request->bantuans as $bantuan) {
                if (!empty($bantuan['nama_bantuan'])) {
                    \App\Models\UmkmBantuan::create([
                        'umkm_id' => $umkm->id_umkm,
                        'nama_bantuan' => strip_tags($bantuan['nama_bantuan']),
                        'sumber_bantuan' => isset($bantuan['sumber_bantuan']) ? strip_tags($bantuan['sumber_bantuan']) : null,
                        'tahun_bantuan' => $bantuan['tahun_bantuan'] ?? null,
                        'keterangan' => isset($bantuan['keterangan']) ? strip_tags($bantuan['keterangan']) : null,
                    ]);
                }
            }
        }

        // Send notification to Admin Kecamatan
        $admins = \App\Models\User::role('admin_kecamatan')->get();
        foreach ($admins as $admin) {
            $admin->notify(new SystemNotification(
                '📥 Pengajuan UMKM Baru',
                'Pelaku UMKM "'.$pemilik->nama_lengkap.'" mendaftarkan usaha baru: "'.$umkm->nama_usaha.'" yang memerlukan verifikasi.',
                'info',
                route('admin.verifikasi.show', $umkm->id_umkm)
            ));
        }

        $msg = 'Pendaftaran UMKM berhasil disimpan dan sedang menunggu verifikasi dari Admin Kecamatan.';

        return redirect()->route('pelaku.dashboard')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => $msg,
        ]);
    }


    public function edit($id)
    {
        $user = Auth::user();
        if (! $user->nik) {
            return redirect()->route('pelaku.dashboard')->with('error', 'NIK tidak ditemukan pada profil Anda.');
        }

        $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();

        if (! $pemilik) {
            return redirect()->route('pelaku.dashboard')->with('error', 'Profil Pemilik belum lengkap.');
        }

        $umkm = UMKM::where('id_umkm', $id)->where('id_pemilik', $pemilik->id_pemilik)->firstOrFail();



        $kategori = KategoriUMKM::all();
        $sektor = SektorUmkm::all();

        return view('pelaku.umkm.edit', compact('umkm', 'kategori', 'sektor'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (! $user->nik) {
            return redirect()->route('pelaku.dashboard')->with('error', 'NIK tidak ditemukan pada profil Anda.');
        }

        $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();

        $umkm = UMKM::where('id_umkm', $id)->where('id_pemilik', $pemilik->id_pemilik)->firstOrFail();



        $request->validate([
            'nama_usaha' => 'required|string|max:150',
            'id_sektor' => 'required|exists:sektor_umkms,id',
            'bentuk_jualan' => 'required|string|max:100',
            'perkiraan_omset' => 'nullable|numeric',
            'alamat_usaha' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'tahun_berdiri' => 'nullable|numeric|digits:4',
            'no_izin_usaha' => 'nullable|string|max:100',
            'npwp_usaha' => 'nullable|string|max:50',
            'telp_usaha' => 'nullable|string|max:20',
            'email_usaha' => 'nullable|email|max:255',
            'jumlah_tenaga_kerja' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'foto_utama' => (!$umkm->foto_utama) ? 'required|image|mimes:jpeg,jpg,png,webp|max:25600' : 'nullable|image|mimes:jpeg,jpg,png,webp|max:25600',
            'foto_gallery.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'dokumen_nib' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'dokumen_lainnya' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $dataUpdate = [
            'nama_usaha' => strip_tags($request->nama_usaha),
            'id_sektor' => $request->id_sektor,
            'bentuk_jualan' => strip_tags($request->bentuk_jualan),
            'alamat_usaha' => strip_tags($request->alamat_usaha),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'tahun_berdiri' => $request->tahun_berdiri,
            'no_izin_usaha' => strip_tags($request->no_izin_usaha),
            'npwp_usaha' => strip_tags($request->npwp_usaha),
            'telp_usaha' => strip_tags($request->telp_usaha),
            'email_usaha' => strip_tags($request->email_usaha),
            'jumlah_tenaga_kerja' => $request->jumlah_tenaga_kerja ?? 0,
            'deskripsi' => strip_tags($request->deskripsi),
            'perkiraan_omset' => $request->perkiraan_omset,
        ];

        // Auto-assign category based on settings
        $omset = $request->perkiraan_omset ? (float) $request->perkiraan_omset : 0;
        $mikroMax = \App\Models\Setting::get('kategori_mikro_max_omset', 10000000);
        $kecilMax = \App\Models\Setting::get('kategori_kecil_max_omset', 50000000);

        $mikroKategori = \App\Models\KategoriUMKM::where('nama_kategori', 'like', '%mikro%')->first();
        $kecilKategori = \App\Models\KategoriUMKM::where('nama_kategori', 'like', '%kecil%')->first();
        $menengahKategori = \App\Models\KategoriUMKM::where('nama_kategori', 'like', '%menengah%')->first();

        if ($omset <= $mikroMax) {
            $dataUpdate['id_kategori'] = $mikroKategori?->id ?? 1;
        } elseif ($omset <= $kecilMax) {
            $dataUpdate['id_kategori'] = $kecilKategori?->id ?? 2;
        } else {
            $dataUpdate['id_kategori'] = $menengahKategori?->id ?? 3;
        }

        // Foto Utama
        if ($request->hasFile('foto_utama')) {
            if ($umkm->foto_utama) {
                Storage::disk('public')->delete($umkm->foto_utama);
            }
            $file = $request->file('foto_utama');
            $filename = time().'_pelaku_'.Auth::id().'.'.$file->extension();
            $dataUpdate['foto_utama'] = $file->storeAs('umkm/foto_utama', $filename, 'public');
        }

        // Foto Gallery
        if ($request->hasFile('foto_gallery')) {
            $existingGalleries = $umkm->foto_gallery ?: [];
            $galleryPaths = $existingGalleries ?: [];

            foreach ($request->file('foto_gallery') as $i => $file) {
                $filename = time().'_gallery_'.$i.'_pelaku_'.Auth::id().'.'.$file->extension();
                $galleryPaths[] = $file->storeAs('umkm/gallery', $filename, 'public');
            }
            $dataUpdate['foto_gallery'] = $galleryPaths;
        }

        // Dokumen NIB
        if ($request->hasFile('dokumen_nib')) {
            if ($umkm->dokumen_nib) {
                Storage::disk('public')->delete($umkm->dokumen_nib);
            }
            $file = $request->file('dokumen_nib');
            $filename = time().'_nib_'.Auth::id().'.'.$file->extension();
            $dataUpdate['dokumen_nib'] = $file->storeAs('umkm/dokumen', $filename, 'public');
        }

        // Dokumen Lainnya
        if ($request->hasFile('dokumen_lainnya')) {
            if ($umkm->dokumen_lainnya) {
                Storage::disk('public')->delete($umkm->dokumen_lainnya);
            }
            $file = $request->file('dokumen_lainnya');
            $filename = time().'_lainnya_'.Auth::id().'.'.$file->extension();
            $dataUpdate['dokumen_lainnya'] = $file->storeAs('umkm/dokumen', $filename, 'public');
        }


        $dataUpdate['status_verifikasi'] = 'terkirim';

        $umkm->update($dataUpdate);

        // Send notification to Admin Kecamatan
        $admins = \App\Models\User::role('admin_kecamatan')->get();
        foreach ($admins as $admin) {
            $admin->notify(new SystemNotification(
                '🔄 Pembaruan Data UMKM',
                'Pelaku UMKM "'.$pemilik->nama_lengkap.'" memperbarui data usaha: "'.$umkm->nama_usaha.'". Silakan tinjau kembali untuk verifikasi.',
                'info',
                route('admin.verifikasi.show', $umkm->id_umkm)
            ));
        }

        $msg = 'Data UMKM berhasil diperbarui dan sedang menunggu verifikasi ulang dari Admin Kecamatan.';

        return redirect()->route('pelaku.dashboard')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => $msg,
        ]);
    }

    public function printQr($id)
    {
        $user = Auth::user();
        if (! $user->nik) {
            return redirect()->route('pelaku.dashboard')->with('error', 'NIK tidak ditemukan pada profil Anda.');
        }

        $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();
        if (! $pemilik) {
            return redirect()->route('pelaku.dashboard')->with('error', 'Profil Pemilik belum lengkap.');
        }

        $umkm = UMKM::with('pemilik')
            ->where('id_umkm', $id)
            ->where('id_pemilik', $pemilik->id_pemilik)
            ->firstOrFail();

        return view('umkm.print-qr', compact('umkm'));
    }

    public function printDokumen($id)
    {
        $user = Auth::user();
        if (! $user->nik) {
            return redirect()->route('pelaku.dashboard')->with('error', 'NIK tidak ditemukan pada profil Anda.');
        }

        $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();
        if (! $pemilik) {
            return redirect()->route('pelaku.dashboard')->with('error', 'Profil Pemilik belum lengkap.');
        }

        $umkm = UMKM::with(['pemilik', 'kategori', 'sektor', 'petugas'])
            ->where('id_umkm', $id)
            ->where('id_pemilik', $pemilik->id_pemilik)
            ->firstOrFail();

        return view('umkm.print-dokumen', compact('umkm'));
    }
}
