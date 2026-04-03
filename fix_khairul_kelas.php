<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$siswa = \App\Models\Siswa::where('nis', '1321544')->first();
$kelas_id = $siswa->kelas_id;
$k = \App\Models\Kelas::find($kelas_id);

echo "Fixing Kelas ID: {$kelas_id} (Currently: {$k->nama_kelas})\n";

// forcefully rename and set wali kelas
$k->nama_kelas = 'X - Teknik Audio Video';
$k->jurusan = 'Teknik Audio Video';
$guru = \App\Models\User::where('nama', 'like', '%fikri%s%pd%')->first();
if (!$guru) {
    $guru = \App\Models\User::where('nama', 'like', '%fikri%')->where('role', 'guru')->first();
}
if ($guru) {
    $k->wali_kelas_id = $guru->id;
    echo "Set wali kelas to {$guru->nama}\n";
}
$k->save();
echo "Done.\n";
