<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriUMKM;
use App\Models\Kelurahan;
use App\Models\Pemilik;
use App\Models\SektorUmkm;
use App\Models\UMKM;
use App\Models\User;
use App\Notifications\SystemNotification;
use App\Services\AktivitasLogger;
use App\Services\UmkmService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UmkmController extends Controller
{
    public function __construct(
        protected UmkmService $umkmService = new UmkmService(),
    ) {}

    public function index(Request $request)
    {
        $search = $request->get('search');
        $kelurahan = $request->get('kelurahan');
        $kategori_id = $request->get('kategori');
        $sektor_id = $request->get('sektor');
        $status = $request->get('status_verifikasi');
        $perPage = $request->get('per_page', 10);

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

        $umkms = $query->paginate($perPage);
        $kelurahans = Kelurahan::pluck('nama_kelurahan')->toArray();
        $kategoris = KategoriUMKM::all();
        $sektors = SektorUmkm::all();

        $totalUmkm = UMKM::count();
        $terverifikasiCount = UMKM::where('status_verifikasi', 'terverifikasi')->count();
        $menungguCount = UMKM::where('status_verifikasi', 'terkirim')->count();
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

        return view('admin.umkm.index', compact('umkms', 'kelurahans', 'kategoris', 'sektors', 'totalUmkm', 'terverifikasiCount', 'menungguCount', 'aktifCount', 'percentageIncrease'));
    }

    public function scanResult(Request $request)
    {
        $no_pendaftaran = trim($request->query('q', ''));

        if (empty($no_pendaftaran)) {
            return redirect()->route('admin.umkm.index')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Nomor pendaftaran tidak ditemukan dari hasil scan.',
                ]);
        }

        $umkm = UMKM::where('no_pendaftaran', $no_pendaftaran)->first();

        if (!$umkm) {
            return redirect()->route('admin.umkm.index')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'UMKM dengan nomor pendaftaran "' . e($no_pendaftaran) . '" tidak ditemukan.',
                ]);
        }

        return redirect()->route('admin.umkm.show', $umkm->id_umkm);
    }

    public function create()
    {
        $kelurahans = Kelurahan::with('rws.rts')->get();
        $kategoris = KategoriUMKM::all();
        $sektors = SektorUmkm::all();

        return view('admin.umkm.create', compact('kelurahans', 'kategoris', 'sektors'));
    }

    public function store(Request $request)
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
            'alamat_pemilik' => 'required|string',
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

        $statusVerifikasi = $request->input('status_verifikasi', 'terkirim');
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
            ? 'UMKM "'.$umkm->nama_usaha.'" berhasil ditambahkan.'
            : 'UMKM "'.$umkm->nama_usaha.'" berhasil ditambahkan (menunggu verifikasi).';

        auth()->user()->notify(new SystemNotification(
            'UMKM Ditambahkan',
            $msgText,
            'success',
            route('admin.umkm.show', $umkm->id_umkm)
        ));

        return redirect()->route('admin.umkm.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => $msgText,
        ]);
    }

    public function show($id)
    {
        if (! $id) {
            return redirect()->route('admin.umkm.index')->with('toast', [
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
                'pengajuan' => function ($q) {
                    $q->orderBy('tanggal_pengajuan', 'desc');
                },
            ])->findOrFail($id);

            return view('admin.umkm.show', compact('umkm'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.umkm.index')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Data UMKM tidak ditemukan.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.umkm.index')->with('toast', [
                'type' => 'error',
                'title' => 'Error!',
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        // Cek apakah ID ada
        if (! $id) {
            return redirect()->route('admin.umkm.index')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'ID UMKM tidak ditemukan.',
            ]);
        }

        try {
            $umkm = UMKM::with(['pemilik', 'kategori', 'sektor'])->findOrFail($id);
            $pemiliks = Pemilik::all();
            $kategoris = KategoriUMKM::all();
            $sektors = SektorUmkm::all();
            $kelurahans = Kelurahan::with('rws.rts')->get();

            // Auto-sync kelurahan name <-> id_kelurahan agar dropdown selalu terisi
            if ($umkm->pemilik) {
                $this->umkmService->syncPemilikWilayah($umkm->pemilik);
                $umkm->load('pemilik');
            }

            return view('admin.umkm.edit', compact('umkm', 'pemiliks', 'kategoris', 'sektors', 'kelurahans'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.umkm.index')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Data UMKM tidak ditemukan.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.umkm.index')->with('toast', [
                'type' => 'error',
                'title' => 'Error!',
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        // Cek apakah ID ada
        if (! $id) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'ID UMKM tidak ditemukan.'], 400);
            }

            return redirect()->route('admin.umkm.index')->with('toast', [
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

            return redirect()->route('admin.umkm.index')->with('toast', [
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
            'Data UMKM "'.$umkm->nama_usaha.'" berhasil Anda perbarui.',
            'info',
            route('admin.umkm.show', $umkm->id_umkm)
        ));

        if ($umkm->id_petugas && $umkm->id_petugas != auth()->id()) {
            $petugas = User::find($umkm->id_petugas);
            if ($petugas) {
                $petugas->notify(new SystemNotification(
                    'Perubahan Data UMKM',
                    'Data UMKM "'.$umkm->nama_usaha.'" yang Anda daftarkan telah diperbarui oleh '.auth()->user()->name.'.',
                    'info',
                    route('operator.umkm.show', $umkm->id_umkm)
                ));
            }
        }

        $superAdmins = User::role('super_admin')->get();
        foreach ($superAdmins as $superAdmin) {
            if ($superAdmin->id != auth()->id()) {
                $superAdmin->notify(new SystemNotification(
                    'Perubahan Data UMKM',
                    'Data UMKM "'.$umkm->nama_usaha.'" telah diperbarui oleh '.auth()->user()->name.'.',
                    'info',
                    route('superadmin.umkm.show', $umkm->id_umkm)
                ));
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'UMKM berhasil diupdate']);
        }

        return redirect()->route('admin.umkm.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'UMKM "'.$umkm->nama_usaha.'" berhasil diupdate. Petugas terkait telah diberi tahu.',
        ]);
    }

    public function destroy($id)
    {
        // Cek apakah ID ada
        if (! $id) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'ID UMKM tidak ditemukan.'], 400);
            }

            return redirect()->route('admin.umkm.index')->with('toast', [
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
                route('admin.umkm.index')
            ));

            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'UMKM berhasil dihapus']);
            }

            return redirect()->route('admin.umkm.index')->with('toast', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'UMKM berhasil dihapus.',
            ]);
        } catch (ModelNotFoundException $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Data UMKM tidak ditemukan.'], 404);
            }

            return redirect()->route('admin.umkm.index')->with('toast', [
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
