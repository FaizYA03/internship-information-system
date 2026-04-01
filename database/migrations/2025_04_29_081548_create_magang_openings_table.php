<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMagangOpeningsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('magang_openings')) {
            Schema::create('magang_openings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('perusahaan_id')->constrained('perusahaan')->onDelete('cascade');
                $table->string('posisi');
                $table->text('deskripsi');
                $table->text('keahlian')->nullable();
                $table->integer('jumlah_posisi')->default(1);
                $table->date('tanggal_mulai');
                $table->date('tanggal_selesai');
                $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('magang_openings');
    }
}