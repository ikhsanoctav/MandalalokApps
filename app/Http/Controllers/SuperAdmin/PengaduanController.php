<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    public function index()
    {
        // Guard: Laporan model does not exist yet. Return empty view gracefully.
        if (!class_exists(\App\Models\Laporan::class)) {
            return view('superadmin.pengaduan.index', ['pengaduans' => collect()]);
        }

        $pengaduans = \App\Models\Laporan::orderBy('created_at', 'desc')->get();
        return view('superadmin.pengaduan.index', compact('pengaduans'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (!class_exists(\App\Models\Laporan::class)) {
            return redirect()->back()->with('error', 'Modul pengaduan belum tersedia.');
        }

        $request->validate(['status' => 'required|in:Menunggu,Diproses,Selesai']);
        $laporan = \App\Models\Laporan::findOrFail($id);
        $laporan->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pengaduan berhasil diperbarui.');
    }
}
