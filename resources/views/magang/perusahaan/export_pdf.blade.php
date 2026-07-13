<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Perusahaan Mitra</title>
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
            padding: 8px;
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
    <h2>Data Perusahaan Mitra</h2>
    
    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="25%">Nama Perusahaan</th>
                <th width="40%">Alamat</th>
                <th width="15%">Kontak</th>
                <th width="15%">Pembimbing</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perusahaan as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $item->nama_perusahaan }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>{{ $item->no_perusahaan }}</td>
                    <td>{{ $item->nama }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; width: 100%; text-align: right;">
        <p>Padang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p style="margin-top: 50px;">Admin Magang</p>
    </div>
</body>
</html>
