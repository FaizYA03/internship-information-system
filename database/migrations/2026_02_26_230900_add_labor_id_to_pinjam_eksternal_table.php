<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLaborIdToPinjamEksternalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('ALTER TABLE pinjam_eksternal MODIFY inventaris_id bigint(20) unsigned NULL;');
        Schema::table('pinjam_eksternal', function (Blueprint $table) {
            $table->foreignId('labor_id')->after('inventaris_id')->nullable()->constrained('labor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pinjam_eksternal', function (Blueprint $table) {
            $table->dropForeign(['labor_id']);
            $table->dropColumn('labor_id');
            // Reverting inventaris_id to non-nullable might be tricky depending on existing data
            // $table->unsignedBigInteger('inventaris_id')->change();
        });
    }
}
