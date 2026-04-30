<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RenameKodeKelasToIdKelasInKelasTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('kelas', 'kode_kelas') && !Schema::hasColumn('kelas', 'id_kelas')) {
            DB::statement('ALTER TABLE kelas CHANGE kode_kelas id_kelas VARCHAR(10) NULL');
        }
    }

    public function down()
    {
        if (Schema::hasColumn('kelas', 'id_kelas')) {
            DB::statement('ALTER TABLE kelas CHANGE id_kelas kode_kelas VARCHAR(10) NULL');
        }
    }
}
