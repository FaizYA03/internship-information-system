<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFotoJenisKelaminAgamaToSiswaTable extends Migration
{
    public function up()
    {
        Schema::table('siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('siswa', 'foto')) {
                $table->string('foto')->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('siswa', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable()->after('foto');
            }
            if (!Schema::hasColumn('siswa', 'agama')) {
                $table->string('agama', 50)->nullable()->after('jenis_kelamin');
            }
        });
    }

    public function down()
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['foto', 'jenis_kelamin', 'agama']);
        });
    }
}
