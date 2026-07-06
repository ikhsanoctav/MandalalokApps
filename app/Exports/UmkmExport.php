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

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
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
        return [
            $umkm->no_pendaftaran,
            $umkm->nama_usaha,
            $umkm->pemilik->nama_lengkap ?? '-',
            $umkm->kategori->nama_kategori ?? '-',
            $umkm->sektor->nama_sektor ?? '-',
            $umkm->status_usaha,
            $umkm->tahun_berdiri,
            $umkm->no_izin_usaha,
            $umkm->jenis_izin,
            $umkm->npwp_usaha,
            $umkm->telp_usaha,
            $umkm->email_usaha,
            $umkm->jumlah_tenaga_kerja,
            $umkm->alamat_usaha,
            $umkm->petugas->name ?? '-',
            $umkm->tanggal_pendataan ? $umkm->tanggal_pendataan->format('d-m-Y') : '-',
            $umkm->status_verifikasi,
            $umkm->status_verifikasi === 'ditolak' ? ($umkm->catatan_penolakan ?? '-') : '-',
        ];
    }

    public function headings(): array
    {
        return [
            'No Pendaftaran',
            'Nama Usaha',
            'Pemilik',
            'Kategori',
            'Sektor',
            'Status Usaha',
            'Tahun Berdiri',
            'No Izin Usaha',
            'Jenis Izin',
            'NPWP Usaha',
            'Telepon Usaha',
            'Email Usaha',
            'Jumlah Tenaga Kerja',
            'Alamat Usaha',
            'Petugas Pendata',
            'Tanggal Pendataan',
            'Status Verifikasi',
            'Alasan Penolakan',
        ];
    }
}
