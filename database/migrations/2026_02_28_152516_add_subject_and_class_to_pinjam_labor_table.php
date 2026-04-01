<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubjectAndClassToPinjamLaborTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('pinjam_labor', function (Blueprint $table) {
            if (!Schema::hasColumn('pinjam_labor', 'kelas')) {
                $table->string('kelas')->nullable()->after('jam_kembali');
            }
            if (!Schema::hasColumn('pinjam_labor', 'mata_pelajaran')) {
                $table->string('mata_pelajaran')->nullable()->after('kelas');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjam_labor', function (Blueprint $table) {
            $table->dropColumn(['kelas', 'mata_pelajaran']);
        });
    }
}
