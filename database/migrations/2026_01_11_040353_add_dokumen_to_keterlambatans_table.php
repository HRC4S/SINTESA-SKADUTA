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
        Schema::table('keterlambatans', function (Blueprint $table) {
            // Menambahkan kolom 'dokumen' setelah kolom 'keterangan'
            // nullable() artinya boleh kosong (jika siswa tidak upload foto)
            $table->string('dokumen')->nullable()->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keterlambatans', function (Blueprint $table) {
            // Menghapus kolom jika di-rollback
            $table->dropColumn('dokumen');
        });
    }
};