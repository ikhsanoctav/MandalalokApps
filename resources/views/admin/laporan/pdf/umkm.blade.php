<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data UMKM</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        @page {
            margin: 30px 40px;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 9pt;
            color: #000;
            background: #fff;
            line-height: 1.4;
        }

        /* ============ KOP SURAT ============ */
        .kop-surat {
            display: table;
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }

        .kop-logo-cell {
            display: table-cell;
            width: 70px;
            vertical-align: middle;
            text-align: center;
        }

        .kop-logo-cell img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .kop-text-cell {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
            padding: 0 10px;
        }

        .kop-instansi {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-unit {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .kop-alamat {
            font-size: 8pt;
            margin-top: 3px;
            color: #333;
        }

        .kop-kontak {
            font-size: 8pt;
            color: #333;
        }

        /* ============ JUDUL LAPORAN ============ */
        .laporan-title {
            text-align: center;
            margin-bottom: 12px;
        }

        .laporan-title h1 {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 4px;
        }

        .laporan-meta {
            font-size: 8.5pt;
            color: #444;
        }

        .laporan-meta table {
            margin: 0 auto;
            border: none;
        }

        .laporan-meta td {
            padding: 1px 6px;
            border: none;
            font-size: 8.5pt;
        }

        /* ============ RINGKASAN STATISTIK ============ */
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .stats-table th {
            border: 1px solid #000;
            background: #f1f5f9;
            padding: 6px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            vertical-align: middle;
        }

        .stats-table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 11pt;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        /* ============ TABEL DATA UTAMA ============ */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .data-table thead th {
            padding: 8px 4px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
            border: 1px solid #000;
            background: #f1f5f9;
        }

        .data-table tbody td {
            padding: 6px 4px;
            border: 1px solid #000;
            vertical-align: top;
            font-size: 8pt;
            color: #000;
        }

        /* Text alignment utility classes */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }

        /* ============ FOOTER ============ */
        .footer-section {
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .footer-meta {
            font-size: 7.5pt;
            color: #666;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            text-align: center;
        }

        .signature-row {
            display: table;
            width: 100%;
            margin-top: 24px;
        }

        .signature-cell {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
        }

        .signature-cell p {
            font-size: 8.5pt;
            margin-bottom: 48px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 8.5pt;
            text-decoration: underline;
            border-top: 1px solid #000;
            display: inline-block;
            padding-top: 3px;
            min-width: 120px;
        }

        /* ============ PAGE BREAK ============ */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    {{-- ===================== KOP SURAT ===================== --}}
    <div class="kop-surat">
        @if(!empty($kop['logo_path']))
            <div class="kop-logo-cell">
                <img src="{{ public_path('storage/' . $kop['logo_path']) }}" alt="Logo">
            </div>
        @else
            <div class="kop-logo-cell">
                <img src="{{ public_path('images/Logo_Mandalaloka.png') }}" alt="Logo">
            </div>
        @endif
        <div class="kop-text-cell">
            <div class="kop-instansi">{{ $kop['nama_instansi'] }}</div>
            <div class="kop-unit">{{ $kop['nama_unit'] }}</div>
            @if(!empty($kop['alamat']))
                <div class="kop-alamat">{{ $kop['alamat'] }}</div>
            @endif
            @if(!empty($kop['telepon']) || !empty($kop['email']))
                <div class="kop-kontak">
                    @if(!empty($kop['telepon']))Telepon: {{ $kop['telepon'] }}@endif
                    @if(!empty($kop['telepon']) && !empty($kop['email'])) | @endif
                    @if(!empty($kop['email']))Email: {{ $kop['email'] }}@endif
                    @if(!empty($kop['website'])) | {{ $kop['website'] }}@endif
                </div>
            @endif
        </div>
    </div>

    {{-- ===================== JUDUL LAPORAN ===================== --}}
    <div class="laporan-title">
        <h1>Laporan Data UMKM</h1>
        <div class="laporan-meta">
            <table>
                <tr>
                    <td>Tanggal Cetak</td>
                    <td>: {{ $tanggal_cetak }}</td>
                    <td style="padding-left:20px;">Total Data</td>
                    <td>: {{ number_format($umkms->count()) }} UMKM</td>
                </tr>
                @if(!empty($filters['periode_awal']) || !empty($filters['periode_akhir']))
                <tr>
                    <td>Periode</td>
                    <td>: {{ !empty($filters['periode_awal']) ? \Carbon\Carbon::parse($filters['periode_awal'])->format('d/m/Y') : '-' }}
                        s/d
                        {{ !empty($filters['periode_akhir']) ? \Carbon\Carbon::parse($filters['periode_akhir'])->format('d/m/Y') : '-' }}
                    </td>
                    @if(!empty($filters['status_verifikasi']))
                    <td style="padding-left:20px;">Status</td>
                    <td>: {{ ucfirst($filters['status_verifikasi']) }}</td>
                    @endif
                </tr>
                @endif
            </table>
        </div>
    </div>

    {{-- ===================== STATISTIK RINGKAS ===================== --}}
    <table class="stats-table">
        <thead>
            <tr>
                <th>Total UMKM</th>
                <th>Terverifikasi</th>
                <th>Total Tenaga Kerja</th>
                <th>Ditolak</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ number_format($umkms->count()) }}</td>
                <td>{{ $umkms->where('status_verifikasi','terverifikasi')->count() }}</td>
                <td>{{ number_format($umkms->sum('jumlah_tenaga_kerja')) }}</td>
                <td>{{ $umkms->where('status_verifikasi','ditolak')->count() }}</td>
            </tr>
        </tbody>
    </table>

    {{-- ===================== TABEL DATA UTAMA ===================== --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:3%">NO</th>
                @if(in_array('no_pendaftaran', $selectedColumns)) <th>NO. DAFTAR</th> @endif
                @if(in_array('nama_usaha', $selectedColumns)) <th>NAMA USAHA</th> @endif
                @if(in_array('pemilik', $selectedColumns)) <th>PEMILIK</th> @endif
                @if(in_array('kategori', $selectedColumns)) <th>KATEGORI</th> @endif
                @if(in_array('sektor', $selectedColumns)) <th>SEKTOR</th> @endif
                @if(in_array('status_usaha', $selectedColumns)) <th>STATUS USAHA</th> @endif
                @if(in_array('tahun_berdiri', $selectedColumns)) <th>THN BERDIRI</th> @endif
                @if(in_array('no_izin', $selectedColumns)) <th>NO. IZIN</th> @endif
                @if(in_array('jenis_izin', $selectedColumns)) <th>JENIS IZIN</th> @endif
                @if(in_array('npwp', $selectedColumns)) <th>NPWP</th> @endif
                @if(in_array('telepon', $selectedColumns)) <th>TELEPON</th> @endif
                @if(in_array('email', $selectedColumns)) <th>EMAIL</th> @endif
                @if(in_array('jumlah_tk', $selectedColumns)) <th>JML TK</th> @endif
                @if(in_array('alamat', $selectedColumns)) <th>ALAMAT</th> @endif
                @if(in_array('petugas', $selectedColumns)) <th>PETUGAS</th> @endif
                @if(in_array('tgl_pendataan', $selectedColumns)) <th>TGL DATA</th> @endif
                @if(in_array('status_verifikasi', $selectedColumns)) <th>VERIFIKASI</th> @endif
                @if(in_array('alasan_penolakan', $selectedColumns)) <th>ALASAN PENOLAKAN</th> @endif
            </tr>
        </thead>
        <tbody>
            @forelse($umkms as $i => $umkm)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                @if(in_array('no_pendaftaran', $selectedColumns)) <td>{{ $umkm->no_pendaftaran ?? '-' }}</td> @endif
                @if(in_array('nama_usaha', $selectedColumns)) <td><strong>{{ $umkm->nama_usaha ?? '-' }}</strong></td> @endif
                @if(in_array('pemilik', $selectedColumns)) <td>{{ $umkm->pemilik->nama_lengkap ?? '-' }}</td> @endif
                @if(in_array('kategori', $selectedColumns)) <td>{{ $umkm->kategori->nama_kategori ?? '-' }}</td> @endif
                @if(in_array('sektor', $selectedColumns)) <td>{{ $umkm->sektor->nama_sektor ?? '-' }}</td> @endif
                @if(in_array('status_usaha', $selectedColumns)) <td class="text-center">{{ ucfirst(str_replace('_', ' ', $umkm->status_usaha)) }}</td> @endif
                @if(in_array('tahun_berdiri', $selectedColumns)) <td class="text-center">{{ $umkm->tahun_berdiri ?? '-' }}</td> @endif
                @if(in_array('no_izin', $selectedColumns)) <td>{{ $umkm->no_izin_usaha ?? '-' }}</td> @endif
                @if(in_array('jenis_izin', $selectedColumns)) <td>{{ $umkm->jenis_izin ?? '-' }}</td> @endif
                @if(in_array('npwp', $selectedColumns)) <td>{{ $umkm->npwp_usaha ?? '-' }}</td> @endif
                @if(in_array('telepon', $selectedColumns)) <td>{{ $umkm->telp_usaha ?? '-' }}</td> @endif
                @if(in_array('email', $selectedColumns)) <td>{{ $umkm->email_usaha ?? '-' }}</td> @endif
                @if(in_array('jumlah_tk', $selectedColumns)) <td class="text-center">{{ $umkm->jumlah_tenaga_kerja ?? 0 }}</td> @endif
                @if(in_array('alamat', $selectedColumns)) <td>{{ $umkm->alamat_usaha ?? '-' }}</td> @endif
                @if(in_array('petugas', $selectedColumns)) <td>{{ $umkm->petugas->name ?? '-' }}</td> @endif
                @if(in_array('tgl_pendataan', $selectedColumns)) <td class="text-center">{{ $umkm->tanggal_pendataan ? $umkm->tanggal_pendataan->format('d/m/Y') : '-' }}</td> @endif
                @if(in_array('status_verifikasi', $selectedColumns)) <td class="text-center">{{ ucfirst($umkm->status_verifikasi) }}</td> @endif
                @if(in_array('alasan_penolakan', $selectedColumns)) <td>{{ $umkm->status_verifikasi === 'ditolak' ? ($umkm->catatan_penolakan ?? '-') : '-' }}</td> @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ count($selectedColumns) + 1 }}" style="text-align:center; padding: 20px; color:#000;">
                    Tidak ada data UMKM yang sesuai dengan filter yang diterapkan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ===================== TANDA TANGAN ===================== --}}
    <div class="footer-section">
        <div class="signature-row">
            <div class="signature-cell">
                {{-- Kosong --}}
            </div>
            <div class="signature-cell">
                {{-- Kosong --}}
            </div>
            <div class="signature-cell">
                <p>{{ $kop['nama_unit'] }},<br>{{ now()->format('d F Y') }}</p>
                <div class="signature-name">........................................</div>
                <div style="font-size:7.5pt; margin-top:2px;">Kepala / Pejabat Berwenang</div>
            </div>
        </div>

        <div class="footer-meta">
            Dokumen ini dicetak secara otomatis dari Sistem Informasi Mandalaloka pada {{ $tanggal_cetak }}.
            @if(!empty($kop['website']))
                | {{ $kop['website'] }}
            @endif
        </div>
    </div>

</body>
</html>
