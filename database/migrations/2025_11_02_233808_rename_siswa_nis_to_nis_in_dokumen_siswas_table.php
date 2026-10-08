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
    Schema::table('dokumen_siswas', function (Blueprint $table) {
        if (Schema::hasColumn('dokumen_siswas', 'siswa_nis')) {
            $table->renameColumn('siswa_nis', 'nis');
        }
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('dokumen_siswas', function (Blueprint $table) {
        if (Schema::hasColumn('dokumen_siswas', 'nis')) {
            $table->renameColumn('nis', 'siswa_nis');
        }
    });
}

};
