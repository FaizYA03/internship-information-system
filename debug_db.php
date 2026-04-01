<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\Lab\LaporanKerusakan;

$laporans = LaporanKerusakan::all();
foreach($laporans as $l) {
    if (str_contains(strtolower($l->inventaris?->nama_inventaris ?? $l->nama_alat), 'cctv')) {
        echo "ID: " . $l->id . "\n";
        echo "Status Perbaikan: " . $l->status_perbaikan . "\n";
        echo "Is Eskalasi: " . ($l->is_eskalasi ? 'Yes' : 'No') . "\n";
        echo "Eskalasi Status: " . $l->eskalasi_status . "\n";
        echo "Status Legacy: " . $l->status . "\n";
    }
}
