<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi (menambahkan kolom siswa_nis)
     */
    public function up(): void
    {
        Schema::table('orang_tuas', function (Blueprint $table) {
            // Tambahkan kolom siswa_nis, bukan foreign key
            $table->string('siswa_nis')->nullable()->after('id');
        });
    }

    /**
     * Balikkan migrasi (hapus kolom siswa_nis)
     */
    public function down(): void
    {
        Schema::table('orang_tuas', function (Blueprint $table) {
            $table->dropColumn('siswa_nis');
        });
    }
};
