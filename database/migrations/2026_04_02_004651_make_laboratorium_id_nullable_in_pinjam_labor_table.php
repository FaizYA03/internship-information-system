<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeLaboratoriumIdNullableInPinjamLaborTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pinjam_labor', function (Blueprint $table) {
            $table->dropForeign(['laboratorium_id']);
            $table->unsignedBigInteger('laboratorium_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pinjam_labor', function (Blueprint $table) {
            $table->foreign('laboratorium_id')->references('id')->on('laboratorium')->onDelete('cascade');
            $table->unsignedBigInteger('laboratorium_id')->nullable(false)->change();
        });
    }
}
