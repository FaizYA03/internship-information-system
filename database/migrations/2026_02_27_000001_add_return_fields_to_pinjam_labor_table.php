<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pinjam_labor', function (Blueprint $table) {
            if (!Schema::hasColumn('pinjam_labor', 'tanggal_kembali')) {
                $table->date('tanggal_kembali')->nullable()->after('tanggal');
            }
            if (!Schema::hasColumn('pinjam_labor', 'jam_pinjam')) {
                $table->time('jam_pinjam')->nullable()->after('waktu');
            }
            if (!Schema::hasColumn('pinjam_labor', 'jam_kembali')) {
                $table->time('jam_kembali')->nullable()->after('jam_pinjam');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pinjam_labor', function (Blueprint $table) {
            $cols = ['tanggal_kembali', 'jam_pinjam', 'jam_kembali'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('pinjam_labor', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
