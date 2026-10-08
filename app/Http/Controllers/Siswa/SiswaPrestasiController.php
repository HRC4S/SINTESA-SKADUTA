<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prestasi;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class SiswaPrestasiController extends Controller
{
    public function index()
    {
        // Siswa yang sedang login
        $siswa = Auth::guard('siswa')->user();

        // Semua prestasi berdasarkan nis siswa
        $prestasi = Prestasi::where('nis', $siswa->nis)->latest()->get();

        // Hitung total prestasi
        $totalPrestasi = $prestasi->count();

        // Hitung total per jenis prestasi
        $totalSertifikat = Prestasi::where('nis', $siswa->nis)
            ->where('jenis', 'sertifikat')
            ->count();

        $totalSeminar = Prestasi::where('nis', $siswa->nis)
            ->where('jenis', 'seminar')
            ->count();

        $totalLomba = Prestasi::where('nis', $siswa->nis)
            ->where('jenis', 'lomba')
            ->count();

        $totalLainnya = Prestasi::where('nis', $siswa->nis)
            ->where('jenis', 'lainnya')
            ->count();

        return view('siswa.prestasi.index', compact(
            'prestasi',
            'siswa',
            'totalPrestasi',
            'totalSertifikat',
            'totalSeminar',
            'totalLomba',
            'totalLainnya'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'jenis' => 'required',
            'tanggal_prestasi' => 'required|date',
            'deskripsi' => 'nullable',
            'link' => 'nullable|string',
            'file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $fileName = null;

        // Jika upload file → simpan file → kosongkan link
        if ($request->file('file')) {
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->storeAs('prestasi', $fileName, 'public');
            $request->merge(['link' => null]);
        }

        // Jika ada link → kosongkan file
        if ($request->link) {
            $fileName = null;
        }

        Prestasi::create([
            'nis' => auth()->user()->nis,  
            'judul' => $request->judul,
            'jenis' => $request->jenis,
            'tanggal_prestasi' => $request->tanggal_prestasi,
            'deskripsi' => $request->deskripsi,
            'link' => $request->link,
            'file' => $fileName,
        ]);

        return redirect()->back()->with('success', 'Data prestasi berhasil ditambahkan');
    }

      public function edit($id)
    {
        $siswa = auth()->guard('siswa')->user(); // ⬅️ ini penting

        $prestasi = Prestasi::findOrFail($id);

        return view('siswa.prestasi.edit', compact('prestasi','siswa'), );
    }

    /**
     * UPDATE DATA PRESTASI
     */
     public function update(Request $request, $id)
    {
        $prestasi = Prestasi::findOrFail($id);

        $request->validate([
            'judul' => 'required',
            'jenis' => 'required',
            'tanggal_prestasi' => 'required|date',
            'deskripsi' => 'nullable',
            'link' => 'nullable|string',
            'file' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $fileName = $prestasi->file;

        // Jika upload file baru
        if ($request->file('file')) {
            // Hapus file lama
            if ($prestasi->file && file_exists(storage_path('app/public/prestasi/'.$prestasi->file))) {
                unlink(storage_path('app/public/prestasi/'.$prestasi->file));
            }

            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->storeAs('public/prestasi', $fileName);

            // Kosongkan link
            $request->merge(['link' => null]);
        }

        // Jika isi link → hapus file
        if ($request->link) {
            // Hapus file lama
            if ($prestasi->file && file_exists(storage_path('app/public/prestasi/'.$prestasi->file))) {
                unlink(storage_path('app/public/prestasi/'.$prestasi->file));
            }
            $fileName = null;
        }

        $prestasi->update([
            'judul' => $request->judul,
            'jenis' => $request->jenis,
            'tanggal_prestasi' => $request->tanggal_prestasi,
            'deskripsi' => $request->deskripsi,
            'link' => $request->link,
            'file' => $fileName,
        ]);

        return redirect()->route('siswa.prestasi.index')->with('success', 'Data prestasi berhasil diperbarui');
    }


    /**
     * HAPUS DATA PRESTASI
     */
    public function destroy($id)
    {
        $prestasi = Prestasi::findOrFail($id);

        // hapus file
        if ($prestasi->file && file_exists(public_path('uploads/prestasi/'.$prestasi->file))) {
            unlink(public_path('uploads/prestasi/'.$prestasi->file));
        }

        $prestasi->delete();

        return redirect()->route('siswa.prestasi.index')
                         ->with('success', 'Prestasi berhasil dihapus');
    }
}

