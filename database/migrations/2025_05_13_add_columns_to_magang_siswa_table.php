<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('magang_siswa')) {
            Schema::table('magang_siswa', function (Blueprint $table) {
                if (!Schema::hasColumn('magang_siswa', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('magang_siswa', 'opening_id')) {
                    $table->unsignedBigInteger('opening_id')->nullable()->after('perusahaan_id');
                }
                if (!Schema::hasColumn('magang_siswa', 'email')) {
                    $table->string('email')->nullable()->after('nama');
                }
                if (!Schema::hasColumn('magang_siswa', 'no_hp')) {
                    $table->string('no_hp')->nullable()->after('email');
                }
            });
        }
    }

    public function down()
    {
        Schema::table('magang_siswa', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'opening_id', 'email', 'no_hp']);
        });
    }
};