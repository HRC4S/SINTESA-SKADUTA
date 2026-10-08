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
    Schema::create('notifikasis', function (Blueprint $table) {
        $table->id();
        $table->string('nis'); // Penghubung ke siswa
        $table->string('judul');
        $table->text('pesan');
        $table->string('kategori')->default('info'); // info, warning, danger
        $table->boolean('is_read')->default(false); // Status sudah dibaca/belum
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
