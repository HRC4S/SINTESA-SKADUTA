<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasFactory;

    protected $table = 'rombels'; // nama tabel di database
    protected $fillable = ['nama_rombel']; // kolom yang bisa diisi
}
