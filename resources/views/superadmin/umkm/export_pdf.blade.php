<!DOCTYPE html>
<html>

<head>
    <title>Laporan Data UMKM</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Laporan Data UMKM Mandalaloka</h2>
    <p>Tanggal: {{ date('d-m-Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Pendaftaran</th>
                <th>Nama Usaha</th>
                <th>Pemilik</th>
                <th>Kategori</th>
                <th>Sektor</th>

                <th>Status</th>
                <th>Tahun</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($umkms as $umkm)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $umkm->no_pendaftaran }}</td>
                    <td>{{ $umkm->nama_usaha }}</td>
                    <td>{{ $umkm->pemilik?->nama_lengkap ?? '-' }}</td>
                    <td>{{ $umkm->kategori?->nama_kategori ?? '-' }}</td>
                    <td>{{ $umkm->sektor?->nama_sektor ?? '-' }}</td>

                    <td>{{ ucfirst($umkm->status_usaha) }}</td>
                    <td>{{ $umkm->tahun_berdiri }}</td>
                    <td>{{ Str::limit($umkm->alamat_usaha, 30) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
