<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konseling extends Model
{
    use HasFactory;

    protected $fillable = [
        // Data Siswa & Ortu
        'nis',
        'nama_siswa',
        'kelas',
        'nama_ortu',
        'alamat_ortu',
        'no_telp_ortu',

        // Data Jadwal & Layanan (BARU)
        'guru_bk_nip',      // <-- Tambahan untuk menyimpan NIP Guru BK
        'tanggal',          // Tanggal pengajuan
        'jam_pengajuan',    // <-- Tambahan untuk jam
        'jenis_layanan',    // <-- Tambahan (Pribadi, Karir, dll)
        
        // Detail Masalah
        'topik',
        'alasan',           // <-- Tambahan (Alasan singkat)
        'latar_belakang',
        'kegiatan_layanan', // Harapan siswa

        // Status Admin
        'status',
        'tanggapan_admin'
    ];

    // =========================================
    // RELASI ANTAR TABEL
    // =========================================

    // Relasi ke Orang Tua (via NIS)
    public function orangTua()
    {
        return $this->hasOne(OrangTua::class, 'nis', 'nis');
    }

    // Relasi ke Siswa (via NIS)
    public function siswa()
    {
        // Pastikan tabel konselings punya kolom 'nis' dan tabel siswas punya 'nis'
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    // Relasi ke Guru BK (BARU - Agar bisa memanggil nama guru di view)
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_bk_nip', 'nip');
    }
}