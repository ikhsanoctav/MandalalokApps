<?php

namespace App\Http\Controllers\Pelaku;

use App\Http\Controllers\Controller;
use App\Models\Pemilik;
use App\Models\Produk;
use App\Models\UMKM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $user    = Auth::user();
        $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();

        if (!$pemilik) {
            return redirect()->route('pelaku.profil.edit')->with('warning', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $umkms   = UMKM::where('id_pemilik', $pemilik->id_pemilik)->get();
        $umkmIds = $umkms->pluck('id_umkm');

        $search      = $request->get('search');
        $filterUmkm  = $request->get('umkm_id');
        $perPage     = (int) $request->get('per_page', 10);

        $query = Produk::whereIn('id_umkm', $umkmIds);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($filterUmkm) {
            $query->where('id_umkm', $filterUmkm);
        }

        $produks = $query->latest()->paginate($perPage)->withQueryString();

        return view('pelaku.produk.index', compact('produks', 'umkms'));
    }

    public function create()
    {
        $user = Auth::user();
        $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();
        
        if (!$pemilik) {
            return redirect()->route('pelaku.profil.edit')->with('warning', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $umkms = UMKM::where('id_pemilik', $pemilik->id_pemilik)->get();
        
        if ($umkms->isEmpty()) {
            return redirect()->route('pelaku.umkm.create')->with('warning', 'Anda belum mendaftarkan UMKM. Silakan daftar UMKM terlebih dahulu.');
        }

        return view('pelaku.produk.create', compact('umkms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_umkm' => 'required|exists:umkms,id_umkm',
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'nullable|numeric|min:0',
            'foto_produk' => 'nullable|array|max:10',
            'foto_produk.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'foto_produk.max' => 'Maksimal foto produk yang dapat diunggah adalah 10.',
            'foto_produk.*.max' => 'Ukuran setiap foto maksimal 2MB.'
        ]);

        // Cek batasan 10 produk per UMKM
        $produkCount = Produk::where('id_umkm', $request->id_umkm)->count();
        if ($produkCount >= 10) {
            return back()->with('error', 'Maaf, saat ini maksimal 10 produk per UMKM. Silakan hapus produk lama jika ingin menambah baru.')->withInput();
        }

        // Pastikan UMKM milik user yang login
        $user = Auth::user();
        $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();
        $umkm = UMKM::where('id_umkm', $request->id_umkm)->where('id_pemilik', $pemilik->id_pemilik)->firstOrFail();

        $fotos = [];
        if ($request->hasFile('foto_produk')) {
            foreach ($request->file('foto_produk') as $file) {
                $path = $file->store('produk', 'public');
                $fotos[] = $path;
            }
        }

        Produk::create([
            'id_umkm' => $request->id_umkm,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'foto_produk' => empty($fotos) ? null : $fotos,
        ]);

        return redirect()->route('pelaku.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();
        
        $produk = Produk::findOrFail($id);
        
        // Verifikasi kepemilikan
        if ($produk->umkm->id_pemilik !== $pemilik->id_pemilik) {
            abort(403, 'Akses ditolak.');
        }

        $umkms = UMKM::where('id_pemilik', $pemilik->id_pemilik)->get();

        return view('pelaku.produk.edit', compact('produk', 'umkms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_umkm' => 'required|exists:umkms,id_umkm',
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'nullable|numeric|min:0',
            'foto_produk' => 'nullable|array|max:10',
            'foto_produk.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = Auth::user();
        $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();
        
        $produk = Produk::findOrFail($id);
        
        // Verifikasi kepemilikan
        if ($produk->umkm->id_pemilik !== $pemilik->id_pemilik) {
            abort(403, 'Akses ditolak.');
        }

        $umkm = UMKM::where('id_umkm', $request->id_umkm)->where('id_pemilik', $pemilik->id_pemilik)->firstOrFail();

        $fotos = $produk->foto_produk ?? [];
        
        // Hapus foto yang dipilih
        if ($request->has('hapus_foto')) {
            foreach ($request->hapus_foto as $key => $val) {
                if (isset($fotos[$key])) {
                    Storage::disk('public')->delete($fotos[$key]);
                    unset($fotos[$key]);
                }
            }
            $fotos = array_values($fotos); // Reindex array
        }

        if ($request->hasFile('foto_produk')) {
            $currentCount = count($fotos);
            $newCount = count($request->file('foto_produk'));
            if ($currentCount + $newCount > 10) {
                return back()->with('error', 'Maksimal total foto produk adalah 10. Harap hapus beberapa foto lama terlebih dahulu.')->withInput();
            }

            foreach ($request->file('foto_produk') as $file) {
                $path = $file->store('produk', 'public');
                $fotos[] = $path;
            }
        }

        $produk->update([
            'id_umkm' => $request->id_umkm,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'foto_produk' => empty($fotos) ? null : $fotos,
        ]);

        return redirect()->route('pelaku.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();
        
        $produk = Produk::findOrFail($id);
        
        // Verifikasi kepemilikan
        if ($produk->umkm->id_pemilik !== $pemilik->id_pemilik) {
            abort(403, 'Akses ditolak.');
        }

        if ($produk->foto_produk) {
            foreach ($produk->foto_produk as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $produk->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus.'
            ]);
        }

        return redirect()->route('pelaku.produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
