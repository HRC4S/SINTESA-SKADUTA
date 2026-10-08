<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    protected $fillable = [
        'nis',
        'judul',
        'jenis',
        'deskripsi',
        'file',
        'link',
        'tanggal_prestasi'
    ];

    public function siswa()
{
    return $this->belongsTo(Siswa::class, 'nis', 'nis');
}

}
