<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMitraSupervisorIdToMagangSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('magang_siswa', function (Blueprint $table) {
            $table->foreignId('mitra_supervisor_id')->nullable()->constrained('mitra_supervisors')->nullOnDelete();
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
            $table->dropForeign(['mitra_supervisor_id']);
            $table->dropColumn('mitra_supervisor_id');
        });
    }
}
