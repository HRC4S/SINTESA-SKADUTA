<?php

namespace Database\Seeders;

use App\Models\Guru;
use Illuminate\Database\Seeder;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pengguna Admin
        Guru::create([
            'nip' => '99999999',
            'nama' => 'Admin Utama',
            'email' => 'admin@sintesa.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Pengguna Guru BK
        Guru::create([
            'nip' => '11223344',
            'nama' => 'Guru BK',
            'email' => 'gurubk@sintesa.id',
            'password' => Hash::make('gurubk123'),
            'role' => 'guru_bk',
        ]);

        // Pengguna Kesiswaan
        Guru::create([
            'nip' => '55667788',
            'nama' => 'Kesiswaan',
            'email' => 'kesiswaan@sintesa.id',
            'password' => Hash::make('kesiswaan123'),
            'role' => 'kesiswaan',
        ]);

        // Pengguna Guru
        Guru::create([
            'nip' => '6767676767',
            'nama' => 'Guru',
            'email' => 'guru@sintesa.id',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
        ]);
        // Pengguna Walikelas
        Guru::create([
            'nip' => '767676767676',
            'nama' => 'Walikelas',
            'email' => 'walikelas@sintesa.id',
            'password' => Hash::make('walikelas123'),
            'role' => 'walikelas',
            'walikelas' => 'XII IPA 1',
        ]);
    }
}
