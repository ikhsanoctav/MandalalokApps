<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\KategoriUMKM;
use App\Models\Kelurahan;
use App\Models\Pemilik;
use App\Models\SektorUmkm;
use App\Models\UMKM;
use App\Models\User;
use App\Notifications\SystemNotification;
use App\Services\UmkmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UmkmController extends Controller
{
    public function __construct(
        protected UmkmService $umkmService = new UmkmService(),
    ) {}

    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'saya');

        // Tab: Input Saya
        $umkmSaya = UMKM::with(['pemilik', 'kategori', 'sektor'])
            ->where('id_petugas', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Tab: Semua Wilayah
        $kelurahan = $user->kelurahan;
        $umkmWilayah = collect();
        if ($kelurahan && $kelurahan !== '-') {
            $umkmWilayah = UMKM::with(['pemilik', 'kategori', 'sektor'])
                ->whereHas('pemilik', function ($q) use ($kelurahan) {
                    $q->where('kelurahan', $kelurahan);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Data peta (UMKM dengan koordinat di wilayah)
        $mapData = $umkmWilayah->filter(fn ($u) => $u->latitude && $u->longitude)
            ->map(fn ($u) => [
                'id' => $u->id_umkm,
                'nama' => $u->nama_usaha,
                'lat' => (float) $u->latitude,
                'lng' => (float) $u->longitude,
                'kategori' => $u->kategori->nama_kategori ?? '-',
                'status' => $u->status_verifikasi,
                'pemilik' => $u->pemilik->nama_lengkap ?? '-',
                'alamat' => $u->alamat_usaha ?? '-',
            ])->values();

        // Statistik wilayah
        $stats = [
            'total' => $umkmWilayah->count(),
            'terverifikasi' => $umkmWilayah->where('status_verifikasi', 'terverifikasi')->count(),
            'ditolak' => $umkmWilayah->where('status_verifikasi', 'ditolak')->count(),
            'per_kategori' => $umkmWilayah->groupBy(fn ($u) => $u->kategori->nama_kategori ?? 'Lainnya')
                ->map->count()->sortDesc(),
        ];

        return view('petugas.umkm.index', compact(
            'umkmSaya',
            'umkmWilayah',
            'mapData',
            'stats',
            'kelurahan',
            'tab'
        ));
    }

    public function show($id)
    {
        // Only allow viewing UMKM registered by this petugas (ownership check)
        $umkm = UMKM::with(['pemilik', 'kategori', 'sektor', 'pengajuan' => function ($q) {
            $q->orderBy('tanggal_pengajuan', 'desc');
        }])
            ->findOrFail($id);

        return view('petugas.umkm.show', compact('umkm'));
    }

    public function scanResult(Request $request)
    {
        $no_pendaftaran = trim($request->query('q', ''));

        if (empty($no_pendaftaran)) {
            return redirect()->route('operator.umkm.index')
                ->with('error', 'Nomor pendaftaran tidak ditemukan dari hasil scan.');
        }

        $umkm = UMKM::where('no_pendaftaran', $no_pendaftaran)->first();

        if (!$umkm) {
            return redirect()->route('operator.umkm.index')
                ->with('error', 'UMKM dengan nomor pendaftaran "' . e($no_pendaftaran) . '" tidak ditemukan.');
        }

        return redirect()->route('operator.umkm.show', $umkm->id_umkm);
    }

    public function create()
    {
        $kategori = KategoriUMKM::all();
        $sektor = SektorUmkm::all();
        $kelurahan = Kelurahan::with('rws.rts')->get();

        return view('petugas.umkm.create', compact('kategori', 'sektor', 'kelurahan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // Validasi Pemilik
            'nik' => 'required|digits:16',
            'nama_lengkap' => 'required|string|max:150',
            'tempat_lahir' => 'nullable|string|max:50',
            'tanggal_lahir' => 'nullable|date',
            'no_hp' => 'nullable|string|max:20',
            'email_pemilik' => 'nullable|email|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat_pemilik' => 'required|string',
            'kelurahan' => 'required|string|max:100',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            // Validasi UMKM
            'nama_usaha' => 'required|string|max:150',
            'id_kategori' => 'required|exists:kategori_umkms,id',
            'id_sektor' => 'required|exists:sektor_umkms,id',
            'status_usaha' => 'nullable|in:aktif,non_aktif,tutup,pindah',
            'bentuk_jualan' => 'required|string|max:100',
            'perkiraan_omset' => 'nullable|string|max:50',
            'tahun_berdiri' => 'nullable|numeric|digits:4',
            'no_izin_usaha' => 'nullable|string|max:100',
            'jenis_izin' => 'nullable|in:NIB,SIUP,SKU,Lainnya',
            'npwp_usaha' => 'nullable|string|max:50',
            'telp_usaha' => 'nullable|string|max:20',
            'email_usaha' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'media_sosial' => 'nullable|array',
            'alamat_usaha' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
            'jumlah_tenaga_kerja' => 'nullable|integer|min:0',
            'tenaga_kerja_laki' => 'nullable|integer|min:0',
            'tenaga_kerja_perempuan' => 'nullable|integer|min:0',
            'riwayat_bantuan' => 'nullable|string',
            'foto_utama' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'foto_gallery.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari tepat 16 digit angka.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'alamat_pemilik.required' => 'Alamat pemilik wajib diisi.',
            'kelurahan.required' => 'Kelurahan wajib dipilih.',
            'nama_usaha.required' => 'Nama usaha wajib diisi.',
            'id_kategori.required' => 'Kategori usaha wajib dipilih.',
            'id_sektor.required' => 'Sektor usaha wajib dipilih.',
            'alamat_usaha.required' => 'Alamat usaha wajib diisi.',
            'foto_utama.image' => 'File foto harus berupa gambar.',
            'foto_utama.mimes' => 'Format foto harus jpeg, jpg, png, atau webp.',
            'foto_utama.max' => 'Ukuran foto maksimal 5MB.',
        ]);

        DB::beginTransaction();
        try {
            // Cek apakah Pemilik dengan NIK ini sudah ada
            $nikHash = hash('sha256', $request->nik);
            $pemilik = Pemilik::where('nik_hash', $nikHash)->first();

            if (! $pemilik) {
                $address = $this->umkmService->resolveAddress($request->kelurahan);
                $kelurahan = Kelurahan::where('nama_kelurahan', $request->kelurahan)->first();

                // Buat Pemilik Baru jika belum ada
                $pemilik = Pemilik::create([
                    'nik' => $request->nik,
                    'nama_lengkap' => $request->nama_lengkap,
                    'tempat_lahir' => $request->tempat_lahir ?? '-',
                    'tanggal_lahir' => $request->tanggal_lahir ?? '2000-01-01',
                    'no_hp' => $request->no_hp ?? '-',
                    'email' => $request->email_pemilik,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'alamat' => $request->alamat_pemilik,
                    'rt' => $this->umkmService->normalizeWilayahNumber($request->rt) ?? '00',
                    'rw' => $this->umkmService->normalizeWilayahNumber($request->rw) ?? '00',
                    'kelurahan' => $request->kelurahan,
                    'id_kelurahan' => $kelurahan?->id,
                    'kecamatan' => $address['kecamatan'],
                    'kota_kab' => $address['kota_kab'],
                    'provinsi' => $address['provinsi'],
                    'kode_pos' => $request->kode_pos ?? $address['kode_pos'],
                    'status_verifikasi_ktp' => 'terverifikasi',
                ]);
            } else {
                $this->umkmService->syncPemilikWilayah($pemilik);
            }

            // Handle upload foto utama
            $fotoUtama = null;
            if ($request->hasFile('foto_utama')) {
                $file = $request->file('foto_utama');
                $filename = time().'_'.Auth::id().'.'.$file->getClientOriginalExtension();
                $fotoUtama = $file->storeAs('umkm/foto_utama', $filename, 'public');
            }

            // Handle upload foto gallery
            $galleryPaths = [];
            if ($request->hasFile('foto_gallery')) {
                foreach ($request->file('foto_gallery') as $i => $file) {
                    $filename = time().'_gallery_'.$i.'_'.Auth::id().'.'.$file->getClientOriginalExtension();
                    $galleryPaths[] = $file->storeAs('umkm/gallery', $filename, 'public');
                }
            }

            // Buat UMKM Baru
            $umkm = UMKM::create([
                'nama_usaha' => $request->nama_usaha,
                'id_pemilik' => $pemilik->id_pemilik,
                'id_kategori' => $request->id_kategori,
                'id_sektor' => $request->id_sektor,
                'status_usaha' => $request->status_usaha ?? 'aktif',
                'bentuk_jualan' => $request->bentuk_jualan,
                'tahun_berdiri' => $request->tahun_berdiri,
                'no_izin_usaha' => $request->no_izin_usaha,
                'jenis_izin' => $request->jenis_izin,
                'npwp_usaha' => $request->npwp_usaha,
                'telp_usaha' => $request->telp_usaha,
                'email_usaha' => $request->email_usaha,
                'website' => $request->website,
                'media_sosial' => $request->media_sosial,
                'alamat_usaha' => $request->alamat_usaha,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'deskripsi' => $request->deskripsi,
                'foto_utama' => $fotoUtama,
                'foto_gallery' => ! empty($galleryPaths) ? $galleryPaths : null,
                'jumlah_tenaga_kerja' => $request->jumlah_tenaga_kerja ?? 0,
                'tenaga_kerja_laki' => $request->tenaga_kerja_laki ?? 0,
                'tenaga_kerja_perempuan' => $request->tenaga_kerja_perempuan ?? 0,
                'id_petugas' => Auth::id(),
                'status_verifikasi' => 'terverifikasi',
                'tanggal_pendataan' => now(),
            ]);

            DB::commit();

            // Kirim notifikasi ke Admin Kecamatan & SuperAdmin
            $kelurahan_id = $pemilik->id_kelurahan ?? null;
            $admins = User::role('admin_kecamatan')->get();
            foreach ($admins as $admin) {
                $admin->notify(new SystemNotification(
                    '📥 UMKM Baru Masuk',
                    'Petugas "'.Auth::user()->name.'" mendaftarkan UMKM baru: "'.$umkm->nama_usaha.'" dan sudah otomatis terverifikasi.',
                    'info',
                    route('admin.verifikasi.show', $umkm->id_umkm)
                ));
            }
            
            $superAdmins = User::role('super_admin')->get();
            foreach ($superAdmins as $sa) {
                $sa->notify(new SystemNotification(
                    '📥 UMKM Baru Masuk',
                    'Petugas "'.Auth::user()->name.'" mendaftarkan UMKM baru: "'.$umkm->nama_usaha.'".',
                    'info',
                    route('superadmin.umkm.show', $umkm->id_umkm)
                ));
            }

            return redirect()->route('operator.umkm.index')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Data UMKM "'.$umkm->nama_usaha.'" berhasil disimpan dan otomatis terverifikasi.',
                ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function edit($id)
    {
        $umkm = UMKM::with(['pemilik', 'kategori', 'sektor'])->where('id_petugas', Auth::id())->findOrFail($id);

        // Block editing if UMKM is already verified
        if ($umkm->status_verifikasi === 'terverifikasi') {
            return redirect()->route('operator.umkm.index')->with('toast', [
                'type' => 'warning',
                'title' => 'Perhatian!',
                'message' => 'UMKM yang sudah terverifikasi tidak dapat diedit.',
            ]);
        }

        $pemiliks = Pemilik::all();
        $kategoris = KategoriUMKM::all();
        $sektors = SektorUmkm::all();
        $kelurahans = Kelurahan::with('rws.rts')->get();

        // Auto-sync kelurahan name <-> id_kelurahan agar dropdown selalu terisi
        if ($umkm->pemilik) {
            $this->umkmService->syncPemilikWilayah($umkm->pemilik);
            $umkm->load('pemilik');
        }

        return view('petugas.umkm.edit', compact('umkm', 'pemiliks', 'kategoris', 'sektors', 'kelurahans'));
    }

    public function update(Request $request, $id)
    {
        $umkm = UMKM::where('id_petugas', Auth::id())->findOrFail($id);

        // Block editing if UMKM is already verified
        if ($umkm->status_verifikasi === 'terverifikasi') {
            return redirect()->route('operator.umkm.index')->with('toast', [
                'type' => 'warning',
                'title' => 'Perhatian!',
                'message' => 'UMKM yang sudah terverifikasi tidak dapat diedit.',
            ]);
        }

        $request->validate([
            'nama_pemilik' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'kelurahan' => 'required|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'rt' => 'nullable|string|max:10',
            'alamat' => 'nullable|string',
            'nama_usaha' => 'required|string|max:255',
            'id_kategori' => 'nullable|exists:kategori_umkms,id',
            'id_sektor' => 'nullable|exists:sektor_umkms,id',
            'status_usaha' => 'nullable|in:aktif,non_aktif,tutup,pindah',
            'bentuk_jualan' => 'required|string|max:100',
            'perkiraan_omset' => 'nullable|string|max:50',
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
            'foto_utama' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'foto_gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $pemilik = $umkm->pemilik;
        if ($pemilik) {
            $this->umkmService->updatePemilikFromRequest($pemilik, $request);
        }

        if ($request->hasFile('foto_utama')) {
            if ($umkm->foto_utama && Storage::disk('public')->exists($umkm->foto_utama)) {
                Storage::disk('public')->delete($umkm->foto_utama);
            }
            $file = $request->file('foto_utama');
            $filename = time().'_'.Auth::id().'.'.$file->getClientOriginalExtension();
            $umkm->foto_utama = $file->storeAs('umkm/foto_utama', $filename, 'public');
        }

        if ($request->hasFile('foto_gallery')) {
            $existingGalleries = $umkm->foto_gallery ?: [];
            $galleryPaths = $existingGalleries;

            foreach ($request->file('foto_gallery') as $i => $file) {
                $filename = time().'_gallery_'.$i.'_'.Auth::id().'.'.$file->getClientOriginalExtension();
                $galleryPaths[] = $file->storeAs('umkm/gallery', $filename, 'public');
            }
            $umkm->foto_gallery = $galleryPaths;
        }

        $umkm->update([
            'nama_usaha' => $request->nama_usaha,
            'id_kategori' => $request->id_kategori,
            'id_sektor' => $request->id_sektor,
            'status_usaha' => $request->status_usaha ?? 'aktif',
            'bentuk_jualan' => $request->bentuk_jualan,
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

        return redirect()->route('operator.umkm.index')->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data UMKM "'.$umkm->nama_usaha.'" berhasil diperbarui.',
        ]);
    }

    public function uploadFoto($id)
    {
        $umkm = UMKM::with('pemilik')->where('id_petugas', Auth::id())->findOrFail($id);

        return view('petugas.umkm.upload-foto', compact('umkm'));
    }

    public function storeUploadFoto(Request $request, $id)
    {
        $umkm = UMKM::where('id_petugas', Auth::id())->findOrFail($id);

        $request->validate([
            'foto_utama' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'foto_gallery.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ], [
            'foto_utama.image' => 'File harus berupa gambar.',
            'foto_utama.mimes' => 'Format harus jpeg, jpg, png, atau webp.',
            'foto_utama.max' => 'Ukuran foto utama maksimal 5MB.',
            'foto_gallery.*.image' => 'Setiap file gallery harus berupa gambar.',
            'foto_gallery.*.max' => 'Ukuran setiap foto gallery maksimal 5MB.',
        ]);

        $updated = [];

        // Upload Foto Utama
        if ($request->hasFile('foto_utama')) {
            // Hapus foto lama kalau ada
            if ($umkm->foto_utama) {
                Storage::disk('public')->delete($umkm->foto_utama);
            }
            $file = $request->file('foto_utama');
            $filename = time().'_'.Auth::id().'.'.$file->getClientOriginalExtension();
            $updated['foto_utama'] = $file->storeAs('umkm/foto_utama', $filename, 'public');
        }

        // Upload Foto Gallery (append ke yang sudah ada)
        if ($request->hasFile('foto_gallery')) {
            $existing = $umkm->foto_gallery ?: [];
            foreach ($request->file('foto_gallery') as $i => $file) {
                $filename = time().'_gallery_'.$i.'_'.Auth::id().'.'.$file->getClientOriginalExtension();
                $existing[] = $file->storeAs('umkm/gallery', $filename, 'public');
            }
            $updated['foto_gallery'] = array_values($existing);
        }

        if (! empty($updated)) {
            $umkm->update($updated);
        }

        return redirect()->route('operator.umkm.upload-foto', $id)
            ->with('toast', [
                'type' => 'success',
                'title' => 'Foto Berhasil Diupload!',
                'message' => 'Foto UMKM "'.$umkm->nama_usaha.'" berhasil disimpan.',
            ]);
    }

    public function deleteFotoGallery(Request $request, $id, $index)
    {
        $umkm = UMKM::where('id_petugas', Auth::id())->findOrFail($id);
        $gallery = $umkm->foto_gallery ?: [];

        if (isset($gallery[$index])) {
            Storage::disk('public')->delete($gallery[$index]);
            array_splice($gallery, $index, 1);
            $umkm->update(['foto_gallery' => array_values($gallery)]);
        }

        return response()->json(['success' => true]);
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
