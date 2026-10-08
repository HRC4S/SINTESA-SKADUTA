<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;

class SiswaAksesController extends Controller
{
    public function toggleAkses($nis)
    {
        $siswa = Siswa::where('nis', $nis)->firstOrFail();

        $siswa->akses_edit = !$siswa->akses_edit;
        $siswa->save();

        return back()->with('success', 'Akses siswa diperbarui.');
    }
}