<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use HasFactory;

    protected $table = 'orang_tuas';

    protected $fillable = [
        'nis',
        'nama_ayah','nik_ayah','tahun_lahir_ayah','pendidikan_ayah','pekerjaan_ayah','penghasilan_ayah','status_hidup_ayah','no_telp_ayah',
        'nama_ibu','nik_ibu','tahun_lahir_ibu','pendidikan_ibu','pekerjaan_ibu','penghasilan_ibu','status_hidup_ibu','no_telp_ibu',
        'nama_wali','nik_wali','tahun_lahir_wali','pendidikan_wali','pekerjaan_wali','penghasilan_wali','status_hidup_wali','no_telp_wali'
    ];

    /**
     * Relasi ke tabel siswa
     * OrangTua milik satu Siswa (foreign key: nis)
     */
   public function siswa()
{
    return $this->belongsTo(Siswa::class, 'nis', 'nis');
}

}
