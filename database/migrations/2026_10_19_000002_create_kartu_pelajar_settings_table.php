<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kartu_pelajar_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('bulan_terbit');
            $table->unsignedSmallInteger('tahun_terbit');
            $table->string('nama_kepsek');
            $table->string('nip_kepsek', 50);
            $table->string('tanda_tangan_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kartu_pelajar_settings');
    }
};
