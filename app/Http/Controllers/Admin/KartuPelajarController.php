<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use Milon\Barcode\Facades\DNS1D;
use Barryvdh\DomPDF\Facade\Pdf;


class KartuPelajarController extends Controller
{
    // 🧭 Daftar siswa
   public function index(Request $request)
{
    $q = $request->query('q');
    $rombel = $request->query('rombel'); // ✅ ambil rombel dari request

    $query = Siswa::query();

    // ✅ Filter pencarian
    if ($q) {
        $query->where(function ($w) use ($q) {
            $w->where('nama_lengkap', 'like', "%{$q}%")
                ->orWhere('nis', 'like', "%{$q}%")
                ->orWhere('nisn', 'like', "%{$q}%")
                ->orWhere('rombel', 'like', "%{$q}%")
                ->orWhere('jurusan', 'like', "%{$q}%");
        });
    }

    // ✅ Filter rombel (kelas)
    if ($rombel) {
        $query->where('rombel', $rombel);
    }

    $siswas =  $query->orderBy('rombel')->orderBy('nama_lengkap')
                    ->paginate(30)
                    ->withQueryString();

    // ✅ Untuk dropdown rombel di view
    $rombels = Siswa::select('rombel')->distinct()->orderBy('rombel')->get();

    return view('admin.kartupelajar.index', compact('siswas', 'q', 'rombel', 'rombels'));
}


    // 🔍 Pencarian cepat (ajax)
    public function search(Request $request)
    {
        $q = $request->query('q');
        $siswas = Siswa::when($q, fn($query) =>
                        $query->where('nama_lengkap', 'like', "%{$q}%"))
                        ->orderBy('nama_lengkap')
                        ->limit(50)
                        ->get();

        return view('admin.kartupelajar._rows', compact('siswas'));
    }

    // 🧾 PREVIEW kartu pelajar (dalam layout admin)
    public function preview($nis)
    {
        $siswa = Siswa::where('nis', $nis)->firstOrFail();

        // arahkan ke halaman preview yang pakai layout admin
        return view('admin.kartupelajar.preview', compact('siswa'));
    }

    // 🧾 CETAK MASSAL (HTML)
    public function printMass(Request $request)
{
    $nisList = explode(',', $request->nis_list);
    $siswas = Siswa::whereIn('nis', $nisList)->get();
    
    return view('admin.kartupelajar.preview', compact('siswas'));
}

    public function downloadPDF($nis)
{
    $siswa = Siswa::where('nis', $nis)->firstOrFail();
     $customPaper = [0, 0, 244, 153]; 
    $pdf = Pdf::loadView('admin.kartupelajar.kartu', compact('siswa'))
    
        ->setPaper([0, 0, 243.78, 153.07], 'portrait') // ukuran kartu pelajar
        ->setOption([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

              
    return $pdf->download('Kartu_Pelajar_'.$siswa->nama_lengkap.'.pdf');
}
public function previewFrame($nis)
{
    $siswa = Siswa::where('nis', $nis)->first();
    // View ini sama seperti preview.blade.php kamu, tapi tanpa layout admin
    return view('admin.kartupelajar.kartu', compact('siswa'));
}

}
