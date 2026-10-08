<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orang_tuas', function (Blueprint $table) {
            $table->id();

            // 🔹 Relasi ke tabel siswas menggunakan NIS
            $table->string('nis');
            $table->foreign('nis')
                  ->references('nis')
                  ->on('siswas')
                  ->onDelete('cascade');

            // 🔹 Data Ayah
            $table->string('nama_ayah')->nullable();
            $table->string('nik_ayah')->nullable();
            $table->string('tahun_lahir_ayah')->nullable();
            $table->string('pendidikan_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('penghasilan_ayah')->nullable();
            $table->string('status_hidup_ayah')->nullable();
            $table->string('no_telp_ayah')->nullable();

            // 🔹 Data Ibu
            $table->string('nama_ibu')->nullable();
            $table->string('nik_ibu')->nullable();
            $table->string('tahun_lahir_ibu')->nullable();
            $table->string('pendidikan_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('penghasilan_ibu')->nullable();
            $table->string('status_hidup_ibu')->nullable();
            $table->string('no_telp_ibu')->nullable();

            // 🔹 Data Wali
            $table->string('nama_wali')->nullable();
            $table->string('nik_wali')->nullable();
            $table->string('tahun_lahir_wali')->nullable();
            $table->string('pendidikan_wali')->nullable();
            $table->string('pekerjaan_wali')->nullable();
            $table->string('penghasilan_wali')->nullable();
            $table->string('status_hidup_wali')->nullable();
            $table->string('no_telp_wali')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::table('orang_tuas', function (Blueprint $table) {
            $table->dropForeign(['nis']);
        });

        Schema::dropIfExists('orang_tuas');
    }
};
