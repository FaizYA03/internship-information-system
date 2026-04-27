<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekomendasiBukuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekomendasi_buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul_buku');
            $table->string('pengarang')->nullable();
            $table->string('penerbit')->nullable();
            $table->foreignId('mapel_id')->nullable()->constrained('mapels')->onDelete('set null');
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusans')->onDelete('set null');
            $table->enum('prioritas', ['High', 'Medium', 'Low'])->default('Medium');
            $table->text('alasan')->nullable();
            $table->enum('status', ['Draft', 'Diproses', 'Disetujui Kepala Sekolah', 'Ditolak', 'Tersedia'])->default('Draft');
            $table->foreignId('waka_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('pengadaan_id')->nullable()->constrained('pengadaans')->onDelete('set null');
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
        Schema::dropIfExists('rekomendasi_buku');
    }
}
