<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFotoAgamaJenisKelaminToGuruTable extends Migration
{
    public function up()
    {
        Schema::table('guru', function (Blueprint $table) {
            if (!Schema::hasColumn('guru', 'foto')) {
                $table->string('foto')->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('guru', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable()->after('foto');
            }
            if (!Schema::hasColumn('guru', 'agama')) {
                $table->string('agama', 50)->nullable()->after('jenis_kelamin');
            }
        });
    }

    public function down()
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->dropColumn(['foto', 'jenis_kelamin', 'agama']);
        });
    }
}
