<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    /**
     * GET /api/berita
     */
    public function index(Request $request)
    {
        $query = Berita::with('user')->latest();

        if ($request->search) {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        $berita = $query->paginate(10);

        return response()->json([
            'data' => $berita->map(function ($b) {
                return [
                    'id' => $b->id,
                    'judul' => $b->judul,
                    'kategori' => $b->kategori,
                    'gambar' => $b->gambar ? asset('storage/' . $b->gambar) : null,
                    'tanggal' => $b->tanggal,
                    'penulis' => $b->user?->name,
                    'created_at' => $b->created_at,
                ];
            }),
            'pagination' => [
                'current_page' => $berita->currentPage(),
                'last_page' => $berita->lastPage(),
                'total' => $berita->total()
            ]
        ]);
    }

    /**
     * GET /api/berita/{id}
     */
    public function show($id)
    {
        $berita = Berita::with('user')->find($id);

        if (!$berita) {
            return response()->json(['message' => 'Berita tidak ditemukan'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $berita->id,
                'judul' => $berita->judul,
                'kategori' => $berita->kategori,
                'konten' => $berita->konten,
                'gambar' => $berita->gambar ? asset('storage/' . $berita->gambar) : null,
                'tanggal' => $berita->tanggal,
                'penulis' => $berita->user?->name,
                'created_at' => $berita->created_at,
            ]
        ]);
    }

    /**
     * POST /api/berita
     */
    public function store(Request $request)
    {
        if (!$request->user()->hasRole('super_admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'konten' => 'required|string',
            'tanggal' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('berita', 'public');
        }

        $berita = Berita::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'konten' => $request->konten,
            'tanggal' => $request->tanggal,
            'gambar' => $path,
            'id_user' => $request->user()->id
        ]);

        return response()->json([
            'message' => 'Berita berhasil dibuat.',
            'data' => $berita
        ], 201);
    }

    /**
     * PUT /api/berita/{id}
     */
    public function update(Request $request, $id)
    {
        if (!$request->user()->hasRole('super_admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $berita = Berita::find($id);
        if (!$berita) return response()->json(['message' => 'Not found'], 404);

        $request->validate([
            'judul' => 'sometimes|required|string|max:255',
            'kategori' => 'sometimes|required|string',
            'konten' => 'sometimes|required|string',
            'tanggal' => 'sometimes|required|date',
        ]);

        if ($request->hasFile('gambar')) {
            if ($berita->gambar) Storage::disk('public')->delete($berita->gambar);
            $berita->gambar = $request->file('gambar')->store('berita', 'public');
        }

        $berita->update($request->only(['judul', 'kategori', 'konten', 'tanggal']));
        if ($request->hasFile('gambar')) $berita->save();

        return response()->json(['message' => 'Berita diperbarui', 'data' => $berita]);
    }

    /**
     * DELETE /api/berita/{id}
     */
    public function destroy(Request $request, $id)
    {
        if (!$request->user()->hasRole('super_admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $berita = Berita::find($id);
        if (!$berita) return response()->json(['message' => 'Not found'], 404);

        if ($berita->gambar) Storage::disk('public')->delete($berita->gambar);
        $berita->delete();

        return response()->json(['message' => 'Berita dihapus']);
    }
}
