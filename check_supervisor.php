<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check if there's a mitra_supervisor_id that is 0 or empty string instead of NULL
echo "=== RAW SQL CHECK ===\n";
$raw = \Illuminate\Support\Facades\DB::select("SELECT id, nama, mitra_supervisor_id, perusahaan_id FROM magang_siswa");
foreach($raw as $r) {
    $val = $r->mitra_supervisor_id;
    $type = gettype($val);
    echo "ID={$r->id} | nama={$r->nama} | supervisor_id=[{$val}] (type:{$type}) | perusahaan_id={$r->perusahaan_id}" . PHP_EOL;
}

echo "\n=== Check updated_at timestamps ===\n";
$items = \App\Models\MagangSiswa::select('id', 'nama', 'mitra_supervisor_id', 'updated_at')->orderBy('updated_at', 'desc')->get();
foreach($items as $it) {
    echo "ID={$it->id} | {$it->nama} | spv_id=" . ($it->mitra_supervisor_id ?? 'NULL') . " | updated_at={$it->updated_at}" . PHP_EOL;
}
