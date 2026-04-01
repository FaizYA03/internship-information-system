<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('laporan_kerusakan')) {
            Schema::table('laporan_kerusakan', function (Blueprint $table) {
                if (!Schema::hasColumn('laporan_kerusakan', 'status')) {
                    $table->enum('status', ['pending', 'process', 'completed', 'rejected'])->default('pending')->after('tanggal_laporan');
                }
                if (!Schema::hasColumn('laporan_kerusakan', 'tanggapan')) {
                    $table->text('tanggapan')->nullable()->after('status');
                }
            });
        }
    }

    public function down()
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->dropColumn(['status', 'tanggapan']);
        });
    }
};