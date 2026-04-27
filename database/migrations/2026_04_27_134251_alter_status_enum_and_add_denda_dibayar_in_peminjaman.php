<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStatusEnumAndAddDendaDibayarInPeminjaman extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->boolean('denda_dibayar')->default(false)->after('denda');
        });
        
        // Alter ENUM safely for MySQL
        DB::statement("ALTER TABLE `peminjaman` CHANGE `status` `status` ENUM('Menunggu', 'Disetujui', 'Ditolak', 'Dikembalikan', 'Terlambat') DEFAULT 'Menunggu'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('denda_dibayar');
        });
        DB::statement("ALTER TABLE `peminjaman` CHANGE `status` `status` ENUM('Menunggu', 'Disetujui', 'Ditolak', 'Dikembalikan') DEFAULT 'Menunggu'");
    }
}
