<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pinjam_eksternal', function (Blueprint $table) {
            $table->id();
            $table->string('nama_peminjam');
            $table->string('instansi')->nullable();
            $table->string('kontak');
            $table->foreignId('inventaris_id')->constrained('inventaris')->onDelete('cascade');
            $table->integer('jumlah');
            
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->string('keperluan');
            
            // Status workflow: pending -> recommended (kalab) -> approved (kepsek)
            // or rejected
            $table->string('status')->default('pending'); 
            
            // Approval chain tracking
            $table->foreignId('rekomendasi_kalab_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('rekomendasi_kalab_at')->nullable();
            
            $table->foreignId('approved_kepsek_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_kepsek_at')->nullable();
            
            $table->string('surat_permohonan')->nullable(); // Path to file
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjam_eksternal');
    }
};
