<?php

namespace App\Http\Controllers\Pelaku;

use App\Http\Controllers\Controller;
use App\Http\Traits\HandlesPengajuanBerkas;
use App\Models\Pemilik;
use App\Models\UMKM;
use App\Models\Pengajuan;
use App\Models\Setting;
use App\Services\AktivitasLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    use HandlesPengajuanBerkas;

    public function index(Request $request)
    {
        if (!Setting::get('pelaku_submission_active', false)) {
            return redirect()->route('pelaku.dashboard')->with('toast', [
                'type' => 'warning',
                'title' => 'Fitur Nonaktif',
                'message' => 'Pengajuan bantuan oleh pelaku UMKM sedang dinonaktifkan.',
            ]);
        }

        $user = Auth::user();
        $pemilik = null;
        if ($user->nik_hash) {
            $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();
        }

        $umkms = collect();
        if ($pemilik) {
            $umkms = UMKM::where('id_pemilik', $pemilik->id_pemilik)
                ->where('status_verifikasi', 'terverifikasi')
                ->orderBy('nama_usaha')
                ->get();
        }

        $umkmIds = $umkms->pluck('id_umkm');
        $pengajuans = Pengajuan::whereIn('umkm_id', $umkmIds)
            ->with('umkm')
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(10);

        return view('pelaku.pengajuan.index', compact('pengajuans', 'umkms'));
    }

    public function store(Request $request)
    {
        if (!Setting::get('pelaku_submission_active', false)) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan bantuan oleh pelaku UMKM sedang dinonaktifkan.',
            ], 403);
        }

        $rules = [
            'umkm_id' => 'required|exists:umkms,id_umkm',
            'jenis_pengajuan' => 'required|in:pembiayaan,bantuan,perizinan,lainnya',
            'nama_program' => 'nullable|string',
            'nominal' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'tanggal_pengajuan' => 'required|date',
        ];
        $rules = array_merge($rules, $this->pengajuanBerkasRules());
        $request->validate($rules);

        $user = Auth::user();
        $pemilik = null;
        if ($user->nik_hash) {
            $pemilik = Pemilik::where('nik_hash', $user->nik_hash)->first();
        }

        if (!$pemilik) {
            return response()->json([
                'success' => false,
                'message' => 'Data pemilik tidak ditemukan untuk akun Anda.',
            ], 403);
        }

        $umkm = UMKM::where('id_pemilik', $pemilik->id_pemilik)
            ->where('id_umkm', $request->umkm_id)
            ->where('status_verifikasi', 'terverifikasi')
            ->first();

        if (!$umkm) {
            return response()->json([
                'success' => false,
                'message' => 'UMKM tidak valid. Daftarkan atau perbarui data UMKM terlebih dahulu.',
            ], 403);
        }

        $maxLimit = (float) Setting::get('max_financing_limit', 50000000);
        if ($request->jenis_pengajuan === 'pembiayaan' && $request->filled('nominal') && (float)$request->nominal > $maxLimit) {
            return response()->json([
                'success' => false,
                'message' => 'Nominal pembiayaan melebihi batas maksimal Rp ' . number_format($maxLimit, 0, ',', '.') . '.',
            ], 422);
        }

        $uploadedBerkas = $this->processBerkasUploads($request);

        $pengajuan = Pengajuan::create([
            'id_pengajuan' => (string) \Illuminate\Support\Str::uuid(),
            'umkm_id' => $request->umkm_id,
            'jenis_pengajuan' => $request->jenis_pengajuan,
            'nama_program' => $request->jenis_pengajuan === 'bantuan' ? $request->nama_program : null,
            'nominal' => $request->jenis_pengajuan === 'pembiayaan' ? $request->nominal : null,
            'keterangan' => $request->keterangan,
            'status' => 'menunggu',
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'berkas' => $uploadedBerkas,
        ]);

        AktivitasLogger::log(
            'Pelaku UMKM menambahkan pengajuan '.ucfirst($pengajuan->jenis_pengajuan).' baru untuk UMKM "'.$umkm->nama_usaha.'".',
            'pengajuan',
            $pengajuan->umkm_id,
            $request
        );

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan Bantuan berhasil ditambahkan.',
        ]);
    }
}
