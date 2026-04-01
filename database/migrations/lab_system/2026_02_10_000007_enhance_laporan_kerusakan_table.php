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
        // Check if report damage table exists, modify it
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            // Add relation to user reporter if not exists
            if (!Schema::hasColumn('laporan_kerusakan', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('set null');
            }
            
            // Link to spesific inventory item
            if (!Schema::hasColumn('laporan_kerusakan', 'inventaris_id')) {
                $table->foreignId('inventaris_id')->nullable()->after('user_id')->constrained('inventaris')->onDelete('cascade');
            }
            
            // Add status tracking
            if (!Schema::hasColumn('laporan_kerusakan', 'status_perbaikan')) {
                 $table->string('status_perbaikan')->default('pending')->after('deskripsi_kerusakan'); 
                 // pending, repairing, completed, scrapped
            }
            
            if (!Schema::hasColumn('laporan_kerusakan', 'tindakan_perbaikan')) {
                $table->text('tindakan_perbaikan')->nullable();
            }
            
            if (!Schema::hasColumn('laporan_kerusakan', 'teknisi_id')) {
                $table->foreignId('teknisi_id')->nullable()->constrained('users')->onDelete('set null');
            }
            
            // Add photo evidence
            if (!Schema::hasColumn('laporan_kerusakan', 'foto_bukti')) {
                $table->string('foto_bukti')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['inventaris_id']);
            $table->dropForeign(['teknisi_id']);
            $table->dropColumn(['user_id', 'inventaris_id', 'status_perbaikan', 'tindakan_perbaikan', 'teknisi_id', 'foto_bukti']);
        });
    }
};
