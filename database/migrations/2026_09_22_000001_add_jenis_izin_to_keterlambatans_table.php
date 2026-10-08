<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambah kolom untuk mendukung 2 jenis formulir:
     * - 'masuk'  = Izin Memasuki Kelas / Mengikuti Pelajaran (siswa telat masuk kelas)
     * - 'keluar' = Izin Meninggalkan Kelas / Pelajaran
     *
     * Kolom jam_datang & menit_terlambat (punya jenis 'masuk' versi lama) TIDAK dihapus
     * supaya data lama tetap aman — tinggal tidak dipakai lagi untuk entri baru.
     */
    public function up(): void
    {
        Schema::table('keterlambatans', function (Blueprint $table) {
            $table->string('jenis', 10)->default('masuk')->after('nis'); // 'masuk' | 'keluar'
            $table->string('rombel')->nullable()->after('nama_siswa');
            $table->string('no_absen', 10)->nullable()->after('rombel');
            $table->string('jam_ke')->nullable()->after('jam_datang'); // contoh: "3" atau "3 s/d selesai"
        });
    }

    public function down(): void
    {
        Schema::table('keterlambatans', function (Blueprint $table) {
            $table->dropColumn(['jenis', 'rombel', 'no_absen', 'jam_ke']);
        });
    }
};
