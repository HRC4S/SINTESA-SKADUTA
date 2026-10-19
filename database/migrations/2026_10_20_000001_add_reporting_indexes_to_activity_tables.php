<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keterlambatans', function (Blueprint $table) {
            $table->index('created_at', 'keterlambatans_created_at_index');
            $table->index(['tanggal', 'jenis'], 'keterlambatans_tanggal_jenis_index');
            $table->index(['status', 'tanggal'], 'keterlambatans_status_tanggal_index');
        });

        Schema::table('prestasis', function (Blueprint $table) {
            $table->index('tanggal_prestasi', 'prestasis_tanggal_prestasi_index');
        });
    }

    public function down(): void
    {
        Schema::table('keterlambatans', function (Blueprint $table) {
            $table->dropIndex('keterlambatans_created_at_index');
            $table->dropIndex('keterlambatans_tanggal_jenis_index');
            $table->dropIndex('keterlambatans_status_tanggal_index');
        });

        Schema::table('prestasis', function (Blueprint $table) {
            $table->dropIndex('prestasis_tanggal_prestasi_index');
        });
    }
};
