<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssetFieldsToInventarisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('inventaris', function (Blueprint $table) {
            // Add asset tracking fields
            $table->string('kode_inventaris')->unique()->nullable()->after('id');
            $table->text('spesifikasi')->nullable()->after('deskripsi');
            $table->enum('sumber_dana', ['APBN', 'BOS', 'Hibah', 'Lainnya'])->nullable()->after('spesifikasi');
            $table->year('tahun_perolehan')->nullable()->after('sumber_dana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaris', function (Blueprint $table) {
            $table->dropColumn(['kode_inventaris', 'spesifikasi', 'sumber_dana', 'tahun_perolehan']);
        });
    }
}
