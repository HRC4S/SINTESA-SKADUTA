<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('konselings', function (Blueprint $table) {
        $table->id();
        $table->string('nis');
        $table->string('nama_siswa');
        $table->string('kelas');
        $table->string('nama_ortu');
        $table->string('alamat_ortu')->nullable();
        $table->string('no_telp_ortu')->nullable();
        $table->date('tanggal');
        $table->string('topik');
        $table->text('latar_belakang');
        $table->text('kegiatan_layanan');
        $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
        $table->text('tanggapan_admin')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konselings');
    }
};
