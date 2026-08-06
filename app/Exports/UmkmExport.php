<?php

namespace App\Exports;

use App\Models\UMKM;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class UmkmExport implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithEvents
{
    protected $filters;
    protected $selectedColumns;
    protected $kop;

    public function __construct(array $filters = [], array $selectedColumns = [], array $kop = [])
    {
        $this->filters = $filters;
        $this->selectedColumns = $selectedColumns;
        $this->kop = $kop;
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                $colCount = count($this->selectedColumns);
                if ($colCount < 5) $colCount = 5; // Minimum merge width
                $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount);
                
                $namaInstansi = $this->kop['nama_instansi'] ?? '';
                $namaUnit = $this->kop['nama_unit'] ?? '';
                $alamat = $this->kop['alamat'] ?? '';
                $telepon = $this->kop['telepon'] ?? '';
                
                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->setCellValue('A1', strtoupper($namaInstansi));
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells("A2:{$lastColumn}2");
                $sheet->setCellValue('A2', strtoupper($namaUnit));
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells("A3:{$lastColumn}3");
                $sheet->setCellValue('A3', $alamat);
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells("A4:{$lastColumn}4");
                $sheet->setCellValue('A4', 'Telepon: ' . $telepon);
                $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells("A5:{$lastColumn}5");
                $sheet->setCellValue('A5', 'LAPORAN DATA UMKM');
                $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Style for table header
                $actualLastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($this->selectedColumns));
                $sheet->getStyle("A7:{$actualLastCol}7")->getFont()->setBold(true);
            },
        ];
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
