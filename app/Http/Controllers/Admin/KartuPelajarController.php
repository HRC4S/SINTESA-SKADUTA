<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\KartuPelajarSetting;
use Milon\Barcode\Facades\DNS1D;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;


class KartuPelajarController extends Controller
{
    public function editPengaturan(Request $request)
    {
        $request->validate([
            'angkatan' => ['nullable', 'integer', 'between:2000,2099'],
        ]);

        $angkatan = (int) $request->query('angkatan', now()->year);
        $pengaturan = KartuPelajarSetting::forAngkatan($angkatan)->initializeForAngkatan();
        $daftarAngkatan = KartuPelajarSetting::availableAngkatan();

        return view('admin.kartupelajar.pengaturan', compact('pengaturan', 'angkatan', 'daftarAngkatan'));
    }

    public function previewPengaturan(Request $request)
    {
        $validated = $request->validate([
            'angkatan' => ['required', 'integer', 'between:2000,2099'],
            'template_style' => ['nullable', 'in:standar,baru'],
            'bulan' => ['nullable', 'integer', 'between:1,12'],
            'tahun' => ['nullable', 'integer', 'between:2000,2100'],
            'nama_kepsek' => ['nullable', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50'],
        ]);

        $angkatan = (int) $validated['angkatan'];
        $nisPrefix = substr((string) ($angkatan % 100 + 100), -2);
        $siswa = new Siswa([
            'nis' => $nisPrefix . '0000001',
            'nisn' => '0000000001',
            'nama_lengkap' => 'CONTOH NAMA SISWA',
            'rombel' => 'X',
            'jurusan' => 'CONTOH JURUSAN',
            'tempat_lahir' => 'Yogyakarta',
            'tanggal_lahir' => '2008-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'agama' => 'Islam',
            'nama_ortu' => 'Nama Orang Tua',
            'alamat' => 'Yogyakarta',
        ]);

        return view('kartupelajar.template', compact('siswa'));
    }

    public function updatePengaturan(Request $request)
    {
        $validated = $request->validate([
            'angkatan' => ['required', 'integer', 'between:2000,2099'],
            'bulan_terbit' => ['required', 'integer', 'between:1,12'],
            'tahun_terbit' => ['required', 'integer', 'between:2000,2100'],
            'nama_kepsek' => ['required', 'string', 'max:255'],
            'nip_kepsek' => ['required', 'string', 'max:50'],
            'template_style' => ['required', 'in:standar,baru'],
            'tanda_tangan' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'tanda_tangan.image' => 'File tanda tangan harus berupa gambar.',
            'tanda_tangan.mimes' => 'Tanda tangan harus berformat JPG atau PNG.',
            'tanda_tangan.max' => 'Ukuran gambar tanda tangan maksimal 2 MB.',
        ]);

        $angkatan = (int) $validated['angkatan'];
        $pengaturan = KartuPelajarSetting::forAngkatan($angkatan);
        $pathLama = $pengaturan->tanda_tangan_path;

        if ($request->hasFile('tanda_tangan')) {
            $validated['tanda_tangan_path'] = $request->file('tanda_tangan')
                ->store('kartu-pelajar', 'public');
        }

        unset($validated['tanda_tangan']);
        $pengaturan->update($validated);

        if (isset($validated['tanda_tangan_path']) && $pathLama) {
            Storage::disk('public')->delete($pathLama);
        }

        return redirect()
            ->route('admin.kartupelajar.pengaturan', ['angkatan' => $angkatan])
            ->with('success', "Pengaturan kartu pelajar angkatan {$angkatan} berhasil disimpan.");
    }

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
