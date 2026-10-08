<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();

            // FK ke tabel siswa menggunakan NIS
            $table->string('nis');

            $table->string('judul');              // contoh: Juara 1 Futsal
            $table->string('jenis');              // sertifikat / seminar / lomba / lainnya
            $table->text('deskripsi')->nullable();

            // File upload
            $table->string('file')->nullable();   // pdf/jpg/jpeg/png

            // Link prestasi opsional
            $table->string('link')->nullable();

            $table->date('tanggal_prestasi')->nullable();
            $table->timestamps();

            // Foreign key → ke tabel siswas kolom nis
            $table->foreign('nis')
                  ->references('nis')
                  ->on('siswas')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};
