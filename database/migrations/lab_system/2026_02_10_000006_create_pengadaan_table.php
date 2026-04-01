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
        Schema::create('pengadaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // Requester (usually Kalab)
            $table->string('nama_barang');
            $table->text('spesifikasi');
            $table->integer('jumlah');
            $table->decimal('estimasi_harga', 15, 2)->nullable();
            $table->string('link_referensi')->nullable();
            $table->string('urgensi')->default('sedang'); // rendah, sedang, tinggi
            
            $table->text('alasan');
            $table->string('status')->default('pending'); // pending, approved, rejected, ordered, received
            
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // Kepsek
            $table->timestamp('approved_at')->nullable();
            $table->text('catatan_approval')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengadaan');
    }
};
