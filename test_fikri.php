<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$guru = \App\Models\Guru::with(['jurusan', 'kelasWali', 'mapels', 'user'])->whereRaw("user_id IN (SELECT id FROM users WHERE nama LIKE '%fikri%')")->first();

if (!$guru) {
    // Try via full name match in user relation just in case
    $gurus = \App\Models\Guru::with(['jurusan', 'user'])->get();
    foreach($gurus as $g) {
        if(stripos($g->nama, 'fikri') !== false) {
            $guru = $g;
            break;
        }
    }
}

file_put_contents('test_fikri.json', json_encode([
    'guru_id' => $guru ? $guru->id : null,
    'nama' => $guru ? $guru->nama : null,
    'user_id' => $guru ? $guru->user_id : null,
    'jurusan_id' => $guru ? $guru->jurusan_id : null,
    'jurusan_relation' => $guru && $guru->jurusan ? $guru->jurusan->toArray() : null,
    'kelas_wali' => $guru && $guru->kelasWali ? $guru->kelasWali->toArray() : null,
    'mapels' => $guru && $guru->mapels ? $guru->mapels->toArray() : null,
    'all_guru_table_data' => $guru ? $guru->toArray() : null
], JSON_PRETTY_PRINT));
