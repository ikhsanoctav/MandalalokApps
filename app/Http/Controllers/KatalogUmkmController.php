<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UMKM;
use App\Models\SektorUmkm;
use App\Models\Produk;

class KatalogUmkmController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Produk::query()
            ->with(['umkm.sektor', 'umkm.pemilik.kelurahanRel', 'umkm.ratings'])
            ->whereHas('umkm', function($q) {
                $q->terverifikasi()->aktif();
            });

        // Filter Pencarian Nama Produk atau UMKM
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_produk', 'like', '%' . $request->search . '%')
                  ->orWhereHas('umkm', function($q2) use ($request) {
                      $q2->where('nama_usaha', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filter Sektor UMKM
        if ($request->filled('sektor')) {
            $query->whereHas('umkm', function($q) use ($request) {
                $q->where('id_sektor', $request->sektor);
            });
        }

        $produks = $query->latest()->paginate(20)->withQueryString();
        $sektors = SektorUmkm::all();

        return view('katalog-umkm', compact('produks', 'sektors'));
    }

    public function storeRating(Request $request, $id_umkm)
    {
        $request->validate([
            'nama_reviewer' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string'
        ]);

        $umkm = UMKM::findOrFail($id_umkm);

        \App\Models\UmkmRating::create([
            'umkm_id' => $umkm->id_umkm,
            'nama_reviewer' => $request->nama_reviewer,
            'rating' => $request->rating,
            'ulasan' => $request->ulasan,
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Rating dan ulasan Anda telah disimpan.');
    }

    public function likeRating($id)
    {
        $rating = \App\Models\UmkmRating::findOrFail($id);
        
        // Simple session-based throttling to prevent infinite liking by the same browser session
        $sessionKey = 'liked_rating_' . $id;
        if (session()->has($sessionKey)) {
            return response()->json(['success' => false, 'message' => 'Anda sudah menyukai ulasan ini.'], 403);
        }

        $rating->increment('likes');
        session()->put($sessionKey, true);

        return response()->json([
            'success' => true,
            'likes' => $rating->likes
        ]);
    }

    public function dislikeRating($id)
    {
        $rating = \App\Models\UmkmRating::findOrFail($id);
        
        // Simple session-based throttling
        $sessionKey = 'disliked_rating_' . $id;
        if (session()->has($sessionKey)) {
            return response()->json(['success' => false, 'message' => 'Anda sudah tidak menyukai ulasan ini.'], 403);
        }

        $rating->increment('dislikes');
        session()->put($sessionKey, true);

        return response()->json([
            'success' => true,
            'dislikes' => $rating->dislikes
        ]);
    }
}
