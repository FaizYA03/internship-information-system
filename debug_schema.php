<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$columns = DB::select("SHOW COLUMNS FROM laporan_kerusakan");
foreach($columns as $c) {
    echo $c->Field . " - " . $c->Type . "\n";
}
