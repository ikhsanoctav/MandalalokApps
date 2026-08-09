<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->get('search');
        $status  = $request->get('status');
        $perPage = (int) $request->get('per_page', 10);

        $query = Berita::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $berita = $query->latest()->paginate($perPage)->withQueryString();

        return view('superadmin.master.berita.index', compact('berita'));
    }

    public function create()
    {
        return view('superadmin.master.berita.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:draft,published',
            'kategori' => 'nullable|string|max:100',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->except('gambar');
        $data['penulis'] = auth()->user()->name ?? 'Super Admin';

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        Berita::create($data);

        return redirect()->route('superadmin.berita.index')->with('success', 'Warta berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $berita = Berita::findOrFail($id);

        return view('superadmin.master.berita.edit', compact('berita'));
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:draft,published',
            'kategori' => 'nullable|string|max:100',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->except('gambar');
        $data['penulis'] = auth()->user()->name ?? 'Super Admin';

        if ($request->hasFile('gambar')) {
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $berita->update($data);

        return redirect()->route('superadmin.berita.index')->with('success', 'Warta berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
            Storage::disk('public')->delete($berita->gambar);
        }
        $berita->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Warta berhasil dihapus.'
            ]);
        }

        return redirect()->route('superadmin.berita.index')->with('success', 'Warta berhasil dihapus.');
    }
}
