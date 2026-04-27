<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTugasSingkatToMagangSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('magang_siswa', function (Blueprint $table) {
            $table->text('tugas_singkat')->nullable()->after('catatan');
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
            $table->dropColumn('tugas_singkat');
        });
    }
}
