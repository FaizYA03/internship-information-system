<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah kolom status dari ENUM ke VARCHAR agar lebih fleksibel
     * dan menstandarkan nilai yang ada.
     */
    public function up(): void
    {
        // Ubah kolom status ke string biasa agar tidak terbatas enum
        DB::statement("ALTER TABLE inventaris MODIFY COLUMN status VARCHAR(50) NULL DEFAULT 'Tersedia'");
        
        // Standarisasi nilai lama: 'tersedia' -> 'Tersedia', 'tidak_tersedia' -> 'Tidak Tersedia'
        DB::table('inventaris')->where('status', 'tersedia')->update(['status' => 'Tersedia']);
        DB::table('inventaris')->where('status', 'tidak_tersedia')->update(['status' => 'Tidak Tersedia']);
        DB::table('inventaris')->whereNull('status')->update(['status' => 'Tersedia']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert: bersihkan data yang tidak sesuai enum dulu
        DB::table('inventaris')->whereNotIn('status', ['Tersedia', 'Tidak Tersedia'])->update(['status' => 'Tersedia']);
        DB::statement("ALTER TABLE inventaris MODIFY COLUMN status ENUM('Tersedia','Tidak Tersedia') NOT NULL DEFAULT 'Tersedia'");
    }
};
