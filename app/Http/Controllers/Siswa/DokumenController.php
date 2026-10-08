<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\DokumenSiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    // 🔹 Tampilkan halaman dokumen siswa
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        $nis = $siswa->nis;

        $jenisDokumen = [
            'Kartu Keluarga',
            'Akta Kelahiran',
            'KPSPKH',
            'KIP',
            'Pas Foto'
        ];

        foreach ($jenisDokumen as $jenis) {
            DokumenSiswa::firstOrCreate(
                ['nis' => $nis, 'jenis_dokumen' => $jenis],
                ['file_path' => null]
            );
        }

        $dokumens = DokumenSiswa::where('nis', $nis)->get()
            ->sortBy(function ($dokumen) use ($jenisDokumen) {
                return array_search($dokumen->jenis_dokumen, $jenisDokumen);
            })
            ->values();

        return view('siswa.dokumensiswa.index', compact('dokumens', 'siswa'));
    }


    // 🔹 Upload / ganti file
    public function upload(Request $request, $id)
    {
        $dokumen = DokumenSiswa::findOrFail($id);
        $nis = Auth::guard('siswa')->user()->nis;

        // Cegah akses dokumen siswa lain
        if ($dokumen->nis !== $nis) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'file' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Hapus file lama jika ada
        if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        // Upload file baru
        $filePath = $request->file('file')->storeAs(
            'dokumen_siswa/' . $nis,
            time() . '_' . $request->file('file')->getClientOriginalName(),
            'public'
        );

        // Update database
        $dokumen->update([
            'file_path' => $filePath,
        ]);

        return back()->with('success', 'File berhasil diunggah!');
    }
}
