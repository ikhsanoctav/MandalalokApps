<?php

namespace App\Exports;

use App\Models\UMKM;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UmkmExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;
    protected $selectedColumns;

    public function __construct(array $filters = [], array $selectedColumns = [])
    {
        $this->filters = $filters;
        $this->selectedColumns = $selectedColumns;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $query = UMKM::with(['pemilik', 'kategori', 'sektor', 'petugas']);

        if (!empty($this->filters['kategori'])) {
            $query->where('id_kategori', $this->filters['kategori']);
        }

        if (!empty($this->filters['status_verifikasi'])) {
            $query->where('status_verifikasi', $this->filters['status_verifikasi']);
        }

        if (!empty($this->filters['periode_awal']) && !empty($this->filters['periode_akhir'])) {
            $query->whereBetween('tanggal_pendataan', [$this->filters['periode_awal'], $this->filters['periode_akhir']]);
        } elseif (!empty($this->filters['periode_awal'])) {
            $query->where('tanggal_pendataan', '>=', $this->filters['periode_awal']);
        } elseif (!empty($this->filters['periode_akhir'])) {
            $query->where('tanggal_pendataan', '<=', $this->filters['periode_akhir']);
        }

        return $query->latest()->get();
    }

    public function map($umkm): array
    {
        $row = [];
        
        $colMap = [
            'no_pendaftaran' => $umkm->no_pendaftaran,
            'nama_usaha' => $umkm->nama_usaha,
            'pemilik' => $umkm->pemilik->nama_lengkap ?? '-',
            'kategori' => $umkm->kategori->nama_kategori ?? '-',
            'sektor' => $umkm->sektor->nama_sektor ?? '-',
            'status_usaha' => $umkm->status_usaha,
            'tahun_berdiri' => $umkm->tahun_berdiri,
            'no_izin' => $umkm->no_izin_usaha,
            'jenis_izin' => $umkm->jenis_izin,
            'npwp' => $umkm->npwp_usaha,
            'telepon' => $umkm->telp_usaha,
            'email' => $umkm->email_usaha,
            'jumlah_tk' => $umkm->jumlah_tenaga_kerja,
            'alamat' => $umkm->alamat_usaha,
            'petugas' => $umkm->petugas->name ?? '-',
            'tgl_pendataan' => $umkm->tanggal_pendataan ? $umkm->tanggal_pendataan->format('d-m-Y') : '-',
            'status_verifikasi' => $umkm->status_verifikasi,
            'alasan_penolakan' => $umkm->status_verifikasi === 'ditolak' ? ($umkm->catatan_penolakan ?? '-') : '-',
        ];

        foreach ($this->selectedColumns as $col) {
            if (array_key_exists($col, $colMap)) {
                $row[] = $colMap[$col];
            }
        }

        return $row;
    }

    public function headings(): array
    {
        $headers = [];

        $headerMap = [
            'no_pendaftaran' => 'No Pendaftaran',
            'nama_usaha' => 'Nama Usaha',
            'pemilik' => 'Pemilik',
            'kategori' => 'Kategori',
            'sektor' => 'Sektor',
            'status_usaha' => 'Status Usaha',
            'tahun_berdiri' => 'Tahun Berdiri',
            'no_izin' => 'No Izin Usaha',
            'jenis_izin' => 'Jenis Izin',
            'npwp' => 'NPWP Usaha',
            'telepon' => 'Telepon Usaha',
            'email' => 'Email Usaha',
            'jumlah_tk' => 'Jumlah Tenaga Kerja',
            'alamat' => 'Alamat Usaha',
            'petugas' => 'Petugas Pendata',
            'tgl_pendataan' => 'Tanggal Pendataan',
            'status_verifikasi' => 'Status Verifikasi',
            'alasan_penolakan' => 'Alasan Penolakan',
        ];

        foreach ($this->selectedColumns as $col) {
            if (array_key_exists($col, $headerMap)) {
                $headers[] = $headerMap[$col];
            }
        }

        return $headers;
    }
}
