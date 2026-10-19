<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kartu_pelajar_settings', function (Blueprint $table) {
            $table->unsignedSmallInteger('angkatan')->nullable()->after('id');
            $table->string('template_style', 20)->default('standar')->after('tanda_tangan_path');
        });

        DB::table('kartu_pelajar_settings')
            ->whereNull('angkatan')
            ->update(['angkatan' => DB::raw('tahun_terbit')]);

        DB::table('kartu_pelajar_settings')
            ->where('angkatan', '>=', now()->year)
            ->update(['template_style' => 'baru']);

        Schema::table('kartu_pelajar_settings', function (Blueprint $table) {
            $table->unique('angkatan');
        });
    }

    public function down(): void
    {
        Schema::table('kartu_pelajar_settings', function (Blueprint $table) {
            $table->dropUnique(['angkatan']);
            $table->dropColumn(['angkatan', 'template_style']);
        });
    }
};
