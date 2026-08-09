<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Kelurahan;
use App\Models\Pengajuan;
use App\Services\AktivitasLogger;
use App\Services\PengajuanService;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function __construct(
        protected PengajuanService $pengajuanService = new PengajuanService(),
    ) {}

    public function pengajuan(Request $request)
    {
        $search = $request->get('search');
        $kelurahan = $request->get('kelurahan');
        $status = $request->get('status', $request->get('status_verifikasi'));
        $jenis_pengajuan = $request->get('jenis_pengajuan');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $perPage = $request->get('per_page', 10);

        $query = Pengajuan::with(['umkm.pemilik', 'umkm.kategori']);

        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        if ($jenis_pengajuan) {
            $query->where('jenis_pengajuan', $jenis_pengajuan);
        }

        if ($kelurahan) {
            $query->whereHas('umkm.pemilik', function ($q) use ($kelurahan) {
                $q->where('kelurahan', $kelurahan);
            });
        }

        if ($search) {
            $searchNikHash = strlen($search) === 16 && ctype_digit($search) ? hash('sha256', $search) : null;
            $query->where(function ($q) use ($search, $searchNikHash) {
                $q->where('keterangan', 'like', "%{$search}%")
                    ->orWhereHas('umkm', function ($sub) use ($search, $searchNikHash) {
                        $sub->where('nama_usaha', 'like', "%{$search}%")
                            ->orWhere('no_pendaftaran', 'like', "%{$search}%")
                            ->orWhereHas('pemilik', function ($sub2) use ($search, $searchNikHash) {
                                $sub2->where('nama_lengkap', 'like', "%{$search}%");
                                if ($searchNikHash) {
                                    $sub2->orWhere('nik_hash', $searchNikHash);
                                }
                            });
                    });
            });
        }

        if ($date_from) {
            $query->whereDate('tanggal_pengajuan', '>=', $date_from);
        }

        if ($date_to) {
            $query->whereDate('tanggal_pengajuan', '<=', $date_to);
        }

        $pengajuans = $query->orderBy('tanggal_pengajuan', 'desc')->paginate($perPage)->withQueryString();

        $kelurahans = Kelurahan::pluck('nama_kelurahan')->toArray();
        $umkms = \App\Models\UMKM::where('status_verifikasi', 'terverifikasi')->orderBy('nama_usaha')->get();

        // Statistics
        $stats = $this->pengajuanService->getStats();

        if ($request->ajax()) {
            $html = view('superadmin.pengajuan.table-data', compact('pengajuans'))->render();

            return response()->json(['success' => true, 'html' => $html]);
        }

        return view('superadmin.pengajuan.index', compact('pengajuans', 'kelurahans', 'stats', 'umkms'));
    }

    public function pengajuanModal($id)
    {
        $pengajuan = Pengajuan::with([
            'umkm.pemilik',
            'umkm.kategori',
            'umkm.sektor',
            'umkm.pengajuan' => function ($q) {
                $q->orderBy('tanggal_pengajuan', 'desc');
            },
        ])->findOrFail($id);

        return view('superadmin.pengajuan.modal-detail', compact('pengajuan'));
    }

    public function pengajuanStore(Request $request)
    {
        $rules = [
            'umkm_id' => 'required|exists:umkms,id_umkm',
            'jenis_pengajuan' => 'required|in:pembiayaan,bantuan,perizinan,lainnya',
            'nama_program' => 'nullable|string',
            'nominal' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'tanggal_pengajuan' => 'required|date',
        ];
        $rules = array_merge($rules, $this->pengajuanService->pengajuanBerkasRules());
        $request->validate($rules);

        $limitError = $this->pengajuanService->validateFinancingLimit($request);
        if ($limitError) {
            return response()->json([
                'success' => false,
                'message' => $limitError,
            ], 422);
        }

        $pengajuan = $this->pengajuanService->createPengajuan($request);

        AktivitasLogger::log(
            'Menambahkan pengajuan '.ucfirst($pengajuan->jenis_pengajuan).' baru untuk UMKM "'.($pengajuan->umkm->nama_usaha ?? 'UMKM').'".',
            'pengajuan',
            $pengajuan->umkm_id,
            $request
        );

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan Bantuan berhasil ditambahkan.',
        ]);
    }

    public function pengajuanUpdateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:proses,disetujui,ditolak,dicairkan',
            'reason' => 'required_if:status,ditolak|nullable|string',
        ]);

        $pengajuan = Pengajuan::with('umkm')->findOrFail($id);

        $result = $this->pengajuanService->updateStatus(
            $pengajuan,
            $request->status,
            $request->reason
        );

        if (! $result['success']) {
            return response()->json($result, 422);
        }

        // Log Aktivitas
        AktivitasLogger::log(
            'Memperbarui status pengajuan '.ucfirst($pengajuan->jenis_pengajuan)
                .' UMKM "'.($pengajuan->umkm->nama_usaha ?? 'UMKM').'" menjadi '.$request->status.'.',
            'pengajuan',
            $pengajuan->umkm_id,
            $request
        );

        return response()->json($result);
    }
}
