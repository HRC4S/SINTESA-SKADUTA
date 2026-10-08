<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->string('nis')->primary(); // Ubah menjadi primary key
            $table->string('nisn')->nullable();
            $table->string('nama_lengkap');
            $table->string('email')->nullable();
            $table->string('no_whatsapp')->nullable();
            $table->string('rombel')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('agama')->nullable();
            $table->string('nama_ortu')->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Putar balik migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
