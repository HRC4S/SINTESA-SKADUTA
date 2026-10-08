<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $guarded = [];
    //
    protected $table = 'notifikasis';
    
    protected $primaryKey = 'id';
    
    protected $fillable = ['nis', 'judul', 'pesan', 'kategori', 'is_read'];

}
