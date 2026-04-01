<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalLaboratoriumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('jadwal_laboratorium', function (Blueprint $table) {
            $table->id();
            $table->foreignId('labor_id')->constrained('labor')->onDelete('cascade');
            $table->string('mata_pelajaran');
            $table->foreignId('guru_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('kelas')->nullable();
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_laboratorium');
    }
}
