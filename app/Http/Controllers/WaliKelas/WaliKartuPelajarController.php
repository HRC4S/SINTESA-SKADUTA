<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class WaliKartuPelajarController extends Controller
{
    /**
     * Menampilkan daftar siswa sesuai rombel wali kelas.
     */
    public function index(Request $request)
    {   

        $guru = auth('guru')->user();
        // Ambil rombel wali kelas dari user login
         $rombel = $guru->walikelas; // ambil rombel wali kelas

        // Ambil semua siswa di rombel wali
        $siswas = Siswa::where('rombel', $rombel)->paginate(36);


        // Query siswa dalam rombel tersebut
        $query = Siswa::where('rombel', $rombel);

        // Fitur search (nama atau NIS)
        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->q . '%')
                  ->orWhere('nis', 'like', '%' . $request->q . '%');
            });
        }

        // Pagination
        $siswas = $query->orderBy('nama_lengkap', 'asc')->paginate(40);

        return view('walikelas.kartupelajar.index', compact('siswas', 'rombel'));

    }

    /**
     * Preview kartu pelajar satu siswa.
     */
    public function preview($nis)
    {
        $rombel = auth()->user()->walikelas;

            $siswa = Siswa::where('nis', $nis)
                        ->where('rombel', $rombel)
                        ->firstOrFail();


        return view('walikelas.kartupelajar.preview', compact('siswa'));
    }

    public function cetak($nis)
{
    $guru = auth('guru')->user();
    $rombel = $guru->walikelas;

    $siswa = Siswa::where('nis', $nis)
                  ->where('rombel', $rombel)
                  ->firstOrFail();

    return view('walikelas.kartupelajar.preview', compact('siswa'));
}

public function frame($nis)
{
    $guru = auth('guru')->user();
    $rombel = $guru->walikelas;

    $siswa = Siswa::where('nis', $nis)
                  ->where('rombel', $rombel)
                  ->firstOrFail();

    return view('walikelas.kartupelajar.kartu', compact('siswa'));
}

}

