<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ubah kolom jenis_labor dari ENUM menjadi STRING
     * agar bisa diisi dari tabel jenis_laboratoria (dynamic CRUD)
     */
    public function up(): void
    {
        // Untuk MySQL: harus ubah tipe dulu dengan DB statement
        // karena doctrineDBAL tidak support enum modification dengan baik
        if (Schema::hasColumn('labor', 'jenis_labor')) {
            DB::statement("ALTER TABLE `labor` MODIFY COLUMN `jenis_labor` VARCHAR(100) DEFAULT 'Lainnya'");
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('labor', 'jenis_labor')) {
            DB::statement("ALTER TABLE `labor` MODIFY COLUMN `jenis_labor` ENUM('Komputer','Kimia','Fisika','Bahasa','Multimedia','Lainnya') DEFAULT 'Lainnya'");
        }
    }
};
