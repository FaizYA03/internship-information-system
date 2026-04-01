<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixInventarisLaborId extends Command
{
    protected $signature = 'inventaris:fix-labor-id';
    protected $description = 'Auto-mapping labor_id pada tabel inventaris berdasarkan kolom lokasi';

    public function handle()
    {
        $labs = DB::table('labor')->get(['id', 'nama_labor']);
        $inventaris = DB::table('inventaris')->whereNull('labor_id')->get(['id', 'lokasi', 'nama_inventaris']);

        $this->info("Ditemukan {$inventaris->count()} inventaris tanpa labor_id...");
        $this->newLine();

        $updated = 0;
        $notFound = [];

        foreach ($inventaris as $item) {
            $matched = null;

            foreach ($labs as $lab) {
                $lokasiLower = strtolower(trim($item->lokasi ?? ''));
                $namaLaborLower = strtolower(trim($lab->nama_labor));

                // Exact match
                if ($lokasiLower === $namaLaborLower) {
                    $matched = $lab;
                    break;
                }
                // Partial: nama_labor ada di dalam lokasi
                if (stripos($item->lokasi, $lab->nama_labor) !== false) {
                    $matched = $lab;
                    break;
                }
                // Partial: lokasi ada di dalam nama_labor
                if (strlen($lokasiLower) > 5 && stripos($lab->nama_labor, $item->lokasi) !== false) {
                    $matched = $lab;
                    break;
                }
            }

            if ($matched) {
                DB::table('inventaris')->where('id', $item->id)->update(['labor_id' => $matched->id]);
                $this->line("  <fg=green>✓</> #{$item->id} '{$item->nama_inventaris}' → Lab #{$matched->id} ({$matched->nama_labor})");
                $updated++;
            } else {
                $notFound[] = $item->lokasi;
                $this->line("  <fg=yellow>?</> #{$item->id} '{$item->nama_inventaris}' → lokasi: '{$item->lokasi}' (tidak cocok)");
            }
        }

        $this->newLine();
        $this->info("Selesai! Updated: {$updated} | Tidak cocok: " . count($notFound));

        if (!empty($notFound)) {
            $this->warn("Lokasi yang tidak cocok dengan nama laboratorium:");
            foreach (array_unique($notFound) as $loc) {
                $this->line("  - {$loc}");
            }
            $this->warn("Silakan update manual melalui halaman edit inventaris.");
        }

        return Command::SUCCESS;
    }
}
