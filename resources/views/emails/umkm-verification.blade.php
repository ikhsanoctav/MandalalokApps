<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pemberitahuan Status Pengajuan UMKM</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.6;
            color: #000;
            max-width: 650px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            border-bottom: 3px solid #000;
            padding-bottom: 5px;
            margin-bottom: 2px;
            text-align: center;
        }
        .header-inner {
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-title {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
            line-height: 1.3;
            text-transform: uppercase;
        }
        .kop-contact {
            font-size: 13px;
            margin: 5px 0 0 0;
            font-family: Arial, sans-serif;
        }
        .content {
            padding: 20px 0;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .status-box {
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
        }
        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #34d399;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table th, .details-table td {
            padding: 10px;
            border: 1px solid #e5e7eb;
            text-align: left;
        }
        .details-table th {
            background-color: #f9fafb;
            width: 35%;
        }
        .footer {
            margin-top: 40px;
            font-size: 11px;
            color: #6b7280;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-inner">
            <h1 class="kop-title">PEMERINTAH KOTA BANDUNG<br>KECAMATAN MANDALAJATI</h1>
            <p class="kop-contact">Jl. Pasir Impun No. 33A Bandung Telp. 02263730954, Fax 02263730954<br>e-mail : mandalajatiunik@gmail.com</p>
        </div>
    </div>

    <div class="content">
        <p>Yth. Bapak/Ibu <strong>{{ $umkm->pemilik?->nama_lengkap ?? 'Pemilik UMKM' }}</strong>,</p>
        
        <p>Terima kasih telah berpartisipasi dalam pendaftaran dan pendataan UMKM di wilayah Kecamatan Mandalajati. Melalui email ini, kami menginformasikan status terbaru dari pengajuan data usaha Anda.</p>

        @if($status == 'terverifikasi')
            <div class="status-box status-approved">
                ✓ PENGAJUAN DISETUJUI / TERVERIFIKASI
            </div>
            <p>Selamat! Data usaha Anda telah melewati tahap verifikasi dan dinyatakan <strong>Lengkap/Disetujui</strong> oleh Admin Kecamatan. Anda kini resmi terdata dalam Sistem Informasi UMKM Mandalaloka.</p>
        @else
            <div class="status-box status-rejected">
                ✕ PENGAJUAN DITOLAK / BUTUH PERBAIKAN
            </div>
            <p>Mohon maaf, pengajuan data usaha Anda saat ini <strong>belum dapat kami setujui</strong> karena terdapat beberapa hal yang perlu diperbaiki dengan alasan sebagai berikut:</p>
            <div style="background-color: #fef2f2; padding: 15px; border-left: 4px solid #ef4444; margin-bottom: 20px; font-style: italic; color: #7f1d1d;">
                "{{ $catatan ?? 'Tidak ada catatan penolakan spesifik.' }}"
            </div>
            <p>Silakan masuk (login) kembali ke dashboard aplikasi Mandalaloka untuk memperbaiki data sesuai catatan di atas, kemudian lakukan pengajuan ulang.</p>
        @endif

        <h3 style="margin-top: 30px; border-bottom: 1px solid #eee; padding-bottom: 5px;">Rincian Data Usaha:</h3>
        <table class="details-table">
            <tr>
                <th>Nama Usaha</th>
                <td><strong>{{ $umkm->nama_usaha }}</strong></td>
            </tr>
            <tr>
                <th>Nomor Pendaftaran</th>
                <td style="font-family: monospace;">{{ $umkm->no_pendaftaran ?? '-' }}</td>
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
                <th>Alamat Lengkap Usaha</th>
                <td>{{ $umkm->alamat_usaha ?? '-' }}</td>
            </tr>
        </table>

        <p>Demikian surat pemberitahuan elektronik ini kami sampaikan agar dapat dipergunakan sebagaimana mestinya.</p>
        
        <br>
        <p>Hormat kami,<br><strong>Admin Pendataan UMKM<br>Kecamatan Mandalajati</strong></p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh Sistem Informasi Mandalaloka (Mandalajati Lokal Karya).<br>Mohon tidak membalas langsung ke alamat email sistem ini.</p>
    </div>
</body>
</html>
