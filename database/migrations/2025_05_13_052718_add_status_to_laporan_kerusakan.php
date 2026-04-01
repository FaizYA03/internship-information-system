<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('laporan_kerusakan')) {
            if (!Schema::hasColumn('laporan_kerusakan', 'status')) {
                Schema::table('laporan_kerusakan', function (Blueprint $table) {
                    $table->enum('status', ['pending', 'process', 'completed', 'rejected'])->default('pending')->after('tanggal_laporan');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};