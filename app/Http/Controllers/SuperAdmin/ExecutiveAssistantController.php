<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Kelurahan;
use App\Models\UMKM;
use App\Services\OllamaService;
use Illuminate\Http\Request;

class ExecutiveAssistantController extends Controller
{
    public function index()
    {
        return view('superadmin.assistant');
    }

    public function chat(Request $request, OllamaService $ollama)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $query = $request->message;
        $intent = $ollama->parseExecutiveIntent($query);

        // Fallback response if intent is unknown
        if (!isset($intent['intent']) || $intent['intent'] === 'unknown') {
            return response()->json([
                'reply' => 'Maaf, saya tidak dapat memahami pertanyaan tersebut terkait data UMKM. Coba tanyakan misalnya: "Berapa banyak umkm kuliner di Jatihandap?"'
            ]);
        }

        // Build Database Query
        $umkmQuery = UMKM::query()->where('status_verifikasi', 'terverifikasi');

        $kategoriFound = null;
        $kelurahanFound = null;

        if (!empty($intent['kategori'])) {
            $umkmQuery->whereHas('kategori', function($q) use ($intent) {
                $q->where('nama_kategori', 'LIKE', '%' . $intent['kategori'] . '%');
            });
            $kategoriFound = $intent['kategori'];
        }

        if (!empty($intent['kelurahan'])) {
            $umkmQuery->whereHas('pemilik.kelurahanRel', function($q) use ($intent) {
                $q->where('nama_kelurahan', 'LIKE', '%' . $intent['kelurahan'] . '%');
            });
            $kelurahanFound = $intent['kelurahan'];
        }

        // Process based on intent
        if ($intent['intent'] === 'count') {
            $count = $umkmQuery->count();
            
            $reply = "Berdasarkan data saat ini, terdapat **{$count} UMKM** yang berstatus Terverifikasi";
            if ($kategoriFound) $reply .= " dalam kategori **{$kategoriFound}**";
            if ($kelurahanFound) $reply .= " di wilayah **{$kelurahanFound}**";
            $reply .= ".";
            
            return response()->json(['reply' => $reply]);
        }

        if ($intent['intent'] === 'list') {
            $list = $umkmQuery->take(10)->get(); // limit 10
            
            if ($list->isEmpty()) {
                return response()->json(['reply' => 'Maaf, tidak ada data UMKM yang cocok dengan kriteria tersebut.']);
            }

            $reply = "Berikut adalah daftar UMKM";
            if ($kategoriFound) $reply .= " kategori {$kategoriFound}";
            if ($kelurahanFound) $reply .= " di wilayah {$kelurahanFound}";
            $reply .= " (Menampilkan maksimal 10):\n\n";

            foreach ($list as $i => $u) {
                $no = $i + 1;
                $kat = $u->kategori->nama_kategori ?? '-';
                $reply .= "{$no}. **{$u->nama_usaha}** ({$kat})\n";
            }

            return response()->json(['reply' => $reply]);
        }

        return response()->json([
            'reply' => 'Saya berhasil mengenali konteks, tapi sistem belum mendukung aksi tersebut saat ini.'
        ]);
    }
}
