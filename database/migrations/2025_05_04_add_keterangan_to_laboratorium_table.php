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
        if (Schema::hasTable('laboratorium')) {
            if (!Schema::hasColumn('laboratorium', 'keterangan')) {
                Schema::table('laboratorium', function (Blueprint $table) {
                    $table->string('keterangan')->nullable()->after('end');
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
        Schema::table('laboratorium', function (Blueprint $table) {
            $table->dropColumn('keterangan');
        });
    }
};