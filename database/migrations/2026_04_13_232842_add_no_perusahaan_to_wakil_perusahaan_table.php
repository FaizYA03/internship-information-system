<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoPerusahaanToWakilPerusahaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('wakil_perusahaan', function (Blueprint $table) {
        $table->string('no_perusahaan')->nullable();
    });
}

public function down()
{
    Schema::table('wakil_perusahaan', function (Blueprint $table) {
        $table->dropColumn('no_perusahaan');
    });
}
}
