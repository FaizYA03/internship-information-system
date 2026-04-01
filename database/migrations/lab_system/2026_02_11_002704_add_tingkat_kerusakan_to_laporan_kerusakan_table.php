<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTingkatKerusakanToLaporanKerusakanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->enum('tingkat_kerusakan', ['Ringan', 'Sedang', 'Berat'])->nullable()->after('deskripsi_kerusakan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->dropColumn('tingkat_kerusakan');
        });
    }
}
