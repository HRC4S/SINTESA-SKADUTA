<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru', function (Blueprint $table) {
            $table->string('nip')->primary(); 
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('no_telp')->nullable();
            $table->text('alamat')->nullable(); 
            $table->string('mata_pelajaran')->nullable(); 
            $table->enum('role', ['guru_bk', 'kesiswaan', 'walikelas', 'admin', 'guru'])->default('guru');
            $table->string('walikelas')->nullable(); 
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
