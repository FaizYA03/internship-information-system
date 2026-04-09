<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laboratorium - {{ ucfirst($type) }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1, h2, h3 { color: #0f172a; }
        .header { text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 10px; margin-bottom: 20px; }
        .stats-table, .detail-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .stats-table th, .stats-table td, .detail-table th, .detail-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .stats-table th, .detail-table th { background-color: #f1f5f9; }
        .footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 50px; text-align: center; line-height: 35px; border-top: 1px solid #ccc; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Operasional Laboratorium</h2>
        <p>SMK Negeri 5 Padang<br>
        Tipe Laporan: {{ ucfirst($type) }} | Tanggal: {{ $date }}</p>
    </div>

    <h3>A. Ringkasan Kinerja (Key Performance Indicators)</h3>
    <table class="stats-table">
        <tr>
            <th>Total Laboratorium</th>
            <td>{{ $stats['total_lab'] }} Unit</td>
        </tr>
        <tr>
            <th>Total Inventaris (Alat & Bahan)</th>
            <td>{{ $stats['total_inventaris'] }} Item</td>
        </tr>
        <tr>
            <th>Alat Kondisi Rusak</th>
            <td>{{ $stats['jumlah_rusak'] }} Item</td>
        </tr>
        <tr>
            <th>Pengadaan Disetujui</th>
            <td>{{ $stats['pengadaan_approved'] }} Pengajuan</td>
        </tr>
        <tr>
            <th>Peminjaman Eksternal Disetujui</th>
            <td>{{ $stats['peminjaman_eksternal'] }} Peminjaman</td>
        </tr>
    </table>

    @if($type === 'akreditasi')
    <h3>B. Kesiapan Akreditasi</h3>
    <p>Data di atas digunakan sebagai bukti fisik ketersediaan dan pemeliharaan prasarana laboratorium untuk standar akreditasi sekolah menengah kejuruan. Fasilitas dipelihara secara berkala dan terdokumentasi dalam sistem.</p>
    @endif

    <div class="footer">
        Dicetak pada {{ now()->isoFormat('D MMMM Y HH:mm') }} &bull; Sistem Laboratorium SMKN 5
    </div>
</body>
</html>
