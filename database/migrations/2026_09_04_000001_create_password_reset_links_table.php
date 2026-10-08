<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel token reset password terpadu untuk guard 'guru' & 'siswa'.
     * Dipisah dari 'password_reset_tokens' bawaan Laravel karena aplikasi ini
     * punya 2 guard (guru & siswa) dengan primary key non-email (nip/nis),
     * sedangkan tabel bawaan hanya berbasis email tunggal.
     */
    public function up(): void
    {
        Schema::create('password_reset_links', function (Blueprint $table) {
            $table->id();
            $table->string('guard');        // 'guru' atau 'siswa'
            $table->string('identifier');   // nip (guru) atau nis (siswa)
            $table->string('email');        // email tujuan saat link dikirim
            $table->string('token_hash');   // hash(sha256) dari token acak
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index(['guard', 'identifier']);
            $table->index('token_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_links');
    }
};
