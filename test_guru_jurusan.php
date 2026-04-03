<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$gurus = \App\Models\Guru::all();
$data = [];
foreach($gurus as $g) {
    if(!is_null($g->jurusan_id)) {
        $data[] = [
            'nama' => $g->nama,
            'jurusan_id' => $g->jurusan_id,
        ];
    }
}

file_put_contents('test_guru_jurusan.json', json_encode($data, JSON_PRETTY_PRINT));
