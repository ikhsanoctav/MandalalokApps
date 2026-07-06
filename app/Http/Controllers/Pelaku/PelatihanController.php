<?php

namespace App\Http\Controllers\Pelaku;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use App\Models\PesertaPelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PelatihanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $pelatihans = Pelatihan::where('status', 'published')
            ->orderBy('tanggal_mulai', 'asc')
            ->get();
            
        $registeredIds = PesertaPelatihan::where('user_id', $user->id)
            ->pluck('pelatihan_id')
            ->toArray();

        return view('pelaku.pelatihan.index', compact('pelatihans', 'registeredIds'));
    }

    public function show($id)
    {
        $user = auth()->user();
        $pelatihan = Pelatihan::findOrFail($id);
        
        $peserta = PesertaPelatihan::where('pelatihan_id', $id)
            ->where('user_id', $user->id)
            ->first();

        return view('pelaku.pelatihan.show', compact('pelatihan', 'peserta'));
    }

    public function daftar(Request $request, $id)
    {
        $user = auth()->user();
        $pelatihan = Pelatihan::findOrFail($id);

        if ($pelatihan->status !== 'published') {
            return back()->with('error', 'Pelatihan ini tidak tersedia untuk pendaftaran.');
        }

        if (PesertaPelatihan::where('pelatihan_id', $id)->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Anda sudah terdaftar di pelatihan ini.');
        }

        if ($pelatihan->peserta()->count() >= $pelatihan->kuota) {
            return back()->with('error', 'Mohon maaf, kuota pelatihan ini sudah penuh.');
        }

        // Cek syarat dokumen
        $dokumenPaths = [];
        if (!empty($pelatihan->syarat_dokumen) && is_array($pelatihan->syarat_dokumen)) {
            $request->validate([
                'dokumen_syarat' => 'required|array',
                'dokumen_syarat.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ], [
                'dokumen_syarat.required' => 'Pelatihan ini mewajibkan Anda untuk mengunggah dokumen persyaratan.',
                'dokumen_syarat.*.required' => 'Semua dokumen persyaratan wajib diunggah.',
                'dokumen_syarat.*.mimes' => 'Dokumen harus berupa PDF atau gambar (JPG/PNG).',
                'dokumen_syarat.*.max' => 'Ukuran maksimal tiap dokumen adalah 5MB.',
            ]);

            if ($request->hasFile('dokumen_syarat')) {
                foreach ($request->file('dokumen_syarat') as $index => $file) {
                    $syaratName = $pelatihan->syarat_dokumen[$index] ?? 'dokumen_'.$index;
                    $filename = time() . '_' . Str::slug($user->name) . '_' . Str::slug($syaratName) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('dokumen_pelatihan', $filename, 'public');
                    $dokumenPaths[$syaratName] = 'storage/' . $path;
                }
            }
        }

        $kode_tiket = 'TRN-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));

        PesertaPelatihan::create([
            'pelatihan_id' => $id,
            'user_id' => $user->id,
            'dokumen_syarat' => !empty($dokumenPaths) ? $dokumenPaths : null,
            'kode_tiket' => $kode_tiket,
            'status_kehadiran' => 'terdaftar',
        ]);

        return redirect()->route('pelaku.pelatihan.show', $id)->with('success', 'Berhasil mendaftar pelatihan! Ini adalah tiket Anda.');
    }
}
