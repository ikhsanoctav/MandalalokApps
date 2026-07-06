<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use App\Models\PesertaPelatihan;
use Illuminate\Http\Request;

class PelatihanScannerController extends Controller
{
    public function index()
    {
        $pelatihans = Pelatihan::where('status', 'published')
            ->orderBy('tanggal_mulai', 'asc')
            ->get();
        return view('petugas.pelatihan.scanner', compact('pelatihans'));
    }

    public function scan(Request $request)
    {
        $request->validate([
            'kode_tiket' => 'required|string',
            'pelatihan_id' => 'required|exists:pelatihans,id',
        ]);

        $tiket = PesertaPelatihan::with('user.pemilik')
            ->where('kode_tiket', $request->kode_tiket)
            ->where('pelatihan_id', $request->pelatihan_id)
            ->first();

        if (!$tiket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak valid atau tidak terdaftar pada pelatihan ini.'
            ]);
        }

        if ($tiket->status_kehadiran === 'hadir') {
            return response()->json([
                'success' => false,
                'message' => 'Peserta sudah melakukan scan kehadiran sebelumnya pada ' . $tiket->waktu_hadir->format('d M Y H:i'),
                'data' => [
                    'nama' => $tiket->user->name,
                ]
            ]);
        }

        // Tandai hadir
        $tiket->update([
            'status_kehadiran' => 'hadir',
            'waktu_hadir' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kehadiran berhasil dicatat.',
            'data' => [
                'nama' => $tiket->user->name,
                'waktu' => $tiket->waktu_hadir->format('H:i:s'),
            ]
        ]);
    }
}
