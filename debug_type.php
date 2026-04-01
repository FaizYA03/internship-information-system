<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$col = DB::select("SHOW COLUMNS FROM laporan_kerusakan LIKE 'is_eskalasi'");
print_r($col);
