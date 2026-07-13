<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Magang Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <h2>Data Magang Siswa</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>NISN</th>
                <th>Perusahaan</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Supervisor Mitra</th>
                <th>Guru Pembimbing</th>
                <th>Status</th>
                <th>Laporan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $item->nama }}<br><small>{{ $item->user->email ?? '-' }}</small></td>
                    <td>{{ optional(optional($item->user)->siswa)->nis ?? optional($item->user)->nis_nip ?? '-' }}</td>
                    <td>{{ optional($item->wakilPerusahaan)->nama_perusahaan ?? 'Mandiri' }}</td>
                    <td>{{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') : '-' }}</td>
                    <td>{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') : '-' }}</td>
                    <td>
                        @if($item->mitraSupervisor)
                            {{ $item->mitraSupervisor->nama_lengkap }}
                        @elseif($item->wakilPerusahaan)
                            {{ $item->wakilPerusahaan->nama }} (Default)
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ optional(optional($item->pembimbing)->guru)->nama ?? '-' }}</td>
                    <td>{{ $item->status }}</td>
                    <td class="text-center">{{ $item->laporans_count ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
