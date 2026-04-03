<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$gurus = \App\Models\Guru::with(['mapels.courses.kelas', 'kelasWali', 'jurusan'])->get();

$results = [];
foreach($gurus as $guru) {
    if (!$guru->nama) continue;
    
    $jurusans = [];
    
    // 1. Explicit Jurusan
    if ($guru->jurusan) {
        $jurusans[] = $guru->jurusan->nama_jurusan;
    }
    
    // 2. Wali Kelas Jurusan
    if ($guru->kelasWali && $guru->kelasWali->jurusan) {
        $jurusans[] = $guru->kelasWali->jurusan;
    }
    
    // 3. Taught Classes Jurusans
    if ($guru->mapels) {
        foreach($guru->mapels as $mapel) {
            if ($mapel->courses) {
                foreach($mapel->courses as $course) {
                    if ($course->kelas && $course->kelas->jurusan) {
                        $jurusans[] = $course->kelas->jurusan;
                    }
                }
            }
        }
    }
    
    $jurusans = array_unique($jurusans);
    
    $results[] = [
        'nama' => $guru->nama,
        'jurusans' => implode(',', $jurusans)
    ];
}

file_put_contents('test_guru_course_jurusans.json', json_encode($results, JSON_PRETTY_PRINT));
