<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\OrangTua;

class OrtuController extends Controller
{
    // TAMPIL DETAIL
    public function show($nis)
    {
        $siswa = Siswa::with('orangTua')->where('nis', $nis)->firstOrFail();
        return view('siswa.dataortu.show', compact('siswa'));
    }

    // TAMPIL FORM EDIT
    public function edit($nis)
    {
        $siswa = Siswa::with('orangTua')->where('nis', $nis)->firstOrFail();

        // Hanya boleh edit jika admin mengaktifkan
        if ($siswa->akses_edit != 1) {
            return redirect()->back()->with('error', 'Akses pengeditan belum diaktifkan oleh admin.');
        }

        return view('siswa.dataortu.edit', compact('siswa'));
    }

    // PROSES UPDATE
    public function update(Request $request, $nis)
    {
        $siswa = Siswa::with('orangTua')->where('nis', $nis)->firstOrFail();

        $ortu = $siswa->orangTua ?? new OrangTua();
        $ortu->nis = $siswa->nis;

        $ortu->fill($request->all());
        $ortu->save();

        return redirect()
            ->route('siswa.ortu.show', $nis)
            ->with('success', 'Data orang tua berhasil diperbarui.');
    }
}
