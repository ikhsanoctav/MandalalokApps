<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriUMKM;
use App\Models\Kelurahan;
use App\Models\UMKM;
use App\Models\User;
use App\Notifications\SystemNotification;
use App\Services\AktivitasLogger;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UmkmVerificationMail;

class VerifikasiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $kelurahan = $request->get('kelurahan');
        $kategori_id = $request->get('kategori');
        $perPage = $request->get('per_page', 10);

        // Admin Kecamatan only sees 'terkirim' (pending) UMKM for verification
        $query = UMKM::with(['pemilik', 'kategori', 'sektor'])->where('status_verifikasi', 'terkirim');

        if ($kelurahan) {
            $query->whereHas('pemilik', function ($q) use ($kelurahan) {
                $q->where('kelurahan', $kelurahan);
            });
        }

        if ($kategori_id) {
            $query->where('id_kategori', $kategori_id);
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
        $pendingCount = UMKM::where('status_verifikasi', 'terkirim')->count();

        return view('admin.verifikasi.index', compact('umkms', 'kelurahans', 'kategoris', 'pendingCount'));
    }

    public function show($id)
    {
        if (! $id) {
            return redirect()->route('admin.verifikasi.index')->with('toast', [
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

            return view('admin.verifikasi.show', compact('umkm'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.verifikasi.index')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Data UMKM tidak ditemukan.',
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.verifikasi.index')->with('toast', [
                'type' => 'error',
                'title' => 'Error!',
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ]);
        }
    }

    public function verify($id)
    {
        if (! $id) {
            return redirect()->route('admin.verifikasi.index')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'ID UMKM tidak ditemukan.',
            ]);
        }

        try {
            $umkm = UMKM::findOrFail($id);

            $incompleteFields = [];
            
            // Cek Data UMKM
            if (empty($umkm->nama_usaha)) $incompleteFields[] = 'Nama Usaha';
            if (empty($umkm->id_kategori)) $incompleteFields[] = 'Kategori Usaha';
            if (empty($umkm->id_sektor)) $incompleteFields[] = 'Sektor Usaha';
            if (empty($umkm->bentuk_jualan)) $incompleteFields[] = 'Bentuk Jualan';
            if (empty($umkm->alamat_usaha)) $incompleteFields[] = 'Alamat Usaha';
            if (empty($umkm->foto_utama)) $incompleteFields[] = 'Foto Utama';
            if (empty($umkm->tahun_berdiri)) $incompleteFields[] = 'Tahun Berdiri';
            
            // Cek Data Pemilik
            $pemilik = $umkm->pemilik;
            if (!$pemilik) {
                if (empty($umkm->dokumen_ktp) && !$umkm->id_petugas) $incompleteFields[] = 'Foto KTP / Dokumen Pemilik';
            } else {
                if (empty($pemilik->nik) && empty($pemilik->nik_hash)) $incompleteFields[] = 'NIK Pemilik';
                if (empty($pemilik->nama_lengkap)) $incompleteFields[] = 'Nama Pemilik';
                
                // Jika didaftarkan oleh operator/admin (id_petugas tidak null), beberapa field mungkin kosong karena form pendaftaran mereka tidak mewajibkannya.
                if (!$umkm->id_petugas) {
                    if (empty($pemilik->no_hp)) $incompleteFields[] = 'No HP Pemilik';
                    if (empty($pemilik->alamat)) $incompleteFields[] = 'Alamat Pemilik';
                    if (empty($pemilik->foto_ktp)) $incompleteFields[] = 'Foto KTP Pemilik';
                }
            }

            if (count($incompleteFields) > 0) {
                return redirect()->route('admin.verifikasi.index')->with('toast', [
                    'type' => 'error',
                    'title' => 'Verifikasi Ditolak!',
                    'message' => 'Data belum lengkap! Kolom yang masih kosong: ' . implode(', ', $incompleteFields) . '. Mohon lengkapi data terlebih dahulu.',
                ]);
            }

            $umkm->update([
                'status_verifikasi' => 'terverifikasi',
                'tanggal_verifikasi' => now(),
                'catatan_penolakan' => null,
            ]);

            AktivitasLogger::log(
                'Admin Kecamatan memverifikasi UMKM '.$umkm->nama_usaha,
                'verifikasi',
                $id
            );

            // Notifikasi ke Admin yang memverifikasi
            auth()->user()->notify(new SystemNotification(
                '✅ UMKM Diverifikasi',
                'Pengajuan UMKM "'.$umkm->nama_usaha.'" berhasil disetujui.',
                'success',
                route('admin.verifikasi.show', $umkm->id_umkm)
            ));

            // Notifikasi ke Petugas Lapangan yang mendaftarkan ATAU operator di kelurahan tersebut
            if ($umkm->id_petugas) {
                $petugas = User::find($umkm->id_petugas);
                if ($petugas) {
                    $petugas->notify(new SystemNotification(
                        '✅ UMKM Anda Diverifikasi!',
                        'UMKM "'.$umkm->nama_usaha.'" yang Anda daftarkan telah diverifikasi & disetujui oleh Admin Kecamatan.',
                        'success',
                        route('operator.umkm.show', $umkm->id_umkm)
                    ));
                }
            } else {
                $kelurahan_id = $umkm->pemilik?->id_kelurahan ?? null;
                $operators = User::role('operator_lapangan')->when($kelurahan_id, function($q) use ($kelurahan_id) {
                    $q->where('id_kelurahan', $kelurahan_id);
                })->get();
                foreach ($operators as $operator) {
                    $operator->notify(new SystemNotification(
                        '✅ UMKM Diverifikasi',
                        'UMKM "'.$umkm->nama_usaha.'" di wilayah Anda telah diverifikasi & disetujui oleh Admin Kecamatan.',
                        'success',
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
                        '✅ UMKM Anda Diverifikasi!',
                        'UMKM "'.$umkm->nama_usaha.'" yang Anda daftarkan telah diverifikasi & disetujui oleh Admin Kecamatan.',
                        'success',
                        route('pelaku.dashboard')
                    ));
                    
                    // Send Email Notification
                    if ($pelaku->email) {
                        Mail::to($pelaku->email)->send(new UmkmVerificationMail($umkm, 'terverifikasi'));
                    }
                }
            }

            // Notifikasi ke Super Admin
            $superAdmins = User::role('super_admin')->get();
            foreach ($superAdmins as $superAdmin) {
                $superAdmin->notify(new SystemNotification(
                    '✅ UMKM Diverifikasi',
                    'UMKM "'.$umkm->nama_usaha.'" telah diverifikasi oleh Admin Kecamatan.',
                    'success',
                    route('superadmin.umkm.show', $umkm->id_umkm)
                ));
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'UMKM "'.$umkm->nama_usaha.'" berhasil diverifikasi & disetujui.',
                ]);
            }

            session()->flash('toast', [
                'type' => 'success',
                'title' => 'Verifikasi Berhasil!',
                'message' => 'UMKM "'.$umkm->nama_usaha.'" telah disetujui. Petugas sudah diberitahu.',
            ]);

            return redirect()->route('admin.verifikasi.index');

        } catch (\Exception $e) {
            return redirect()->route('admin.verifikasi.index')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ]);
        }
    }

    public function reject($id, Request $request)
    {
        if (! $id) {
            return redirect()->route('admin.verifikasi.index')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'ID UMKM tidak ditemukan.',
            ]);
        }

        try {
            $umkm = UMKM::findOrFail($id);

            $reason = $request->input('reason');
            if (! $reason) {
                return redirect()->back()->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Alasan penolakan harus diisi.',
                ]);
            }

            $umkm->update([
                'status_verifikasi' => 'ditolak',
                'tanggal_verifikasi' => null,
                'catatan_penolakan' => $reason,
            ]);

            AktivitasLogger::log(
                'Admin Kecamatan menolak UMKM '.$umkm->nama_usaha.' dengan alasan: '.$reason,
                'penolakan',
                $id
            );

            // Notifikasi ke Admin yang menolak
            auth()->user()->notify(new SystemNotification(
                '❌ UMKM Ditolak',
                'Pengajuan UMKM "'.$umkm->nama_usaha.'" telah ditolak.',
                'warning',
                route('admin.verifikasi.show', $umkm->id_umkm)
            ));

            // Notifikasi ke Petugas Lapangan yang mendaftarkan
            if ($umkm->id_petugas) {
                $petugas = User::find($umkm->id_petugas);
                if ($petugas) {
                    $petugas->notify(new SystemNotification(
                        '❌ UMKM Anda Ditolak',
                        'UMKM "'.$umkm->nama_usaha.'" yang Anda daftarkan ditolak oleh Admin Kecamatan. Alasan: '.$reason,
                        'danger',
                        route('operator.umkm.show', $umkm->id_umkm)
                    ));
                }
            } else {
                $kelurahan_id = $umkm->pemilik?->id_kelurahan ?? null;
                $operators = User::role('operator_lapangan')->when($kelurahan_id, function($q) use ($kelurahan_id) {
                    $q->where('id_kelurahan', $kelurahan_id);
                })->get();
                foreach ($operators as $operator) {
                    $operator->notify(new SystemNotification(
                        '❌ UMKM Ditolak',
                        'UMKM "'.$umkm->nama_usaha.'" di wilayah Anda ditolak oleh Admin Kecamatan. Alasan: '.$reason,
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
                        '❌ UMKM Anda Ditolak',
                        'UMKM "'.$umkm->nama_usaha.'" yang Anda daftarkan ditolak oleh Admin Kecamatan. Alasan: '.$reason,
                        'danger',
                        route('pelaku.dashboard')
                    ));
                    
                    // Send Email Notification
                    if ($pelaku->email) {
                        Mail::to($pelaku->email)->send(new UmkmVerificationMail($umkm, 'ditolak', $reason));
                    }
                }
            }

            // Notifikasi ke Super Admin
            $superAdmins = User::role('super_admin')->get();
            foreach ($superAdmins as $superAdmin) {
                $superAdmin->notify(new SystemNotification(
                    '❌ UMKM Ditolak',
                    'UMKM "'.$umkm->nama_usaha.'" telah ditolak oleh Admin Kecamatan dengan alasan: '.$reason,
                    'warning',
                    route('superadmin.umkm.show', $umkm->id_umkm)
                ));
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'UMKM "'.$umkm->nama_usaha.'" telah ditolak.',
                ]);
            }

            session()->flash('toast', [
                'type' => 'warning',
                'title' => 'Penolakan Berhasil!',
                'message' => 'UMKM "'.$umkm->nama_usaha.'" ditolak. Petugas sudah diberitahu.',
            ]);

            return redirect()->route('admin.verifikasi.index');

        } catch (\Exception $e) {
            return redirect()->route('admin.verifikasi.index')->with('toast', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ]);
        }
    }
}
