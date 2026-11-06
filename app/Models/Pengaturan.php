<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    public const POIN_PER_KETERLAMBATAN = 'poin_per_keterlambatan';

    protected $fillable = ['key', 'value'];

    protected function casts(): array
    {
        return ['value' => 'integer'];
    }
}