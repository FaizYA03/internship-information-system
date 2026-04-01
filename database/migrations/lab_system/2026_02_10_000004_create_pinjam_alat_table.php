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
        Schema::create('pinjam_alat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('inventaris_id')->constrained('inventaris')->onDelete('cascade');
            $table->integer('jumlah');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->time('jam_pinjam')->nullable();
            $table->time('jam_kembali')->nullable();
            $table->string('keperluan');
            $table->string('status')->default('pending'); // pending, approved, rejected, returned, late
            
            // Tracking approvals
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            $table->string('kondisi_awal')->nullable();
            $table->string('kondisi_akhir')->nullable();
            $table->text('catatan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjam_alat');
    }
};
