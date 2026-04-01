<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('magang_laporans')) {
            Schema::create('magang_laporans', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('magang_siswa_id');
                $table->string('judul');
                $table->text('deskripsi');
                $table->integer('minggu_ke');
                $table->date('tanggal_mulai');
                $table->date('tanggal_selesai');
                $table->enum('status', ['draft', 'submitted', 'reviewed', 'approved', 'rejected'])->default('draft');
                $table->text('komentar')->nullable();
                $table->timestamps();

                $table->foreign('magang_siswa_id')->references('id')->on('magang_siswa')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('magang_laporans');
    }
};