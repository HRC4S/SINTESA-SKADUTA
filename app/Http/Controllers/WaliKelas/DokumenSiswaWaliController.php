<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\DokumenSiswa;
use Illuminate\Support\Facades\Storage;

class DokumenSiswaWaliController extends Controller
{
    // INDEX
public function index(Request $request)
{
    $guru = auth('guru')->user();

    // Ambil rombel wali kelas dari tabel guru
    $rombel = $guru->walikelas;

    // Query siswa yang rombelnya sesuai
    $query = Siswa::where('rombel', $rombel)
        ->withCount([
            'dokumenSiswa as dokumen_uploaded_count' => function ($q) {
                $q->whereNotNull('file_path');
            }
        ]);

    $query->orderBy('nama_lengkap', 'asc');

    // Search
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('nis', 'like', "%{$request->search}%")
              ->orWhere('nama_lengkap', 'like', "%{$request->search}%");
        });
    }

    // Filter dokumen
    if ($request->filled('dokumen')) {
        $query->whereHas('dokumenSiswa', function ($q) use ($request) {
            $q->where('jenis_dokumen', $request->dokumen);
        });
    }

    $siswa = $query->paginate(40);
    $totalDokumenWajib = 5;

    return view('walikelas.dokumensiswa.index', compact('siswa', 'totalDokumenWajib'));
}

    // SHOW
    public function show($nis)
    {
        // Ambil guru wali
        $guru = auth('guru')->user();

        // Ambil siswa berdasarkan NIS
        $siswa = Siswa::where('nis', $nis)->firstOrFail();

        // Cek apakah siswa sesuai kelas wali
        if ($siswa->rombel !== $guru->walikelas) {
            abort(403, 'Anda tidak memiliki akses ke siswa ini.');
        }

        // Ambil dokumen siswa
        $dokumen = DokumenSiswa::where('nis', $nis)->get();

        // Daftar dokumen wajib
        $wajib = [
            'Kartu Keluarga',
            'Akta Kelahiran',
            'KPSPKH',
            'KIP',
            'Pas Foto'
        ];

        // Hitung jumlah dokumen yang sudah diunggah
        $totalTerpenuhi = $dokumen->whereNotNull('file_path')->count();
        $totalWajib = count($wajib);

        // Kembalikan view
        return view('walikelas.dokumensiswa.show', compact(
            'siswa',
            'dokumen',
            'wajib',
            'totalTerpenuhi',
            'totalWajib'
        ));
    }

    // EDIT
    public function edit($nis)
    {
        $guru = auth('guru')->user();

        // Ambil data siswa
        $siswa = Siswa::where('nis', $nis)->firstOrFail();

        // Cek apakah ini siswa wali kelasnya
        if ($siswa->rombel !== $guru->walikelas) {
            abort(403, 'Anda tidak memiliki akses ke siswa ini.');
        }

        // Ambil dokumen, jika tidak ada buat object kosong
        $dokumen = DokumenSiswa::firstOrNew([
            'nis' => $nis
        ]);

        // Daftar dokumen wajib
        $dokumenWajib = [
            'Kartu Keluarga',
            'Akta Kelahiran',
            'KPSPKH',
            'KIP',
            'Pas Foto'
        ];

        // Jenis dokumen yang dipilih, jika kosong jadikan null
        $dokumenDipilih = $dokumen->jenis_dokumen ?? null;

        return view('walikelas.dokumensiswa.edit', compact(
            'siswa',
            'dokumen',
            'dokumenWajib',
            'dokumenDipilih'
        ));
    }

    // UPDATE
    public function update(Request $request, $nis)
    {
        $guru = auth('guru')->user();

        $siswa = Siswa::where('nis', $nis)
                        ->where('rombel', $guru->walikelas)
                        ->firstOrFail();

        // Validasi
        $request->validate([
            'dokumen' => 'required|string',
            'file' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        // Cek apakah dokumen sudah ada
        $dokumen = DokumenSiswa::where('nis', $nis)
                                ->where('jenis_dokumen', $request->dokumen)
                                ->first();

        // Jika belum ada, buat record baru
        if (!$dokumen) {
            $dokumen = new DokumenSiswa();
            $dokumen->nis = $nis;
            $dokumen->jenis_dokumen = $request->dokumen;
        }

        // Jika upload file baru
        if ($request->hasFile('file')) {

            // Hapus file lama jika ada
            if ($dokumen->file_path && Storage::exists($dokumen->file_path)) {
                Storage::delete($dokumen->file_path);
            }

            // Upload file baru
            $path = $request->file('file')->store('dokumen_siswa');
            $dokumen->file_path = $path;
        }

        $dokumen->save();

        return back()->with('success', 'Dokumen berhasil diperbarui.');
    }

    // DESTROY
    public function destroy($nis)
    {
        $guru = auth('guru')->user();

        $siswa = Siswa::where('nis', $nis)
                        ->where('rombel', $guru->walikelas)
                        ->firstOrFail();

        $dokumen = DokumenSiswa::where('nis', $nis)->get();

        foreach ($dokumen as $doc) {
            if ($doc->file_path && Storage::exists($doc->file_path)) {
                Storage::delete($doc->file_path);
            }

            $doc->delete();
        }

        return back()->with('success', 'Semua dokumen siswa berhasil dihapus.');
    }

    private function ensureSiswaInKelasWali($siswa)
    {
        $guru = auth('guru')->user();

        if ($siswa->rombel !== $guru->walikelas) {
            abort(403, 'Anda tidak memiliki akses ke siswa ini.');
        }
    }

}
