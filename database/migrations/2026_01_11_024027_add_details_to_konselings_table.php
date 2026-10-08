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
        Schema::table('konselings', function (Blueprint $table) {
            // Menambahkan kolom baru tanpa menghapus data lama
            $table->string('guru_bk_nip')->nullable()->after('no_telp_ortu');
            $table->time('jam_pengajuan')->nullable()->after('tanggal');
            $table->string('jenis_layanan')->nullable()->after('jam_pengajuan');
            $table->text('alasan')->nullable()->after('topik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('konselings', function (Blueprint $table) {
            // Hapus kolom jika di-rollback
            $table->dropColumn(['guru_bk_nip', 'jam_pengajuan', 'jenis_layanan', 'alasan']);
        });
    }
};