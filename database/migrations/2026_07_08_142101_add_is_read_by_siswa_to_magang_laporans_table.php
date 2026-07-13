<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsReadBySiswaToMagangLaporansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('magang_laporans', function (Blueprint $table) {
            $table->boolean('is_read_by_siswa')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('magang_laporans', function (Blueprint $table) {
            $table->dropColumn('is_read_by_siswa');
        });
    }
}
