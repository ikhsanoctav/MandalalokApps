<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Exports\UmkmExport;
use App\Http\Controllers\Controller;
use App\Imports\UmkmImport;
use App\Models\KategoriUMKM;
use App\Models\Kelurahan;
use App\Models\Pemilik;
use App\Models\SektorUmkm;
use App\Models\UMKM;
use App\Models\User;
use App\Notifications\SystemNotification;
use App\Services\AktivitasLogger;
use App\Services\UmkmService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class UmkmController extends Controller
{
    public function __construct(
        protected UmkmService $umkmService = new UmkmService(),
    ) {}

    public function umkm(Request $request)
    {
        $search = $request->get('search');
        $kelurahan = $request->get('kelurahan');
        $kategori_id = $request->get('kategori');
        $sektor_id = $request->get('sektor');
        $status = $request->get('status_verifikasi');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');
        $perPage = $request->get('per_page', 20);

        $query = UMKM::with(['pemilik', 'kategori', 'sektor']);

        if ($status) {
            $query->where('status_verifikasi', $status);
        }

        if ($kelurahan) {
            $query->whereHas('pemilik', function ($q) use ($kelurahan) {
                $q->where('kelurahan', $kelurahan);
            });
        }

        if ($kategori_id) {
            $query->where('id_kategori', $kategori_id);
        }

        if ($sektor_id) {
            $query->where('id_sektor', $sektor_id);
        }

        if ($search) {
            $searchNikHash = strlen($search) === 16 && ctype_digit($search) ? hash('sha256', $search) : null;
            $query->where(function ($q) use ($search, $searchNikHash) {
                $q->where('nama_usaha', 'like', "%{$search}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$search}%")
                    ->orWhereHas('pemilik', function ($sub) use ($search, $searchNikHash) {
                        $sub->where('nama_lengkap', 'like', "%{$search}%");
                        if ($searchNikHash) {
                            $sub->orWhere('nik_hash', $searchNikHash);
                        }
                    });
            });
        }

        if ($date_from) {
            $query->whereDate('created_at', '>=', $date_from);
        }

        if ($date_to) {
            $query->whereDate('created_at', '<=', $date_to);
        }

        $sort = $request->get('sort', 'terbaru');
        if ($sort === 'terlama') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'a-z') {
            $query->orderBy('nama_usaha', 'asc');
        } elseif ($sort === 'z-a') {
            $query->orderBy('nama_usaha', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $umkms = $query->paginate($perPage)->withQueryString();

        $kelurahans = Kelurahan::pluck('nama_kelurahan')->toArray();
        $kategoris = KategoriUMKM::all();
        $sektors = SektorUmkm::all();
        $pendingCount = UMKM::where('status_verifikasi', 'menunggu_verifikasi')->count();

        $totalUmkm = UMKM::count();
        $terverifikasiCount = UMKM::where('status_verifikasi', 'terverifikasi')->count();
        $menungguCount = $pendingCount;
        $aktifCount = UMKM::where('status_usaha', 'aktif')->where('status_verifikasi', 'terverifikasi')->count();

        $lastMonthTotal = UMKM::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
            
        $percentageIncrease = 0;
        if ($lastMonthTotal > 0) {
            $percentageIncrease = (($totalUmkm - $lastMonthTotal) / $lastMonthTotal) * 100;
        } else if ($totalUmkm > 0) {
            $percentageIncrease = 100;
        }

        if ($request->ajax()) {
            $html = view('superadmin.umkm.table-data', compact('umkms'))->render();

            return response()->json(['success' => true, 'html' => $html]);
        }

        return view('superadmin.umkm.index', compact('umkms', 'kelurahans', 'kategoris', 'sektors', 'pendingCount', 'totalUmkm', 'terverifikasiCount', 'menungguCount', 'aktifCount', 'percentageIncrease'));
    }

    public function umkmCreate()
    {
        $kelurahans = Kelurahan::with('rws.rts')->get();
        $kategoris = KategoriUMKM::all();
        $sektors = SektorUmkm::all();

        return view('superadmin.umkm.create', compact('kelurahans', 'kategoris', 'sektors'));
    }

    public function umkmStore(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'tempat_lahir' => 'nullable|string|max:50',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp' => 'nullable|string|max:20',
            'email_pemilik' => 'nullable|email|max:255',
            'kelurahan' => 'required|string|max:100',
            'rw' => 'nullable|string|max:10',
            'rt' => 'nullable|string|max:10',
            'alamat_pemilik' => 'nullable|string',
            'nama_usaha' => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategori_umkms,id',
            'id_sektor' => 'nullable|exists:sektor_umkms,id',
            'bentuk_jualan' => 'required|string|max:100',
            'perkiraan_omset' => 'nullable|numeric',
            'status_usaha' => 'nullable|in:aktif,non_aktif,tutup,pindah',
            'tahun_berdiri' => 'nullable|integer|min:1900|max:'.date('Y'),
            'no_izin_usaha' => 'nullable|string|max:100',
            'jenis_izin' => 'nullable|in:NIB,SIUP,SKU,Lainnya',
            'npwp_usaha' => 'nullable|string|max:50',
            'telp_usaha' => 'nullable|string|max:20',
            'email_usaha' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'media_sosial' => 'nullable|array',
            'alamat_usaha' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
            'jumlah_tenaga_kerja' => 'nullable|integer|min:0',
            'tenaga_kerja_laki' => 'nullable|integer|min:0',
            'tenaga_kerja_perempuan' => 'nullable|integer|min:0',
            'riwayat_bantuan' => 'nullable|string',
            'foto_utama' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'foto_gallery.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Simpan pemilik & UMKM via service
        $pemilik = $this->umkmService->findOrCreatePemilik($request);

        $statusVerifikasi = $request->input('status_verifikasi', 'menunggu_verifikasi');
        $umkmData = $this->umkmService->buildUmkmData(
            $request,
            $pemilik->id_pemilik,
            auth()->id(),
            $statusVerifikasi
        );
        $umkmData['id_umkm'] = (string) Str::uuid();
        $umkm = UMKM::create($umkmData);

        AktivitasLogger::log(
            'Menambahkan UMKM '.$umkm->nama_usaha,
            'tambah',
            $umkm->id_umkm
        );

        $msgText = $statusVerifikasi === 'terverifikasi'
            ? 'UMKM "'.$umkm->nama_usaha.'" berhasil ditambahkan dan terverifikasi.'
            : 'UMKM "'.$umkm->nama_usaha.'" berhasil ditambahkan (menunggu verifikasi).';

        auth()->user()->notify(new SystemNotification(
            'UMKM Ditambahkan',
            $msgText,
            'success',
            route('superadmin.umkm.show', $umkm->id_umkm)
        ));

        return redirect()->route('superadmin.umkm')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => $msgText,
        ]);
    }

    // ============ PERBAIKAN DI FUNCTION UMKMSHOW ============
    public function umkmShow($id)
    {
        // Cek apakah ID ada
        if (! $id) {
            return redirect()->route('superadmin.umkm')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'ID UMKM tidak ditemukan.',
            ]);
        }

        try {
            $umkm = UMKM::with([
                'pemilik',
                'kategori',
                'sektor',
                'latestVerifikasiLapangan.petugas',
                'pengajuan' => function ($q) {
                    $q->orderBy('tanggal_pengajuan', 'desc');
                },
            ])->findOrFail($id);

            return view('superadmin.umkm.show', compact('umkm'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('superadmin.umkm')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Data UMKM tidak ditemukan.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('superadmin.umkm')->with('toast', [
                'type' => 'error',
                'title' => 'Error!',
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ]);
        }
    }

    public function umkmEdit($id)
    {
        // Cek apakah ID ada
        if (! $id) {
            return redirect()->route('superadmin.umkm')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'ID UMKM tidak ditemukan.',
            ]);
        }

        try {
            $umkm = UMKM::with(['pemilik', 'kategori', 'sektor', 'latestVerifikasiLapangan.petugas'])->findOrFail($id);
            $pemiliks = Pemilik::all();
            $kategoris = KategoriUMKM::all();
            $sektors = SektorUmkm::all();
            $kelurahans = Kelurahan::with('rws.rts')->get();

            // Auto-sync kelurahan name <-> id_kelurahan agar dropdown selalu terisi
            if ($umkm->pemilik) {
                $this->umkmService->syncPemilikWilayah($umkm->pemilik);
                $umkm->load('pemilik'); // reload setelah sync
            }

            return view('superadmin.umkm.edit', compact('umkm', 'pemiliks', 'kategoris', 'sektors', 'kelurahans'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('superadmin.umkm')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Data UMKM tidak ditemukan.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('superadmin.umkm')->with('toast', [
                'type' => 'error',
                'title' => 'Error!',
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ]);
        }
    }

    public function umkmUpdate(Request $request, $id)
    {
        // Cek apakah ID ada
        if (! $id) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'ID UMKM tidak ditemukan.'], 400);
            }

            return redirect()->route('superadmin.umkm')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'ID UMKM tidak ditemukan.',
            ]);
        }

        try {
            $umkm = UMKM::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Data UMKM tidak ditemukan.'], 404);
            }

            return redirect()->route('superadmin.umkm')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Data UMKM tidak ditemukan.',
            ]);
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp' => 'nullable|string|max:20',
            'email_pemilik' => 'nullable|email|max:255',
            'kelurahan' => 'required|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'rt' => 'nullable|string|max:10',
            'alamat_pemilik' => 'nullable|string',
            'nama_usaha' => 'required|string|max:255',
            'id_kategori' => 'nullable|exists:kategori_umkms,id',
            'id_sektor' => 'nullable|exists:sektor_umkms,id',
            'bentuk_jualan' => 'required|string|max:100',
            'perkiraan_omset' => 'nullable|numeric',
            'status_usaha' => 'nullable|in:aktif,non_aktif,tutup,pindah',
            'tahun_berdiri' => 'nullable|integer|min:1900|max:'.date('Y'),
            'no_izin_usaha' => 'nullable|string|max:100',
            'jenis_izin' => 'nullable|in:NIB,SIUP,SKU,Lainnya',
            'npwp_usaha' => 'nullable|string|max:50',
            'telp_usaha' => 'nullable|string|max:20',
            'email_usaha' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'media_sosial' => 'nullable|array',
            'alamat_usaha' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
            'jumlah_tenaga_kerja' => 'nullable|integer|min:0',
            'tenaga_kerja_laki' => 'nullable|integer|min:0',
            'tenaga_kerja_perempuan' => 'nullable|integer|min:0',
            'riwayat_bantuan' => 'nullable|string',
            'foto_utama' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'foto_gallery.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Update data pemilik via service
        $pemilik = $umkm->pemilik;
        $nikHash = hash('sha256', $request->nik);

        if ($pemilik && $pemilik->nik_hash === $nikHash) {
            $this->umkmService->updatePemilikFromRequest($pemilik, $request);
        } else {
            $newPemilik = Pemilik::where('nik_hash', $nikHash)->first();
            if (!$newPemilik) {
                $newPemilik = $this->umkmService->findOrCreatePemilik($request);
            }
            $umkm->id_pemilik = $newPemilik->id_pemilik;
        }

        // Handle foto uploads via service
        $fotos = $this->umkmService->handleFotoUploads($request, $umkm);
        if ($fotos['foto_utama'] !== $umkm->foto_utama) {
            $umkm->foto_utama = $fotos['foto_utama'];
        }
        if ($fotos['foto_gallery'] !== $umkm->foto_gallery) {
            $umkm->foto_gallery = $fotos['foto_gallery'];
        }

        // Update data UMKM
        $umkm->update([
            'nama_usaha' => $request->nama_usaha,
            'id_kategori' => $request->id_kategori,
            'id_sektor' => $request->id_sektor,
            'bentuk_jualan' => $request->bentuk_jualan,
            'status_usaha' => $request->status_usaha ?? 'aktif',
            'tahun_berdiri' => $request->tahun_berdiri,
            'no_izin_usaha' => $request->no_izin_usaha,
            'jenis_izin' => $request->jenis_izin,
            'npwp_usaha' => $request->npwp_usaha,
            'telp_usaha' => $request->telp_usaha,
            'email_usaha' => $request->email_usaha,
            'website' => $request->website,
            'media_sosial' => $request->media_sosial,
            'jumlah_tenaga_kerja' => $request->jumlah_tenaga_kerja ?? 0,
            'tenaga_kerja_laki' => $request->tenaga_kerja_laki ?? 0,
            'tenaga_kerja_perempuan' => $request->tenaga_kerja_perempuan ?? 0,
            'alamat_usaha' => $request->alamat_usaha,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'deskripsi' => $request->deskripsi,
        ]);

        AktivitasLogger::log(
            'Mengupdate UMKM '.$umkm->nama_usaha,
            'update',
            $umkm->id_umkm
        );

        auth()->user()->notify(new SystemNotification(
            'UMKM Diperbarui',
            'Data UMKM "'.$umkm->nama_usaha.'" berhasil diperbarui.',
            'info',
            route('superadmin.umkm.show', $umkm->id_umkm)
        ));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'UMKM berhasil diupdate']);
        }

        return redirect()->route('superadmin.umkm')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'UMKM "'.$umkm->nama_usaha.'" berhasil diupdate.',
        ]);
    }

    public function umkmDestroy($id)
    {
        // Cek apakah ID ada
        if (! $id) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'ID UMKM tidak ditemukan.'], 400);
            }

            return redirect()->route('superadmin.umkm')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'ID UMKM tidak ditemukan.',
            ]);
        }

        try {
            $umkm = UMKM::findOrFail($id);
            $nama = $umkm->nama_usaha;

            $this->umkmService->deleteUmkmWithFiles($umkm);

            AktivitasLogger::log(
                'Menghapus UMKM '.$nama,
                'hapus',
                $id
            );

            auth()->user()->notify(new SystemNotification(
                'UMKM Dihapus',
                'Data UMKM "'.$nama.'" beserta file terkait berhasil dihapus.',
                'danger',
                route('superadmin.umkm')
            ));

            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'UMKM berhasil dihapus']);
            }

            return redirect()->route('superadmin.umkm')->with('toast', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'UMKM berhasil dihapus.',
            ]);
        } catch (ModelNotFoundException $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Data UMKM tidak ditemukan.'], 404);
            }

            return redirect()->route('superadmin.umkm')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Data UMKM tidak ditemukan.',
            ]);
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            return redirect()->back()->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Gagal menghapus UMKM: '.$e->getMessage(),
            ]);
        }
    }

    public function umkmModal($id)
    {
        // Cek apakah ID ada
        if (! $id) {
            return response()->json([
                'success' => false,
                'message' => 'ID UMKM tidak ditemukan.',
            ], 400);
        }

        try {
            $umkm = UMKM::with(['pemilik', 'kategori', 'sektor', 'produks'])->findOrFail($id);
            $html = view('superadmin.umkm.modal-detail', compact('umkm'))->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'isPending' => $umkm->status_verifikasi === 'menunggu_verifikasi',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data UMKM tidak ditemukan.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }

    public function umkmSuspend($id, Request $request)
    {
        // Cek apakah ID ada
        if (! $id) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'ID UMKM tidak ditemukan.'], 400);
            }
            return redirect()->route('superadmin.umkm')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'ID UMKM tidak ditemukan.',
            ]);
        }

        try {
            $umkm = UMKM::findOrFail($id);

            // Hanya UMKM berstatus 'terverifikasi' yang dapat ditangguhkan
            if ($umkm->status_verifikasi !== 'terverifikasi') {
                $msg = 'Hanya UMKM terverifikasi yang dapat ditangguhkan/dinonaktifkan.';

                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 400);
                }
                return redirect()->route('superadmin.umkm')->with('toast', [
                    'type' => 'warning',
                    'title' => 'Perhatian!',
                    'message' => $msg,
                ]);
            }

            // Validasi reason
            $reason = $request->input('reason');
            if (! $reason) {
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Alasan penolakan harus diisi.'], 400);
                }
                return redirect()->back()->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Alasan penolakan harus diisi.',
                ]);
            }

            $umkm->update([
                'status_verifikasi' => 'terverifikasi',
                'status_usaha' => 'non_aktif',
                'catatan_penolakan' => $reason,
            ]);

            AktivitasLogger::log(
                'Menangguhkan UMKM '.$umkm->nama_usaha.' dengan alasan: '.$reason,
                'penangguhan',
                $id
            );

            auth()->user()->notify(new SystemNotification(
                'UMKM Ditangguhkan',
                'UMKM "'.$umkm->nama_usaha.'" telah ditangguhkan. Alasan: '.$reason,
                'warning',
                route('superadmin.umkm.show', $umkm->id_umkm)
            ));

            // Notifikasi ke Petugas Lapangan yang mendaftarkan
            if ($umkm->id_petugas) {
                $petugas = User::find($umkm->id_petugas);
                if ($petugas) {
                    $petugas->notify(new SystemNotification(
                        '❌ UMKM Wilayah Anda Ditangguhkan',
                        'UMKM "'.$umkm->nama_usaha.'" yang ada di wilayah Anda ditangguhkan oleh Super Admin. Alasan: '.$reason,
                        'danger',
                        route('operator.umkm.show', $umkm->id_umkm)
                    ));
                }
            }

            // Notifikasi ke Pelaku UMKM
            $pemilik = $umkm->pemilik;
            if ($pemilik && $pemilik->nik_hash) {
                $pelaku = User::where('nik_hash', $pemilik->nik_hash)->first();
                if ($pelaku) {
                    $pelaku->notify(new SystemNotification(
                        '❌ UMKM Anda Ditangguhkan',
                        'Status UMKM "'.$umkm->nama_usaha.'" Anda telah ditangguhkan oleh Admin. Alasan: '.$reason,
                        'danger',
                        route('pelaku.dashboard')
                    ));
                }
            }

            // Notifikasi ke Admin Kecamatan
            $admins = User::role('admin_kecamatan')->get();
            foreach ($admins as $admin) {
                $admin->notify(new SystemNotification(
                    'UMKM Ditangguhkan',
                    'UMKM "'.$umkm->nama_usaha.'" telah ditangguhkan oleh Super Admin dengan alasan: '.$reason,
                    'warning',
                    route('admin.verifikasi.show', $umkm->id_umkm)
                ));
            }

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'UMKM "'.$umkm->nama_usaha.'" telah ditangguhkan.']);
            }

            session()->flash('toast', [
                'type' => 'warning',
                'title' => 'Penangguhan Berhasil!',
                'message' => 'UMKM "'.$umkm->nama_usaha.'" telah ditangguhkan.',
            ]);

            return redirect()->route('superadmin.umkm');

        } catch (ModelNotFoundException $e) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Data UMKM tidak ditemukan.'], 404);
            }
            return redirect()->route('superadmin.umkm')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Data UMKM tidak ditemukan.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }

    public function umkmVerify($id)
    {
        if (! $id) {
            return response()->json(['success' => false, 'message' => 'ID UMKM tidak ditemukan.'], 400);
        }

        try {
            $umkm = UMKM::findOrFail($id);

            $umkm->update([
                'status_verifikasi' => 'terverifikasi',
                'tanggal_verifikasi' => $umkm->tanggal_verifikasi ?? now(),
                'catatan_penolakan' => null,
            ]);

            AktivitasLogger::log(
                'Super Admin memverifikasi UMKM '.$umkm->nama_usaha,
                'verifikasi',
                $id
            );

            // Notifications
            auth()->user()->notify(new SystemNotification(
                '✅ UMKM Diverifikasi',
                'Pengajuan UMKM "'.$umkm->nama_usaha.'" berhasil disetujui.',
                'success',
                route('superadmin.umkm.show', $umkm->id_umkm)
            ));

            if ($umkm->id_petugas) {
                $petugas = User::find($umkm->id_petugas);
                if ($petugas) {
                    $petugas->notify(new SystemNotification(
                        '✅ UMKM Anda Diverifikasi!',
                        'UMKM "'.$umkm->nama_usaha.'" yang Anda daftarkan telah diverifikasi & disetujui oleh Super Admin.',
                        'success',
                        route('operator.umkm.show', $umkm->id_umkm)
                    ));
                }
            } else {
                $kelurahan_id = $umkm->pemilik->id_kelurahan ?? null;
                $operators = User::role('operator_lapangan')->when($kelurahan_id, function($q) use ($kelurahan_id) {
                    $q->where('id_kelurahan', $kelurahan_id);
                })->get();
                foreach ($operators as $operator) {
                    $operator->notify(new SystemNotification(
                        '✅ UMKM Diverifikasi',
                        'UMKM "'.$umkm->nama_usaha.'" di wilayah Anda telah diverifikasi & disetujui oleh Super Admin.',
                        'success',
                        route('operator.umkm.show', $umkm->id_umkm)
                    ));
                }
            }

            $pemilik = $umkm->pemilik;
            if ($pemilik && $pemilik->nik_hash) {
                $pelaku = User::where('nik_hash', $pemilik->nik_hash)->first();
                if ($pelaku) {
                    $pelaku->notify(new SystemNotification(
                        '✅ UMKM Anda Diverifikasi!',
                        'UMKM "'.$umkm->nama_usaha.'" yang Anda daftarkan telah diverifikasi & disetujui.',
                        'success',
                        route('pelaku.dashboard')
                    ));
                }
            }

            $admins = User::role('admin_kecamatan')->get();
            foreach ($admins as $admin) {
                $admin->notify(new SystemNotification(
                    '✅ UMKM Diverifikasi',
                    'UMKM "'.$umkm->nama_usaha.'" telah diverifikasi oleh Super Admin.',
                    'success',
                    route('admin.verifikasi.show', $umkm->id_umkm)
                ));
            }

            return response()->json([
                'success' => true,
                'message' => 'UMKM "'.$umkm->nama_usaha.'" telah disetujui.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage()
            ], 500);
        }
    }

    public function umkmReject($id, Request $request)
    {
        if (! $id) {
            return response()->json(['success' => false, 'message' => 'ID UMKM tidak ditemukan.'], 400);
        }

        try {
            $umkm = UMKM::findOrFail($id);

            if ($umkm->status_verifikasi !== 'menunggu_verifikasi') {
                $messages = [
                    'draft' => 'UMKM masih berstatus Draft. Tidak ada pengajuan untuk ditolak.',
                    'terverifikasi' => 'UMKM sudah diverifikasi, tidak dapat ditolak.',
                    'ditolak' => 'UMKM sudah pernah ditolak sebelumnya.',
                ];
                $msg = $messages[$umkm->status_verifikasi] ?? 'Status UMKM tidak valid untuk penolakan.';

                return response()->json(['success' => false, 'message' => $msg], 400);
            }

            $reason = $request->input('alasan') ?? $request->input('reason');
            if (! $reason) {
                return response()->json(['success' => false, 'message' => 'Alasan penolakan harus diisi.'], 400);
            }

            $umkm->update([
                'status_verifikasi' => 'ditolak',
                'tanggal_verifikasi' => null,
                'catatan_penolakan' => $reason,
            ]);

            AktivitasLogger::log(
                'Super Admin menolak UMKM '.$umkm->nama_usaha.' dengan alasan: '.$reason,
                'penolakan',
                $id
            );

            auth()->user()->notify(new SystemNotification(
                '❌ UMKM Ditolak',
                'Pengajuan UMKM "'.$umkm->nama_usaha.'" telah ditolak.',
                'warning',
                route('superadmin.umkm.show', $umkm->id_umkm)
            ));

            if ($umkm->id_petugas) {
                $petugas = User::find($umkm->id_petugas);
                if ($petugas) {
                    $petugas->notify(new SystemNotification(
                        '❌ UMKM Anda Ditolak',
                        'UMKM "'.$umkm->nama_usaha.'" yang Anda daftarkan ditolak oleh Super Admin. Alasan: '.$reason,
                        'danger',
                        route('operator.umkm.show', $umkm->id_umkm)
                    ));
                }
            }

            $pemilik = $umkm->pemilik;
            if ($pemilik && $pemilik->nik_hash) {
                $pelaku = User::where('nik_hash', $pemilik->nik_hash)->first();
                if ($pelaku) {
                    $pelaku->notify(new SystemNotification(
                        '❌ UMKM Anda Ditolak',
                        'UMKM "'.$umkm->nama_usaha.'" yang Anda daftarkan ditolak oleh Super Admin. Alasan: '.$reason,
                        'danger',
                        route('pelaku.dashboard')
                    ));
                }
            }

            $admins = User::role('admin_kecamatan')->get();
            foreach ($admins as $admin) {
                $admin->notify(new SystemNotification(
                    '❌ UMKM Ditolak',
                    'UMKM "'.$umkm->nama_usaha.'" telah ditolak oleh Super Admin dengan alasan: '.$reason,
                    'warning',
                    route('admin.verifikasi.show', $umkm->id_umkm)
                ));
            }

            return response()->json([
                'success' => true,
                'message' => 'UMKM "'.$umkm->nama_usaha.'" telah ditolak.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage()
            ], 500);
        }
    }

    public function umkmImport(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,csv,xls|max:5120',
        ]);

        try {
            Excel::import(new UmkmImport, $request->file('file_excel'));

            AktivitasLogger::log('Mengimport data UMKM dari Excel', 'import', null);

            return redirect()->route('superadmin.umkm')->with('toast', [
                'type' => 'success',
                'title' => 'Import Berhasil!',
                'message' => 'Data UMKM berhasil diimport ke dalam sistem.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('superadmin.umkm')->with('toast', [
                'type' => 'error',
                'title' => 'Import Gagal!',
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ]);
        }
    }

    public function umkmImportTemplate()
    {
        $headers = [
            'nik', 'nama_pemilik', 'jenis_kelamin', 'no_hp', 'kelurahan', 'kecamatan', 'kota_kab', 'provinsi',
            'nama_usaha', 'sektor', 'bentuk_jualan', 'perkiraan_omset', 'status_usaha', 'tahun_berdiri', 'alamat_usaha', 'jumlah_tenaga_kerja', 'no_izin_usaha', 'npwp_usaha', 'telp_usaha', 'email_usaha'
        ];

        $export = new class($headers) implements FromArray, WithHeadings
        {
            protected $headings;

            public function __construct($headings)
            {
                $this->headings = $headings;
            }

            public function array(): array
            {
                return [];
            }

            public function headings(): array
            {
                return $this->headings;
            }
        };

        return Excel::download($export, 'Template_Import_UMKM.xlsx');
    }

    public function umkmExport(Request $request)
    {
        $format = $request->get('format', 'excel');
        $filename = 'data_umkm_'.date('Ymd_His');

        if ($format === 'pdf') {
            $umkms = UMKM::with(['pemilik', 'kategori', 'sektor', 'petugas'])->get();
            $pdf = Pdf::loadView('superadmin.umkm.export_pdf', compact('umkms'))->setPaper('a4', 'landscape');

            return $pdf->download($filename.'.pdf');
        }

        return Excel::download(new UmkmExport, $filename.'.xlsx');
    }

    public function printQr($id)
    {
        $umkm = UMKM::with('pemilik')->findOrFail($id);
        return view('umkm.print-qr', compact('umkm'));
    }

    public function printDokumen($id)
    {
        $umkm = UMKM::with(['pemilik', 'kategori', 'sektor', 'petugas'])->findOrFail($id);
        return view('umkm.print-dokumen', compact('umkm'));
    }
}
