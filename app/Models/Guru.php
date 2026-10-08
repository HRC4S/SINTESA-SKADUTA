<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Guru extends Authenticatable
{
    use Notifiable;
    protected $guard = 'guru';
    protected $table = 'guru';
    protected $primaryKey = 'nip';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nip', 'nama', 'email', 'password', 'role','walikelas',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
