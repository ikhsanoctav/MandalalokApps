<?php

namespace App\Imports;

use App\Models\KategoriUMKM;
use App\Models\Kelurahan;
use App\Models\Pemilik;
use App\Models\SektorUmkm;
use App\Models\UMKM;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UmkmImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Skip jika nik kosong
        if (empty($row['nik']) || empty($row['nama_usaha'])) {
            return null;
        }

        try {
            return DB::transaction(function () use ($row) {
                // 1. Cari atau Buat Pemilik berdasarkan NIK (gunakan nik_hash karena NIK terenkripsi)
                $nikHash = hash('sha256', $row['nik']);
                $pemilik = Pemilik::where('nik_hash', $nikHash)->first();

                if (! $pemilik) {
                    $kelurahan = Kelurahan::where('nama_kelurahan', $row['kelurahan'] ?? '')->first();
                    $id_kelurahan = $kelurahan ? $kelurahan->id : null;

                    $pemilik = Pemilik::create([
                        'id_pemilik' => Str::uuid(),
                        'nik' => $row['nik'],
                        'nik_hash' => hash('sha256', $row['nik']),
                        'nama_lengkap' => $row['nama_pemilik'] ?? 'Tanpa Nama',
                        'jenis_kelamin' => $row['jenis_kelamin'] ?? 'L',
                        'no_hp' => $row['no_hp'] ?? null,
                        'kelurahan' => $row['kelurahan'] ?? '-',
                        'id_kelurahan' => $id_kelurahan,
                        'kecamatan' => $row['kecamatan'] ?? 'Soreang',
                        'kota_kab' => $row['kota_kab'] ?? 'Kab. Bandung',
                        'provinsi' => $row['provinsi'] ?? 'Jawa Barat',
                        'status_verifikasi_ktp' => 'pending',
                    ]);
                }

                // 2. Cari Sektor
                $sektor = SektorUmkm::where('nama_sektor', $row['sektor'])->first();
                $id_sektor = $sektor ? $sektor->id : 1; // Default 1

                // 3. Buat UMKM
                // Cek agar tidak duplikat nama usaha dari pemilik yg sama
                $existingUmkm = UMKM::where('id_pemilik', $pemilik->id_pemilik)
                    ->where('nama_usaha', $row['nama_usaha'])
                    ->first();

                if ($existingUmkm) {
                    return null; // Skip jika sudah ada
                }

                $noPendaftaran = 'UMKM-'.date('Ymd').'-'.strtoupper(Str::random(5));

                return new UMKM([
                    'id_umkm' => Str::uuid(),
                    'no_pendaftaran' => $noPendaftaran,
                    'nama_usaha' => $row['nama_usaha'],
                    'id_pemilik' => $pemilik->id_pemilik,
                    'id_sektor' => $id_sektor,
                    'bentuk_jualan' => $row['bentuk_jualan'] ?? null,
                    'perkiraan_omset' => $row['perkiraan_omset'] ?? null,
                    'status_usaha' => $row['status_usaha'] ?? 'aktif',
                    'tahun_berdiri' => $row['tahun_berdiri'] ?? null,
                    'alamat_usaha' => $row['alamat_usaha'] ?? null,
                    'jumlah_tenaga_kerja' => $row['jumlah_tenaga_kerja'] ?? 0,
                    'no_izin_usaha' => $row['no_izin_usaha'] ?? null,
                    'npwp_usaha' => $row['npwp_usaha'] ?? null,
                    'telp_usaha' => $row['telp_usaha'] ?? null,
                    'email_usaha' => $row['email_usaha'] ?? null,
                    'id_petugas' => auth()->user()?->id ?? null,
                    'tanggal_pendataan' => now()->toDateString(),
                    'status_verifikasi' => 'terverifikasi', // Auto verifikasi via import
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Gagal import baris UMKM: '.$e->getMessage());

            return null;
        }
    }
}
