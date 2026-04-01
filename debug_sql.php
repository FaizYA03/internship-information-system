<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\Lab\LaporanKerusakan;

$query = LaporanKerusakan::where('status_perbaikan', 'selesai')
            ->where(function($q) {
                $q->where('is_eskalasi', 0)
                  ->orWhere('eskalasi_status', 'disetujui');
            });

echo "Final Selesai SQL: " . $query->toSql() . "\n";
echo "Bindings: " . json_encode($query->getBindings()) . "\n";
echo "Total Records Match: " . $query->count() . "\n";
echo "Wait, is IDs matching? " . json_encode($query->pluck('id')) . "\n";
