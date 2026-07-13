<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detail Nilai Magang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
            text-transform: uppercase;
        }
        .student-info table {
            width: 100%;
            margin-bottom: 30px;
        }
        .student-info td {
            padding: 6px;
        }
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .grades-table th, .grades-table td {
            border: 1px solid #444;
            padding: 10px;
        }
        .grades-table th {
            background-color: #f5f5f5;
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .final-score {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .final-score table {
            width: 100%;
        }
        .final-score td {
            font-size: 16px;
        }
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }
        .signature {
            width: 30%;
            float: right;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 60px;
            padding-top: 5px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LEMBAR PENILAIAN AKHIR<br>PRAKTIK KERJA LAPANGAN (PKL)</div>
    </div>
    
    <div class="student-info">
        <table>
            <tr>
                <td width="25%">Nama Siswa</td>
                <td width="5%">:</td>
                <td width="70%"><strong>{{ Auth::user()->name }}</strong></td>
            </tr>
            <tr>
                <td>Tempat PKL (Mitra)</td>
                <td>:</td>
                <td>{{ $magangSiswa->wakilPerusahaan->nama_perusahaan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Waktu Pelaksanaan</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($magangSiswa->tanggal_mulai)->format('d M Y') }} s.d. {{ \Carbon\Carbon::parse($magangSiswa->tanggal_selesai)->format('d M Y') }}</td>
            </tr>
        </table>
    </div>

    <table class="grades-table">
        <thead>
            <tr>
                <th width="70%">Komponen Penilaian</th>
                <th width="30%" class="text-center">Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Nilai PKL (dari Mitra)</td>
                <td class="text-center">{{ $nilaiPKL }}</td>
            </tr>
            <tr>
                <td>Nilai Laporan (dari Guru Pembimbing)</td>
                <td class="text-center">{{ $nilaiLaporan }}</td>
            </tr>
        </tbody>
    </table>

    <div class="final-score">
        <table>
            <tr>
                <td width="70%"><strong>NILAI AKHIR</strong></td>
                <td width="30%" class="text-center"><strong>{{ $nilaiAkhir }}</strong></td>
            </tr>
            <tr>
                <td><strong>KETERANGAN</strong></td>
                <td class="text-center"><strong>{{ $keterangan }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="signature-section clearfix">
        <div class="signature">
            <p>Padang, {{ date('d M Y') }}</p>
            <p>Guru Pembimbing</p>
            <div class="signature-line">
                {{ $magangSiswa->pembimbing->guru->user->name ?? '.......................' }}
            </div>
        </div>
    </div>
</body>
</html>
