<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pengadaan Buku</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h3 { text-align: center; margin-bottom: 5px; }
        p { text-align: center; margin-top: 0; color: #555; }
    </style>
</head>
<body>
    <h3>Laporan Data Pengadaan Buku</h3>
    <p>Perpustakaan SMK Negeri 5 Padang<br>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Pengadaan</th>
                <th>Status</th>
                <th>Vendor</th>
                <th>Tanggal Diterima</th>
                <th>Faktur No</th>
                <th>Total Estimasi</th>
                <th>Total Aktual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengadaans as $key => $p)
            <tr>
                <td style="text-align:center">{{ $key + 1 }}</td>
                <td>{{ $p->judul }}</td>
                <td>{{ $p->status }}</td>
                <td>{{ $p->vendor->nama ?? '-' }}</td>
                <td>{{ $p->tanggal_diterima ? \Carbon\Carbon::parse($p->tanggal_diterima)->format('d/m/Y') : '-' }}</td>
                <td>{{ $p->faktur_no ?? '-' }}</td>
                <td style="text-align:right">Rp {{ number_format($p->total_estimasi, 0, ',', '.') }}</td>
                <td style="text-align:right">Rp {{ number_format($p->total_aktual, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
