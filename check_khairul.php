<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$siswa = \App\Models\Siswa::with('dataKelas.waliKelas')->where('nis', '1321544')->first();
if ($siswa) {
    echo "ID: {$siswa->id}, Kelas (String): {$siswa->kelas}, Kelas_ID: {$siswa->kelas_id}\n";
    if ($siswa->dataKelas) {
        echo "Data Kelas Name: {$siswa->dataKelas->nama_kelas}\n";
        if ($siswa->dataKelas->waliKelas) {
            echo "Wali Kelas: {$siswa->dataKelas->waliKelas->nama}\n";
        } else {
            echo "Wali Kelas NULL\n";
        }
    } else {
        echo "Data Kelas REL NULL\n";
        
        $k = \App\Models\Kelas::where('nama_kelas', 'like', '%X - Teknik%')->first();
        if ($k) {
            $siswa->kelas_id = $k->id;
            $siswa->save();
            echo "Assigned forcefully to X - Teknik Audio Video (ID: {$k->id})\n";
        } else {
            echo "Class X - Teknik Audio Video not found\n";
        }
    }
} else {
    echo "Siswa not found\n";
}
