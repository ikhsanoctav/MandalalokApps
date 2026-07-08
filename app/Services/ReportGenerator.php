<?php

namespace App\Services;

use App\Exports\UmkmExport;
use App\Models\UMKM;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ReportGenerator
{
    /**
     * Men-generate laporan berdasarkan tipe dan format.
     *
     * @param string $type
     * @param string $format
     * @param array $filters (optional)
     * @return mixed
     */
    public function generate(string $type, string $format, array $filters = [])
    {
        // Panggil metode spesifik berdasarkan tipe data
        return match ($type) {
            'umkm' => $this->generateUmkmReport($format, $filters),
            default => abort(404, 'Tipe laporan tidak ditemukan.'),
        };
    }

    /**
     * Logic khusus untuk menggenerate laporan Data UMKM
     */
    private function generateUmkmReport(string $format, array $filters)
    {
        $filename = 'Laporan_UMKM_' . date('Ymd_His');

        // Get selected columns or use default if none selected
        $selectedColumns = $filters['columns'] ?? [
            'no_pendaftaran', 'nama_usaha', 'pemilik', 'kategori', 
            'sektor', 'tahun_berdiri', 'no_izin', 'jumlah_tk', 'status_verifikasi'
        ];

        if ($format === 'excel') {
            return Excel::download(new UmkmExport($filters, $selectedColumns), "{$filename}.xlsx");
        }

        if ($format === 'csv') {
            return Excel::download(new UmkmExport($filters, $selectedColumns), "{$filename}.csv", \Maatwebsite\Excel\Excel::CSV);
        }

        if ($format === 'pdf') {
            // Ambil data untuk PDF.
            $query = UMKM::with(['pemilik', 'kategori', 'sektor', 'petugas']);
            
            if (!empty($filters['kategori'])) {
                $query->where('id_kategori', $filters['kategori']);
            }

            if (!empty($filters['status_verifikasi'])) {
                $query->where('status_verifikasi', $filters['status_verifikasi']);
            }

            if (!empty($filters['periode_awal']) && !empty($filters['periode_akhir'])) {
                $query->whereBetween('tanggal_pendataan', [$filters['periode_awal'], $filters['periode_akhir']]);
            } elseif (!empty($filters['periode_awal'])) {
                $query->where('tanggal_pendataan', '>=', $filters['periode_awal']);
            } elseif (!empty($filters['periode_akhir'])) {
                $query->where('tanggal_pendataan', '<=', $filters['periode_akhir']);
            }

            $umkms = $query->latest()->get();

            $kop = [
                'nama_instansi' => \App\Models\Setting::get('kop_nama_instansi', 'Pemerintah Kabupaten/Kota'),
                'nama_unit'     => \App\Models\Setting::get('kop_nama_unit', 'Dinas Koperasi dan UKM'),
                'alamat'        => \App\Models\Setting::get('kop_alamat', ''),
                'telepon'       => \App\Models\Setting::get('kop_telepon', ''),
                'email'         => \App\Models\Setting::get('kop_email', ''),
                'website'       => \App\Models\Setting::get('kop_website', ''),
                'logo_path'     => \App\Models\Setting::get('kop_logo_path', ''),
            ];

            $pdf = Pdf::loadView('admin.laporan.pdf.umkm', [
                'umkms' => $umkms,
                'kop' => $kop,
                'filters' => $filters,
                'selectedColumns' => $selectedColumns,
                'tanggal_cetak' => now()->format('d F Y H:i'),
            ])->setPaper('a4', 'landscape');

            return $pdf->download("{$filename}.pdf");
        }

        abort(400, 'Format file tidak didukung.');
    }
}
