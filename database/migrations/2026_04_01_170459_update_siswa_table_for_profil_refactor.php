<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSiswaTableForProfilRefactor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->enum('status_siswa', ['Aktif', 'Lulus', 'Nonaktif'])->default('Aktif');
            $table->integer('tahun_masuk')->nullable();
            $table->foreignId('wali_kelas_id')->nullable()->constrained('guru')->onDelete('set null');
            if (Schema::hasColumn('siswa', 'agama')) {
                $table->dropColumn('agama');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropForeign(['wali_kelas_id']);
            $table->dropColumn(['status_siswa', 'tahun_masuk', 'wali_kelas_id']);
            $table->string('agama', 50)->nullable();
        });
    }
}
