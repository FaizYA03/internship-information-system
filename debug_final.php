<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\Lab\LaporanKerusakan;

$l = LaporanKerusakan::find(6);
echo "ID 6 Details:\n";
echo "status_perbaikan: '" . $l->status_perbaikan . "'\n";
echo "is_eskalasi: " . var_export($l->is_eskalasi, true) . " (raw type: " . gettype($l->is_eskalasi) . ")\n";
echo "eskalasi_status: '" . $l->eskalasi_status . "'\n";

$count = LaporanKerusakan::where('status_perbaikan', 'selesai')->count();
echo "Count with status_perbaikan=selesai: " . $count . "\n";

$count2 = LaporanKerusakan::where('status_perbaikan', 'selesai')
            ->where(function($q) {
                $q->where('is_eskalasi', false)
                  ->orWhere('eskalasi_status', 'disetujui');
            })->count();
echo "Count with Selesai Filters: " . $count2 . "\n";
