<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        // Guard: Laporan model does not exist yet. Return empty view gracefully.
        if (!class_exists(\App\Models\Laporan::class)) {
            return view('superadmin.pengaduan.index', ['pengaduans' => collect()]);
        }

        $search   = $request->get('search');
        $status   = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');
        $perPage  = (int) $request->get('per_page', 15);

        $query = \App\Models\Laporan::orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('isi', 'like', "%{$search}%")
                  ->orWhere('nama_pelapor', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $pengaduans = $query->paginate($perPage)->withQueryString();

        if ($request->ajax() || $request->has('ajax')) {
            $html = view('superadmin.pengaduan.table-data', compact('pengaduans'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $pengaduans->total()
            ]);
        }

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
