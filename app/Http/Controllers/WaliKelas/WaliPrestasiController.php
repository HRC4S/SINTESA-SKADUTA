<?php

namespace App\Http\Controllers\Walikelas;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WaliPrestasiController extends Controller
{
    public function index()
    {
        $wali = auth()->guard('guru')->user();
        // Gunakan properti yang sesuai di model Guru Anda (walikelas atau lainnya)
        $rombel = $wali->walikelas; 

        $prestasi = Prestasi::whereHas('siswa', function ($q) use ($rombel) {
            $q->where('rombel', $rombel);
        })
        ->orderBy('tanggal_prestasi', 'desc')
        ->get();

        // Pastikan folder di resources/views adalah 'walikelas'
        return view('walikelas.prestasi.index', compact('prestasi'));
    }

    public function create()
    {
        $wali = auth()->guard('guru')->user();
        $rombel = $wali->walikelas;

        $siswas = Siswa::where('rombel', $rombel)
                        ->orderBy('nama_lengkap', 'asc')
                        ->get();

        return view('walikelas.prestasi.create', compact('siswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|exists:siswas,nis',
            'judul' => 'required|string|max:255',
            'jenis' => 'required|in:lomba,seminar,sertifikat,lainnya',
            'tanggal_prestasi' => 'required|date',
            'deskripsi' => 'nullable|string',
            'link' => 'nullable|url',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $request->nis . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/prestasi', $filename);
            $data['file'] = $filename;
        }

        Prestasi::create($data);

        return redirect()->route('wali.prestasi.index')
                         ->with('success', 'Data prestasi berhasil ditambahkan!');
    }

    public function show($nis)
    {
        $siswa = Siswa::where('nis', $nis)->firstOrFail();

        $prestasi = Prestasi::where('nis', $nis)
            ->orderBy('tanggal_prestasi', 'desc')
            ->get();

        return view('walikelas.prestasi.show', compact('siswa', 'prestasi'));
    }

    public function destroy($id)
    {
        $prestasi = Prestasi::findOrFail($id);
        
        // Hapus file fisik jika ada di storage
        if ($prestasi->file) {
            Storage::delete('public/prestasi/' . $prestasi->file);
        }
        
        $prestasi->delete();

        return redirect()->route('wali.prestasi.index')
                        ->with('success', 'Data prestasi berhasil dihapus!');
    }
}