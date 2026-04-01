<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('magang_siswa')) {
            if (!Schema::hasColumn('magang_siswa', 'opening_id')) {
                Schema::table('magang_siswa', function (Blueprint $table) {
                    $table->unsignedBigInteger('opening_id')->nullable()->after('perusahaan_id');
                    $table->foreign('opening_id')
                          ->references('id')
                          ->on('magang_openings')
                          ->onDelete('set null');
                });
            }
        }
    }

    public function down()
    {
        Schema::table('magang_siswa', function (Blueprint $table) {
            $table->dropForeign(['opening_id']);
            $table->dropColumn('opening_id');
        });
    }
};