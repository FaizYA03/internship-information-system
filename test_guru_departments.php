<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$departments = \App\Models\Guru::has('jurusan')
    ->with('jurusan')
    ->get()
    ->pluck('jurusan.nama_jurusan')
    ->unique()
    ->sort()
    ->values();

file_put_contents('test_guru_departments.json', json_encode($departments));
