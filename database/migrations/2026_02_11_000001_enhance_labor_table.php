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
        Schema::table('labor', function (Blueprint $table) {
            if (!Schema::hasColumn('labor', 'jenis_labor')) {
                $table->enum('jenis_labor', ['Komputer', 'Kimia', 'Fisika', 'Bahasa', 'Multimedia', 'Lainnya'])
                    ->default('Lainnya')
                    ->after('nama_labor');
            }
            
            if (!Schema::hasColumn('labor', 'kapasitas')) {
                $table->integer('kapasitas')->default(30)->after('jenis_labor');
            }
            
            if (!Schema::hasColumn('labor', 'fasilitas')) {
                $table->text('fasilitas')->nullable()->after('deskripsi');
            }
            
            if (!Schema::hasColumn('labor', 'status_penggunaan')) {
                $table->enum('status_penggunaan', ['kosong', 'digunakan'])->default('kosong')->after('fasilitas');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('labor', function (Blueprint $table) {
            if (Schema::hasColumn('labor', 'jenis_labor')) {
                $table->dropColumn('jenis_labor');
            }
            if (Schema::hasColumn('labor', 'kapasitas')) {
                $table->dropColumn('kapasitas');
            }
            if (Schema::hasColumn('labor', 'fasilitas')) {
                $table->dropColumn('fasilitas');
            }
            if (Schema::hasColumn('labor', 'status_penggunaan')) {
                $table->dropColumn('status_penggunaan');
            }
        });
    }
};
