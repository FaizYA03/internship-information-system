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
                <th width="15%">Pengembalian</th>
                <th width="12%">Status</th>
                <th width="15%">Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $index => $p)
                <tr>
                    <td style="text-align: center">{{ $index + 1 }}</td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->buku->judul ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                    <td>
                        @if(in_array($p->status, ['Dikembalikan', 'Terlambat']) && $p->tanggal_dikembalikan)
                            <b>{{ \Carbon\Carbon::parse($p->tanggal_dikembalikan)->format('d/m/Y') }}</b> (Aktual)<br>
                            <small>Target: {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }}</small>
                        @else
                            {{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') : '-' }} <small>(Target)</small>
                        @endif
                    </td>
                    <td>{{ $p->status }}</td>
                    <td>
                        @if($p->denda > 0)
                            <span style="color: #dc3545; font-weight: bold;">Rp {{ number_format($p->denda, 0, ',', '.') }}</span><br>
                            <small>{{ $p->denda_dibayar ? '(Lunas)' : '(Belum Dibayar)' }}</small>
                        @else
                            -
                        @endif
                    </td>
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
