<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ubah kolom role menjadi string jika sebelumnya enum, agar fleksibel
            // Kita gunakan change() jika didukung, atau raw statement
        });

        // Karena mengubah enum ke string bisa tricky di beberapa database driver/version tanpa doctrine/dbal,
        // kita gunakan raw SQL untuk amannya di MySQL.
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum basic jika rollback (opsional, tapi sebaiknya jangan karena data bisa hilang)
        // DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'guru', 'siswa') NOT NULL");
    }
};
