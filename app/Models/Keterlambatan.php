<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keterlambatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'jenis',
        'nama_siswa',
        'rombel',
        'no_absen',
        'tanggal',
        'jam_datang',
        'jam_ke',
        'menit_terlambat',
        'keterangan',
        'dokumen',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    public function isMasuk(): bool
    {
        return $this->jenis === 'masuk';
    }

    public function isKeluar(): bool
    {
        return $this->jenis === 'keluar';
    }

    public function getLabelJenisAttribute(): string
    {
        return $this->jenis === 'keluar' ? 'Izin Keluar Kelas' : 'Izin Masuk Kelas';
    }
}
