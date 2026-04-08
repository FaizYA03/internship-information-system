<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_laboratorium', function (Blueprint $table) {
            $table->enum('status_validasi', ['draft', 'menunggu', 'disetujui', 'ditolak'])
                  ->default('draft')
                  ->after('keterangan');
            $table->text('catatan_validasi')->nullable()->after('status_validasi');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_laboratorium', function (Blueprint $table) {
            $table->dropColumn(['status_validasi', 'catatan_validasi']);
        });
    }
};
