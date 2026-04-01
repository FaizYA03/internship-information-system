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
        Schema::table('pinjam_labor', function (Blueprint $table) {
            if (!Schema::hasColumn('pinjam_labor', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('nama')->constrained('users')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('pinjam_labor', 'labor_id')) {
                $table->foreignId('labor_id')->nullable()->after('laboratorium_id')->constrained('labor')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('pinjam_labor', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending')->after('waktu');
            }
            
            if (!Schema::hasColumn('pinjam_labor', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('status')->constrained('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('pinjam_labor', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
            
            if (!Schema::hasColumn('pinjam_labor', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable()->after('approved_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjam_labor', function (Blueprint $table) {
            if (Schema::hasColumn('pinjam_labor', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('pinjam_labor', 'labor_id')) {
                $table->dropForeign(['labor_id']);
                $table->dropColumn('labor_id');
            }
            if (Schema::hasColumn('pinjam_labor', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('pinjam_labor', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            }
            if (Schema::hasColumn('pinjam_labor', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            if (Schema::hasColumn('pinjam_labor', 'alasan_penolakan')) {
                $table->dropColumn('alasan_penolakan');
            }
        });
    }
};
