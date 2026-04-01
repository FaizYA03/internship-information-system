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
        Schema::table('inventaris', function (Blueprint $table) {
            $table->foreignId('labor_id')->nullable()->after('kategori')->constrained('labor')->onDelete('set null');
            $table->enum('jenis', ['Alat', 'Bahan'])->default('Alat')->after('nama_inventaris');
            // Kondisi sudah ada sebagai string, kita akan standarkan valuenya di level aplikasi/model
            
            // Tambahkan kolom untuk stock tracking bahan
            $table->integer('stok_minimum')->default(0)->after('jumlah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaris', function (Blueprint $table) {
            $table->dropForeign(['labor_id']);
            $table->dropColumn(['labor_id', 'jenis', 'stok_minimum']);
        });
    }
};
