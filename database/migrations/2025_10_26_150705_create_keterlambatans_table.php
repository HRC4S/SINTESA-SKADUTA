<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('keterlambatans', function (Blueprint $table) {
            $table->id();
            $table->string('nis'); // gunakan NIS, bukan siswa_id
            $table->string('nama_siswa');
            $table->date('tanggal');
            $table->time('jam_datang');
            $table->integer('menit_terlambat');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            // relasi ke tabel siswa (kolom nis)
            $table->foreign('nis')->references('nis')->on('siswas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keterlambatans');
    }
};

