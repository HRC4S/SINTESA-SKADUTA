<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class WaliSiswaController extends Controller
{
    // List siswa kelas wali
    public function index()
    {
        $guru = auth('guru')->user();
        $rombel = $guru->walikelas; // ambil rombel wali kelas

        // Ambil semua siswa di rombel wali
        $siswas = Siswa::where('rombel', $rombel)->orderBy('nama_lengkap', 'asc')->paginate(40);

        // Hitung jumlah siswa
        $jumlah = Siswa::where('rombel', $rombel)->count();

        return view('walikelas.datasiswa.siswa', compact('siswas', 'rombel', 'jumlah'));
    }

    // Edit satu siswa
    public function edit($nis)
    {
        $guru = auth('guru')->user();
        $rombel = $guru->walikelas;

        $siswa = Siswa::where('nis', $nis)
                      ->where('rombel', $rombel) // keamanan
                      ->firstOrFail();

        return view('walikelas.datasiswa.edit', compact('siswa'));
    }

    // Update siswa
    public function update(Request $request, $nis)
    {
        $guru = auth('guru')->user();
        $rombel = $guru->walikelas;

        $siswa = Siswa::where('nis', $nis)
                      ->where('rombel', $rombel)
                      ->firstOrFail();

        $siswa->update([
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->route('wali.datasiswa')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function show($nis)
{
    $guru = auth('guru')->user();
    $rombel = $guru->walikelas;

    // Pastikan siswa yang diakses hanya yang satu rombel
    $siswa = Siswa::where('nis', $nis)
                  ->where('rombel', $rombel)
                  ->firstOrFail();

    return view('walikelas.datasiswa.show', compact('siswa'));
}
     public function toggleAkses($nis)
    {
        $siswa = Siswa::where('nis', $nis)->firstOrFail();

        $siswa->akses_edit = !$siswa->akses_edit;
        $siswa->save();

        return back()->with('success', 'Akses siswa diperbarui.');
    }

    private function generateRombelBaru($rombelLama)
{
    // Format rombel: "XII TKR 3" atau "X KGS" dll

    $parts = explode(' ', $rombelLama);

    // Kelas = X, XI, XII, XIII
    $kelas = $parts[0];

    // Jurusan bisa 1 kata atau lebih
    $jurusan = implode(' ', array_slice($parts, 1));

    // Proses naik kelas
    switch ($kelas) {
        case 'X':
            $kelasBaru = 'XI';
            break;
        case 'XI':
            $kelasBaru = 'XII';
            break;
        case 'XII':
            $kelasBaru = 'XIII';
            break;
        case 'XIII':
            $kelasBaru = 'XIII'; // Tidak naik lagi
            break;
        default:
            $kelasBaru = $kelas;
    }

    return $kelasBaru . ' ' . $jurusan;
}

public function naikkanSemua()
{
    $guru = auth('guru')->user();
    $rombel = $guru->walikelas;

    $rombelsBaru = $this->generateRombelBaru($rombel);

    Siswa::where('rombel', $rombel)->update([
        'rombel' => $rombelsBaru
    ]);

    return back()->with('success', 'Semua siswa berhasil dinaikkan ke ' . $rombelsBaru . '.');
}

public function naikkanSatu($nis)
{
    $guru = auth('guru')->user();
    $rombel = $guru->walikelas;

    $siswa = Siswa::where('nis', $nis)
                  ->where('rombel', $rombel)
                  ->firstOrFail();

    $rombelBaru = $this->generateRombelBaru($siswa->rombel);

    $siswa->update([
        'rombel' => $rombelBaru
    ]);

    return back()->with('success', 'Siswa berhasil dinaikkan ke ' . $rombelBaru . '.');
}


}
