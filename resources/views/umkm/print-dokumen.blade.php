<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data UMKM - {{ $umkm->nama_usaha }}</title>
    <style>
        /* CSS Khusus Print Dokumen Resmi A4 */
        @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman:wght@400;700&family=Arial:wght@400;700&display=swap');

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
            font-size: 12pt;
            line-height: 1.5;
        }

        /* Ukuran Kertas A4 */
        @page {
            size: A4 portrait;
            margin: 2cm 2cm 2cm 2cm;
        }

        .document-container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            box-sizing: border-box;
        }

        /* KOP SURAT */
        .kop-surat-wrapper {
            border-bottom: 4px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
            width: 100%;
        }

        .kop-surat {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .kop-logo {
            width: 80px;
            height: auto;
            margin-right: 20px;
        }

        .kop-text {
            text-align: center;
        }

        .kop-text h1 {
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-text h2 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
        }

        .kop-text p {
            margin: 2px 0 0 0;
            font-size: 10pt;
            font-family: Arial, sans-serif;
        }

        /* JUDUL DOKUMEN */
        .doc-title {
            text-align: center;
            margin-bottom: 25px;
        }

        .doc-title h3 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .doc-title p {
            margin: 5px 0 0 0;
            font-size: 11pt;
            font-family: Arial, sans-serif;
        }

        /* TABEL DATA */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
            font-size: 11pt;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 8px 10px;
            vertical-align: top;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
            width: 35%;
        }

        .table-section {
            background-color: #e6e6e6 !important;
            text-align: center !important;
            font-weight: bold;
            font-size: 12pt;
        }

        /* TANDA TANGAN & FOOTER */
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: bottom;
        }

        .signature-box p {
            margin: 0 0 70px 0;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
        }

        .signature-nik {
            margin: 0;
            font-size: 10pt;
        }

        /* QR CODE */
        .qr-section {
            margin-top: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            page-break-inside: avoid;
        }

        .qr-box {
            width: 80px;
            height: 80px;
        }

        .qr-text {
            font-size: 9pt;
            font-family: Arial, sans-serif;
            color: #555;
        }

        /* HIDE BUTTONS ON PRINT */
        .no-print {
            text-align: center;
            margin: 20px 0;
        }
        
        .no-print button {
            padding: 10px 20px;
            font-size: 14pt;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: Arial, sans-serif;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff; }
            .document-container { box-shadow: none; max-width: none; width: 100%; }
        }

        /* SCREEN PREVIEW STYLING */
        @media screen {
            body { background: #f1f5f9; padding: 20px; }
            .document-container {
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                padding: 2cm;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <button onclick="window.print()">Cetak Dokumen Sekarang</button>
        <p style="font-size: 10pt; color: #666; margin-top: 10px;">Gunakan kertas ukuran A4 (Portrait) dan hilangkan opsi Header/Footer di pengaturan cetak browser Anda.</p>
    </div>

    <div class="document-container">
        
        <!-- KOP SURAT -->
        <div class="kop-surat-wrapper">
            <div class="kop-surat">
                @php
                    $kopLogo = \App\Models\Setting::get('kop_logo_path');
                @endphp
                @if(!empty($kopLogo))
                    <img src="{{ asset('storage/' . $kopLogo) }}" alt="Logo" class="kop-logo">
                @else
                    <img src="{{ asset('images/Logo_Mandalaloka.png') }}" alt="Logo" class="kop-logo">
                @endif
                <div class="kop-text">
                    <h1 style="font-family: 'Times New Roman', Times, serif;">PEMERINTAH KOTA BANDUNG</h1>
                    <h2 style="font-family: 'Times New Roman', Times, serif;">KECAMATAN MANDALAJATI</h2>
                    <p>Jl. Pasir Impun No. 33A Bandung Telp.02263730954, Fax 02263730954</p>
                    <p>e-mail : mandalajatiunik@gmail.com</p>
                </div>
            </div>
        </div>

        <!-- JUDUL -->
        <div class="doc-title">
            <h3>PROFIL DATA USAHA MIKRO, KECIL, DAN MENENGAH (UMKM)</h3>
            <p>Nomor Registrasi: {{ $umkm->no_pendaftaran ?? 'Belum Ada' }}</p>
        </div>

        <!-- TABEL DATA -->
        <table>
            <tr>
                <th colspan="2" class="table-section">A. DATA PEMILIK USAHA</th>
            </tr>
            <tr>
                <th>Nama Lengkap</th>
                <td>{{ $umkm->pemilik?->nama_lengkap ?? '-' }}</td>
            </tr>
            <tr>
                <th>Nomor Induk Kependudukan (NIK)</th>
                <td>{{ $umkm->pemilik?->nik ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tempat, Tanggal Lahir</th>
                <td>{{ $umkm->pemilik?->tempat_lahir ?? '-' }}, {{ $umkm->pemilik?->tanggal_lahir ? \Carbon\Carbon::parse($umkm->pemilik?->tanggal_lahir)->format('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td>{{ ($umkm->pemilik?->jenis_kelamin ?? '') == 'L' ? 'Laki-Laki' : (($umkm->pemilik?->jenis_kelamin ?? '') == 'P' ? 'Perempuan' : '-') }}</td>
            </tr>
            <tr>
                <th>Alamat Lengkap KTP</th>
                <td>
                    {{ $umkm->pemilik?->alamat ?? '-' }}<br>
                    RT {{ $umkm->pemilik?->rt ?? '-' }} / RW {{ $umkm->pemilik?->rw ?? '-' }},
                    Kelurahan {{ $umkm->pemilik?->kelurahan ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>Nomor Telepon / HP</th>
                <td>{{ $umkm->pemilik?->no_telepon ?? ($umkm->pemilik?->no_hp ?? '-') }}</td>
            </tr>

            <tr>
                <th colspan="2" class="table-section">B. DATA USAHA</th>
            </tr>
            <tr>
                <th>Nama Usaha</th>
                <td><strong>{{ $umkm->nama_usaha ?? '-' }}</strong></td>
            </tr>
            <tr>
                <th>Kategori Usaha</th>
                <td>{{ $umkm->kategori?->nama_kategori ?? '-' }}</td>
            </tr>
            <tr>
                <th>Sektor Usaha</th>
                <td>{{ $umkm->sektor?->nama_sektor ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tahun Berdiri</th>
                <td>{{ $umkm->tahun_berdiri ?? '-' }}</td>
            </tr>
            <tr>
                <th>Status Usaha</th>
                <td>{{ ucfirst($umkm->status_usaha ?? '-') }}</td>
            </tr>
            <tr>
                <th>Alamat Usaha (Lokasi)</th>
                <td>{{ $umkm->alamat_usaha ?? '-' }}</td>
            </tr>
            <tr>
                <th>Nomor Izin Usaha / NIB</th>
                <td>{{ $umkm->no_izin_usaha ?? '-' }} ({{ $umkm->jenis_izin ?? '-' }})</td>
            </tr>
            <tr>
                <th>NPWP Usaha</th>
                <td>{{ $umkm->npwp_usaha ?? '-' }}</td>
            </tr>

            <tr>
                <th colspan="2" class="table-section">C. DATA TAMBAHAN</th>
            </tr>
            <tr>
                <th>Jumlah Tenaga Kerja</th>
                <td>Total: {{ $umkm->jumlah_tenaga_kerja ?? 0 }} Orang (Laki-laki: {{ $umkm->tenaga_kerja_laki ?? 0 }}, Perempuan: {{ $umkm->tenaga_kerja_perempuan ?? 0 }})</td>
            </tr>
            <tr>
                <th>Deskripsi Singkat Usaha</th>
                <td>{{ $umkm->deskripsi ?? '-' }}</td>
            </tr>
            <tr>
                <th>Status Verifikasi Sistem</th>
                <td>{{ strtoupper($umkm->status_verifikasi ?? 'DRAFT') }} 
                    @if($umkm->status_verifikasi == 'terverifikasi' && $umkm->tanggal_verifikasi)
                        (Pada: {{ \Carbon\Carbon::parse($umkm->tanggal_verifikasi)->format('d/m/Y') }})
                    @endif
                </td>
            </tr>
        </table>

        <!-- TANDA TANGAN -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Petugas Pendata / Verifikator,</p>
                <p class="signature-name">{{ $umkm->petugas?->name ?? '.........................................' }}</p>
                <p class="signature-nik">ID: {{ $umkm->id_petugas ?? '..............' }}</p>
            </div>
            <div class="signature-box">
                <p>Bandung, {{ date('d F Y') }}<br>Pemilik Usaha,</p>
                <p class="signature-name">{{ $umkm->pemilik?->nama_lengkap ?? '.........................................' }}</p>
                <p class="signature-nik">NIK: {{ $umkm->pemilik?->nik ?? '..............' }}</p>
            </div>
        </div>

        <!-- QR CODE VERIFIKASI -->
        @if(!empty($umkm->no_pendaftaran))
        <div class="qr-section">
            <div class="qr-box">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->generate($umkm->no_pendaftaran) !!}
            </div>
            <div class="qr-text">
                <strong>Pindai QR Code</strong> ini menggunakan aplikasi Scanner untuk<br>
                memverifikasi keabsahan data UMKM langsung di sistem Mandalaloka.<br>
                *Dokumen ini dicetak secara sah melalui sistem pada {{ date('d-m-Y H:i:s') }}.
            </div>
        </div>
        @endif

    </div>

</body>
</html>
