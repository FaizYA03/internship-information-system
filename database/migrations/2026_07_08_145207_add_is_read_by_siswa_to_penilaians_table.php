<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsReadBySiswaToPenilaiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->boolean('is_read_by_siswa')->default(false)->after('nilai_akhir');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->dropColumn('is_read_by_siswa');
        });
    }
}
