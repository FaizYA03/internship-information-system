<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\Lab\LaporanKerusakan;

$l = LaporanKerusakan::find(6);
if ($l) {
    echo "Current Status: " . $l->status_perbaikan . "\n";
    $result = $l->update(['status_perbaikan' => 'selesai', 'eskalasi_status' => 'disetujui']);
    echo "Update Result: " . ($result ? "Success" : "Failed") . "\n";
    echo "New Status: " . $l->status_perbaikan . "\n";
    echo "New Eskalasi Status: " . $l->eskalasi_status . "\n";
} else {
    echo "Laporan ID 6 not found\n";
}
