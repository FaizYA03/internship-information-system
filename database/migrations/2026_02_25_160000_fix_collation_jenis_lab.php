<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Fix MySQL collation mismatch antara:
 *  - labor.jenis_labor          (kemungkinan utf8mb4_general_ci)
 *  - jenis_laboratoria.nama     (utf8mb4_unicode_ci — Laravel default)
 *
 * Solusi: samakan keduanya ke utf8mb4_unicode_ci.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Fix kolom jenis_labor di tabel labor
        DB::statement(
            "ALTER TABLE `labor`
             MODIFY COLUMN `jenis_labor`
             VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Lainnya'"
        );

        // Fix kolom nama di tabel jenis_laboratoria (pastikan juga unicode_ci)
        DB::statement(
            "ALTER TABLE `jenis_laboratoria`
             MODIFY COLUMN `nama`
             VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL"
        );

        // Samakan kolom prefix_kode juga
        DB::statement(
            "ALTER TABLE `jenis_laboratoria`
             MODIFY COLUMN `prefix_kode`
             VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL"
        );
    }

    public function down(): void
    {
        // Kembalikan ke general_ci jika perlu rollback
        DB::statement(
            "ALTER TABLE `labor`
             MODIFY COLUMN `jenis_labor`
             VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Lainnya'"
        );
    }
};
