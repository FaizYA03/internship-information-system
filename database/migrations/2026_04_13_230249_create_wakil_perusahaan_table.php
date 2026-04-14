<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWakilPerusahaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('wakil_perusahaan', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->string('nama');
        $table->string('email')->unique();
        $table->string('nama_perusahaan');
        $table->text('alamat');
        $table->string('no_perusahaan')->nullable();
        $table->string('bukti_lampiran')->nullable();
        $table->string('password');
        $table->enum('status', ['Pending', 'Accepted', 'Rejected'])->default('Pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wakil_perusahaan');
    }
}
