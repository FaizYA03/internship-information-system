<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEscalationToLaporanKerusakanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->boolean('is_eskalasi')->default(false)->after('tanggal_laporan');
            $table->enum('eskalasi_ke', ['kepala_lab', 'waka_akademik', 'kepala_sekolah'])->nullable()->after('is_eskalasi');
            $table->text('eskalasi_catatan')->nullable()->after('eskalasi_ke');
            $table->timestamp('eskalasi_tanggal')->nullable()->after('eskalasi_catatan');
            $table->enum('eskalasi_status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu')->after('eskalasi_tanggal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->dropColumn(['is_eskalasi', 'eskalasi_ke', 'eskalasi_catatan', 'eskalasi_tanggal', 'eskalasi_status']);
        });
    }
}
