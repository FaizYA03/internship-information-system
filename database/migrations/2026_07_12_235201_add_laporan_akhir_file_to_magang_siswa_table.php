<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLaporanAkhirFileToMagangSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('magang_siswa', function (Blueprint $table) {
            $table->string('laporan_akhir_file')->nullable()->after('tugas_singkat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('magang_siswa', function (Blueprint $table) {
            $table->dropColumn('laporan_akhir_file');
        });
    }
}
