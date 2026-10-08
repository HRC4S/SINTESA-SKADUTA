<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresensiSesi extends Model
{
    protected $table = 'presensi_sesi';

    protected $fillable = [
        'rombel',
        'tanggal',
        'koor_nis',
        'diedit_oleh',
        'diedit_at',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'diedit_at'   => 'datetime',
    ];

    public function koor()
    {
        return $this->belongsTo(Siswa::class, 'koor_nis', 'nis');
    }

    public function detail()
    {
        return $this->hasMany(Presensi::class, 'presensi_sesi_id', 'id');
    }
}
