<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use App\Models\User;
use App\Models\PesertaPelatihan;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->get('search');
        $status  = $request->get('status');
        $perPage = (int) $request->get('per_page', 15);

        $query = Pelatihan::orderBy('tanggal_mulai', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $pelatihans = $query->paginate($perPage)->withQueryString();
        return view('superadmin.pelatihan.index', compact('pelatihans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'kuota' => 'required|integer|min:1',
            'syarat_dokumen' => 'nullable|array',
            'syarat_dokumen.*' => 'string|max:255',
            'status' => 'required|in:draft,published,completed',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (isset($validated['syarat_dokumen']) && is_array($validated['syarat_dokumen'])) {
            $validated['syarat_dokumen'] = array_values(array_filter($validated['syarat_dokumen'], fn($value) => !is_null($value) && $value !== ''));
            if (empty($validated['syarat_dokumen'])) {
                $validated['syarat_dokumen'] = null;
            }
        }

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('pelatihan-banners', 'public');
            $validated['banner'] = 'storage/' . $path;
        }

        Pelatihan::create($validated);

        return redirect()->route('superadmin.pelatihan.index')->with('success', 'Pelatihan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pelatihan = Pelatihan::with(['peserta.user.pemilik.umkm'])->findOrFail($id);
        $users = User::role('pelaku_umkm')->get(); // Untuk dropdown mendaftarkan peserta
        return view('superadmin.pelatihan.show', compact('pelatihan', 'users'));
    }

    public function edit($id)
    {
        $pelatihan = Pelatihan::findOrFail($id);
        return view('superadmin.pelatihan.edit', compact('pelatihan'));
    }

    public function update(Request $request, $id)
    {
        $pelatihan = Pelatihan::findOrFail($id);
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'kuota' => 'required|integer|min:1',
            'syarat_dokumen' => 'nullable|array',
            'syarat_dokumen.*' => 'string|max:255',
            'status' => 'required|in:draft,published,completed',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (isset($validated['syarat_dokumen']) && is_array($validated['syarat_dokumen'])) {
            $validated['syarat_dokumen'] = array_values(array_filter($validated['syarat_dokumen'], fn($value) => !is_null($value) && $value !== ''));
            if (empty($validated['syarat_dokumen'])) {
                $validated['syarat_dokumen'] = null;
            }
        } else {
            $validated['syarat_dokumen'] = null;
        }

        if ($request->hasFile('banner')) {
            // Hapus banner lama jika ada
            if ($pelatihan->banner && file_exists(public_path($pelatihan->banner))) {
                @unlink(public_path($pelatihan->banner));
            }
            $path = $request->file('banner')->store('pelatihan-banners', 'public');
            $validated['banner'] = 'storage/' . $path;
        }

        $pelatihan->update($validated);

        return redirect()->route('superadmin.pelatihan.index')->with('success', 'Pelatihan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $pelatihan = Pelatihan::with('peserta')->findOrFail($id);

            // Clean up banner file
            if ($pelatihan->banner && file_exists(public_path($pelatihan->banner))) {
                @unlink(public_path($pelatihan->banner));
            }

            // Clean up participant document files
            foreach ($pelatihan->peserta as $peserta) {
                if (!empty($peserta->dokumen_syarat) && is_array($peserta->dokumen_syarat)) {
                    foreach ($peserta->dokumen_syarat as $filePath) {
                        if ($filePath && file_exists(public_path($filePath))) {
                            @unlink(public_path($filePath));
                        }
                    }
                }
            }

            $pelatihan->delete();
        });

        return redirect()->route('superadmin.pelatihan.index')->with('success', 'Pelatihan berhasil dihapus.');
    }

    public function registerUser(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $error = DB::transaction(function () use ($request, $id) {
            $pelatihan = Pelatihan::where('id', $id)->lockForUpdate()->firstOrFail();

            // Cek apakah sudah terdaftar
            if (PesertaPelatihan::where('pelatihan_id', $id)->where('user_id', $request->user_id)->exists()) {
                return 'Pelaku UMKM tersebut sudah terdaftar di pelatihan ini.';
            }

            // Cek kuota dengan lock aktif
            if ($pelatihan->peserta()->count() >= $pelatihan->kuota) {
                return 'Kuota pelatihan sudah penuh.';
            }

            $kode_tiket = 'TRN-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));

            PesertaPelatihan::create([
                'pelatihan_id' => $id,
                'user_id' => $request->user_id,
                'kode_tiket' => $kode_tiket,
                'status_kehadiran' => 'terdaftar',
            ]);

            return null;
        });

        if ($error) {
            return back()->with('error', $error);
        }

        return back()->with('success', 'Pelaku UMKM berhasil didaftarkan ke pelatihan.');
    }
}
