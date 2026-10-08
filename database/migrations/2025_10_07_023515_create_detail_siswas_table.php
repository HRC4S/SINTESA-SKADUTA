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
        Schema::create('detail_siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nis'); // foreign key baru
            $table->string('hobi')->nullable();
            $table->string('cita_cita')->nullable();
            $table->integer('berat_badan')->nullable();
            $table->integer('tinggi_badan')->nullable();
            $table->integer('anak_ke')->nullable();
            $table->integer('jumlah_saudara')->nullable();
            $table->string('tinggal_dengan')->nullable();
            $table->string('jarak_rumah')->nullable();
            $table->string('waktu_tempuh')->nullable();
            $table->string('transportasi')->nullable();
            $table->string('nama_jalan')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('dusun')->nullable();
            $table->string('desa')->nullable();
            $table->string('kode_pos')->nullable();
            $table->foreign('nis')->references('nis')->on('siswas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_siswas');
    }
};