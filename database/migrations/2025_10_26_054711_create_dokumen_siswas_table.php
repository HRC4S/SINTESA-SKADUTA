<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_siswas', function (Blueprint $table) {
            $table->id();

            // karena siswa pakai NIS sebagai primary key:
            $table->string('nis'); // foreign key ke kolom 'nis' di tabel siswas

            $table->string('jenis_dokumen');
            $table->string('file_path')->nullable();
            $table->timestamps();

            // relasi ke tabel nis
            $table->foreign('nis')
                  ->references('nis')
                  ->on('siswas')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_siswas');
    }
};
