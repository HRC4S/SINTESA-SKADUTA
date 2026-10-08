<?php 

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Authenticatable
{
    use HasFactory, Notifiable;

    // Tabel
    protected $table = 'siswas';

    // Primary key NIS
    protected $primaryKey = 'nis';
    public $incrementing = false;
    protected $keyType = 'string';

    // Kolom yang bisa diisi
    protected $fillable = [
        'nis', 'nisn', 'nama_lengkap', 'email', 'no_whatsapp',
        'rombel', 'jurusan', 'tempat_lahir', 'tanggal_lahir',
        'jenis_kelamin', 'agama', 'nama_ortu', 'alamat', 'foto', 
        'password','is_default_password', 'role', // ✅ penting untuk login siswa
    ];

    // Sembunyikan password di output
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'akses_edit' => 'boolean',
    ];

    public function getAuthIdentifierName()
    {
        return 'nis';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }


    // Relasi Detail Siswa
    public function detailSiswa()
    {
        return $this->hasOne(DetailSiswa::class, 'nis', 'nis');
    }

    // Relasi Orang Tua
    public function orangTua()
    {
        return $this->hasOne(OrangTua::class, 'nis', 'nis');
    }

    // Relasi Dokumen
    public function dokumen()
    {
        return $this->hasMany(DokumenSiswa::class, 'nis', 'nis');
    }

    // Custom attribute nama pengguna
    public function getNamaPenggunaAttribute()
    {
        return $this->nama_lengkap;
    }

    public function dokumenSiswa()
    {
        return $this->hasMany(DokumenSiswa::class, 'nis', 'nis');
    }  

    public function konselings()
{
    return $this->hasMany(\App\Models\Konseling::class, 'nis', 'nis');
}

    // Relasi Presensi
    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'nis', 'nis');
    }

    // Apakah siswa ini koor kelas (case-insensitive, karena data lama pakai "Siswa" berbagai casing)
    public function isKoorKelas(): bool
    {
        return strtolower((string) $this->role) === 'koor_kelas';
    }

}