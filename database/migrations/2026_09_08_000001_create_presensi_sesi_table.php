<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Header presensi: 1 baris = 1 kelas pada 1 tanggal.
     * Keberadaan baris ini menandakan koor kelas SUDAH input presensi
     * untuk kelas+tanggal tsb (dipakai sebagai kunci agar koor tidak
     * bisa input dobel). Admin/Kesiswaan tidak terikat kunci ini.
     */
    public function up(): void
    {
        Schema::create('presensi_sesi', function (Blueprint $table) {
            $table->id();
            $table->string('rombel');
            $table->date('tanggal');

            // NIS koor kelas yang input pertama kali (nullable karena
            // admin/kesiswaan juga bisa input dari nol tanpa koor).
            $table->string('koor_nis')->nullable();
            $table->foreign('koor_nis')->references('nis')->on('siswas')->nullOnDelete();

            // Audit siapa & kapan terakhir mengedit (biasanya admin/kesiswaan).
            $table->string('diedit_oleh')->nullable(); // contoh: "admin: Budi (NIP 123)"
            $table->timestamp('diedit_at')->nullable();

            $table->timestamps();

            $table->unique(['rombel', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_sesi');
    }
};
