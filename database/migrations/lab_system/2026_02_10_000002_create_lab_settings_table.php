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
        Schema::create('lab_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('lab_settings')->insert([
            ['key' => 'can_borrow_external', 'value' => 'false', 'description' => 'Apakah pihak luar boleh meminjam alat?'],
            ['key' => 'auto_approve_student', 'value' => 'false', 'description' => 'Otomatis setujui peminjaman siswa?'],
            ['key' => 'max_borrow_days', 'value' => '3', 'description' => 'Maksimal hari peminjaman'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_settings');
    }
};
