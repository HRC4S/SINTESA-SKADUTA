<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $table = 'presensis';

    protected $fillable = [
        'presensi_sesi_id',
        'nis',
        'rombel',
        'tanggal',
        'status',
        'bukti_path',
        'bukti_uploaded_at',
        'keterangan',
    ];

    protected $casts = [
        'tanggal'            => 'date',
        'bukti_uploaded_at'  => 'datetime',
    ];

    // Status yang wajib upload bukti kalau tidak hadir
    public const STATUS_WAJIB_BUKTI = ['sakit', 'izin'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    public function sesi()
    {
        return $this->belongsTo(PresensiSesi::class, 'presensi_sesi_id', 'id');
    }

    public function wajibBukti(): bool
    {
        return in_array($this->status, self::STATUS_WAJIB_BUKTI, true);
    }

    public function sudahUploadBukti(): bool
    {
        return ! empty($this->bukti_path);
    }

    public function getLabelStatusAttribute(): string
    {
        return match ($this->status) {
            'hadir' => 'Hadir',
            'sakit' => 'Sakit',
            'izin'  => 'Izin',
            'alpa'  => 'Alpa',
            default => ucfirst($this->status),
        };
    }
}
