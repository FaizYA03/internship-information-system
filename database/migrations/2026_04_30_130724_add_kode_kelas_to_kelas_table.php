<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddKodeKelasToKelasTable extends Migration
{
    public function up()
    {
        Schema::table('kelas', function (Blueprint $table) {
            if (!Schema::hasColumn('kelas', 'kode_kelas')) {
                // Unique short code like "01", "02", ...
                $table->string('kode_kelas', 10)->nullable()->unique()->after('nama_kelas');
            }
        });

        // Auto-assign kode_kelas to existing records ordered by nama_kelas
        $rows = DB::table('kelas')->orderBy('nama_kelas')->orderBy('id')->get();
        foreach ($rows as $i => $row) {
            $kode = str_pad($i + 1, 2, '0', STR_PAD_LEFT); // "01","02",...
            DB::table('kelas')->where('id', $row->id)->update(['kode_kelas' => $kode]);
        }
    }

    public function down()
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('kode_kelas');
        });
    }
}
