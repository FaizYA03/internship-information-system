<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_laboratoria', function (Blueprint $table) {
            if (!Schema::hasColumn('jenis_laboratoria', 'ikon')) {
                $table->string('ikon')->default('bi-building')->after('deskripsi');
            }
            if (!Schema::hasColumn('jenis_laboratoria', 'warna')) {
                $table->string('warna')->default('secondary')->after('ikon');
            }
            if (!Schema::hasColumn('jenis_laboratoria', 'prefix_kode')) {
                $table->string('prefix_kode', 20)->nullable()->after('warna');
            }
        });

        // Seed data awal jika tabel kosong
        if (DB::table('jenis_laboratoria')->count() === 0) {
            DB::table('jenis_laboratoria')->insert([
                [
                    'nama'        => 'Komputer',
                    'deskripsi'   => 'Laboratorium teknologi komputer dan jaringan',
                    'ikon'        => 'bi-pc-display',
                    'warna'       => 'primary',
                    'prefix_kode' => 'LAB-KOM',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'nama'        => 'Kimia',
                    'deskripsi'   => 'Laboratorium ilmu kimia dan percobaan zat',
                    'ikon'        => 'bi-flask',
                    'warna'       => 'danger',
                    'prefix_kode' => 'LAB-KIM',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'nama'        => 'Fisika',
                    'deskripsi'   => 'Laboratorium ilmu fisika dan percobaan sains',
                    'ikon'        => 'bi-lightning',
                    'warna'       => 'warning',
                    'prefix_kode' => 'LAB-FIS',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'nama'        => 'Bahasa',
                    'deskripsi'   => 'Laboratorium pembelajaran bahasa dan linguistik',
                    'ikon'        => 'bi-translate',
                    'warna'       => 'success',
                    'prefix_kode' => 'LAB-BHS',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'nama'        => 'Multimedia',
                    'deskripsi'   => 'Laboratorium multimedia, desain, dan audio visual',
                    'ikon'        => 'bi-camera-video',
                    'warna'       => 'purple',
                    'prefix_kode' => 'LAB-MUL',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'nama'        => 'Lainnya',
                    'deskripsi'   => 'Jenis laboratorium lainnya',
                    'ikon'        => 'bi-building',
                    'warna'       => 'secondary',
                    'prefix_kode' => 'LAB-LAB',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('jenis_laboratoria', function (Blueprint $table) {
            $table->dropColumn(['ikon', 'warna', 'prefix_kode']);
        });
    }
};
