<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\UMKM;
use App\Models\VerifikasiLapangan;
use App\Services\AktivitasLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VerifikasiController extends Controller
{
    /**
     * Daftar UMKM terverifikasi di wilayah operator + status kunjungan terakhir.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $kelurahan = $user->kelurahan;
        $filter = $request->get('filter', 'semua'); // semua, belum, sudah, tindak_lanjut

        if (! $kelurahan || $kelurahan === '-') {
            return view('petugas.verifikasi.index', [
                'umkms' => collect(),
                'kelurahan' => null,
                'stats' => ['total' => 0, 'belum' => 0, 'sudah' => 0, 'tindak_lanjut' => 0],
                'filter' => $filter,
            ]);
        }

        $query = UMKM::with(['pemilik', 'kategori', 'sektor', 'latestVerifikasiLapangan'])
            ->whereIn('status_verifikasi', ['terkirim', 'terverifikasi'])
            ->whereHas('pemilik', function ($q) use ($kelurahan) {
                $q->where('kelurahan', $kelurahan);
            });

        // Jika bukan tab riwayat, sembunyikan UMKM yang sudah "Selesai & Sesuai"
        if ($filter !== 'riwayat') {
            $query->whereDoesntHave('latestVerifikasiLapangan', function($q) {
                $q->where('status_kunjungan', 'dikunjungi')
                  ->where('kondisi_usaha', 'sesuai');
            });
        }

        $allUmkms = (clone $query)->get();

        // Hitung statistik (gunakan query terpisah untuk mendapatkan total keseluruhan tanpa filter hide)
        $allStatsQuery = UMKM::with(['latestVerifikasiLapangan'])
            ->whereIn('status_verifikasi', ['terkirim', 'terverifikasi'])
            ->whereHas('pemilik', function ($q) use ($kelurahan) {
                $q->where('kelurahan', $kelurahan);
            })->get();

        $stats = [
            'total' => $allStatsQuery->count(),
            'belum' => $allStatsQuery->filter(fn ($u) => ! $u->latestVerifikasiLapangan)->count(),
            'sudah' => $allStatsQuery->filter(fn ($u) => $u->latestVerifikasiLapangan && $u->latestVerifikasiLapangan->status_kunjungan === 'dikunjungi')->count(),
            'tindak_lanjut' => $allStatsQuery->filter(fn ($u) => $u->latestVerifikasiLapangan && $u->latestVerifikasiLapangan->status_kunjungan === 'butuh_tindak_lanjut')->count(),
        ];

        // Terapkan filter list
        if ($filter === 'belum') {
            $umkms = $allUmkms->filter(fn ($u) => ! $u->latestVerifikasiLapangan);
        } elseif ($filter === 'sudah') {
            $umkms = $allUmkms->filter(fn ($u) => $u->latestVerifikasiLapangan && $u->latestVerifikasiLapangan->status_kunjungan === 'dikunjungi');
        } elseif ($filter === 'tindak_lanjut') {
            $umkms = $allUmkms->filter(fn ($u) => $u->latestVerifikasiLapangan && $u->latestVerifikasiLapangan->status_kunjungan === 'butuh_tindak_lanjut');
        } elseif ($filter === 'riwayat') {
            $umkms = $allUmkms->filter(fn ($u) => $u->latestVerifikasiLapangan && $u->latestVerifikasiLapangan->status_kunjungan === 'dikunjungi' && $u->latestVerifikasiLapangan->kondisi_usaha === 'sesuai');
        } else {
            $umkms = $allUmkms;
        }

        return view('petugas.verifikasi.index', compact('umkms', 'kelurahan', 'stats', 'filter'));
    }

    /**
     * Detail UMKM + riwayat kunjungan.
     */
    public function show($umkmId)
    {
        $umkm = UMKM::with([
            'pemilik',
            'kategori',
            'sektor',
            'verifikasiLapangan' => function ($q) {
                $q->with('petugas')->orderBy('tanggal_kunjungan', 'desc');
            },
        ])->findOrFail($umkmId);

        return view('petugas.verifikasi.show', compact('umkm'));
    }

    /**
     * Form catat kunjungan lapangan.
     */
    public function create($umkmId)
    {
        $umkm = UMKM::with(['pemilik', 'kategori'])->findOrFail($umkmId);

        return view('petugas.verifikasi.create', compact('umkm'));
    }

    /**
     * Simpan data kunjungan lapangan.
     */
    public function store(Request $request, $umkmId)
    {
        $umkm = UMKM::findOrFail($umkmId);

        $request->validate([
            'tanggal_kunjungan' => 'required|date|before_or_equal:today',
            'status_kunjungan' => 'required|in:dikunjungi,butuh_tindak_lanjut',
            'kondisi_usaha' => 'required|in:sesuai,tidak_sesuai,tutup_sementara,tidak_ditemukan',
            'catatan_kunjungan' => 'nullable|string|max:2000',
            'foto_kunjungan.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'latitude_kunjungan' => 'nullable|numeric',
            'longitude_kunjungan' => 'nullable|numeric',
        ], [
            'tanggal_kunjungan.required' => 'Tanggal kunjungan wajib diisi.',
            'tanggal_kunjungan.before_or_equal' => 'Tanggal kunjungan tidak boleh melebihi hari ini.',
            'status_kunjungan.required' => 'Status kunjungan wajib dipilih.',
            'kondisi_usaha.required' => 'Kondisi usaha wajib dipilih.',
            'foto_kunjungan.*.image' => 'File harus berupa gambar.',
            'foto_kunjungan.*.mimes' => 'Format foto harus jpeg, jpg, png, atau webp.',
            'foto_kunjungan.*.max' => 'Ukuran foto maksimal 5MB.',
        ]);

        // Upload foto kunjungan
        $fotoPaths = [];
        if ($request->hasFile('foto_kunjungan')) {
            foreach ($request->file('foto_kunjungan') as $i => $file) {
                $filename = time() . '_visit_' . $i . '_' . Auth::id() . '.' . $file->getClientOriginalExtension();
                $fotoPaths[] = $file->storeAs('verifikasi_lapangan', $filename, 'public');
            }
        }

        $verifikasi = VerifikasiLapangan::create([
            'umkm_id' => $umkmId,
            'petugas_id' => Auth::id(),
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'status_kunjungan' => $request->status_kunjungan,
            'kondisi_usaha' => $request->kondisi_usaha,
            'catatan_kunjungan' => $request->catatan_kunjungan,
            'foto_kunjungan' => ! empty($fotoPaths) ? $fotoPaths : null,
            'latitude_kunjungan' => $request->latitude_kunjungan,
            'longitude_kunjungan' => $request->longitude_kunjungan,
        ]);

        AktivitasLogger::log(
            'Operator Lapangan mencatat kunjungan ke UMKM ' . $umkm->nama_usaha . ' - Kondisi: ' . $request->kondisi_usaha,
            'kunjungan_lapangan',
            $umkmId
        );

        return redirect()->route('operator.verifikasi.show', $umkmId)->with('toast', [
            'type' => 'success',
            'title' => 'Kunjungan Dicatat!',
            'message' => 'Data kunjungan lapangan ke "' . $umkm->nama_usaha . '" berhasil disimpan.',
        ]);
    }
}
