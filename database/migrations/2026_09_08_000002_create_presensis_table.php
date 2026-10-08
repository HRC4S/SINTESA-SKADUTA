<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Detail presensi: 1 baris = 1 siswa pada 1 tanggal.
     * SATU tabel ini menampung presensi SELURUH siswa dari SEMUA kelas —
     * dibedakan lewat kolom rombel & tanggal, bukan dipisah per tabel per kelas.
     * Kolom rombel & tanggal sengaja diduplikasi (denormalisasi) dari
     * presensi_sesi supaya rekap bulanan/total bisa query cepat tanpa join.
     */
    public function up(): void
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('presensi_sesi_id')->constrained('presensi_sesi')->cascadeOnDelete();

            $table->string('nis');
            $table->foreign('nis')->references('nis')->on('siswas')->cascadeOnDelete();

            $table->string('rombel');   // snapshot kelas saat presensi diambil
            $table->date('tanggal');    // snapshot tanggal (sama dgn presensi_sesi.tanggal)

            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa'])->default('hadir');

            $table->string('bukti_path')->nullable();      // diisi siswa sendiri (sakit/izin)
            $table->timestamp('bukti_uploaded_at')->nullable();

            $table->text('keterangan')->nullable();

            $table->timestamps();

            // Satu siswa cuma boleh punya 1 catatan presensi per tanggal.
            $table->unique(['nis', 'tanggal']);

            // Index buat rekap per kelas per bulan & rekap total per siswa.
            $table->index(['rombel', 'tanggal']);
            $table->index(['nis', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
