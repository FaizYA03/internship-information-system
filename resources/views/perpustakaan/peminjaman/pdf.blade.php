<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
            font-size: 16px;
        }
        .header p {
            margin: 5px 0 0;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Data Peminjaman Buku</h2>
        <p>Perpustakaan SMK Negeri 5 Padang</p>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama</th>
                <th>Buku</th>
                <th width="15%">Tgl Pinjam</th>
                <th width="15%">Tgl Kembali</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $index => $p)
                <tr>
                    <td style="text-align: center">{{ $index + 1 }}</td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->buku->judul ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                    <td>{{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $p->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center">Tidak ada data peminjaman</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
