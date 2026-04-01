<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFotoToLaborTableIfNotExists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('labor', function (Blueprint $table) {
            // Add foto column if it doesn't exist
            if (!Schema::hasColumn('labor', 'foto')) {
                $table->string('foto')->nullable()->after('fasilitas');
            }
            
            // Add lokasi column if it doesn't exist  
            if (!Schema::hasColumn('labor', 'lokasi')) {
                $table->string('lokasi')->nullable()->after('foto');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('labor', function (Blueprint $table) {
            if (Schema::hasColumn('labor', 'foto')) {
                $table->dropColumn('foto');
            }
            if (Schema::hasColumn('labor', 'lokasi')) {
                $table->dropColumn('lokasi');
            }
        });
    }
}
