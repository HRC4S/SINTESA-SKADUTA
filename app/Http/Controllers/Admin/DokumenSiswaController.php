<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\DokumenSiswa;
use Illuminate\Support\Facades\Storage;
use App\Models\Notifikasi;

class DokumenSiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dokumenFilter = $request->input('dokumen');
        $totalDokumenWajib = 5; // jumlah jenis dokumen wajib

        // Ambil semua siswa beserta dokumennya
        $query = Siswa::with('dokumenSiswa');

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Ambil data siswa (paginate)
        $siswa = $query->paginate(10);

        // Hitung total dokumen yang sudah diunggah per siswa
        foreach ($siswa as $s) {
            $uploadedCount = DokumenSiswa::where('nis', $s->nis)
                            ->whereNotNull('file_path')
                            ->count();
            $s->dokumen_siswa_count = $uploadedCount;
        }

        return view('admin.dokumensiswa.index', compact('siswa', 'totalDokumenWajib'));
    }

    public function show($nis)
    {
        $siswa = Siswa::where('nis', $nis)->firstOrFail();

        // Ambil semua dokumen milik siswa
        $dokumen = DokumenSiswa::where('nis', $nis)->get();

        // Daftar dokumen wajib
        $wajib = [
            'Kartu Keluarga',
            'Akta Kelahiran',
            'KPSPKH',
            'KIP',
            'Pas Foto'
        ];

        // Hitung total dokumen yang sudah diunggah
        $totalTerpenuhi = $dokumen->whereNotNull('file_path')->count();
        $totalWajib = count($wajib);

        // Kirim ke view
        return view('admin.dokumensiswa.show', compact(
            'siswa', 'dokumen', 'wajib', 'totalTerpenuhi', 'totalWajib'
        ));
    }

    public function edit($nis)
    {
        $siswa = Siswa::where('nis', $nis)->with('dokumen')->firstOrFail();

        $dokumenWajib = [
            'Kartu Keluarga',
            'Akta Kelahiran',
            'Ijazah',
            'KTP Orang Tua',
            'Pas Foto',
        ];

        return view('admin.dokumensiswa.edit', compact('siswa', 'dokumenWajib'));
    }

    public function update(Request $request, $nis)
    {
        $siswa = Siswa::where('nis', $nis)->firstOrFail();

        $validated = $request->validate([
            'dokumen' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'dokumen.required' => 'Jenis dokumen wajib dipilih.',
            'file.mimes' => 'File harus berupa PDF atau gambar (jpg, jpeg, png).',
            'file.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $dokumen = DokumenSiswa::where('nis', $nis)
            ->where('jenis_dokumen', $validated['dokumen'])
            ->first();

        if ($request->hasFile('file')) {
            if ($dokumen && $dokumen->file_path && Storage::exists('public/' . $dokumen->file_path)) {
                Storage::delete('public/' . $dokumen->file_path);
            }

            $filePath = $request->file('file')->storeAs(
                'dokumen_siswa/' . $nis,
                time() . '_' . $request->file('file')->getClientOriginalName(),
                'public'
            );

            if ($dokumen) {
                $dokumen->update(['file_path' => $filePath]);
            } else {
                DokumenSiswa::create([
                    'nis' => $nis,
                    'jenis_dokumen' => $validated['dokumen'],
                    'file_path' => $filePath,
                ]);
            }
        } else {
            if (!$dokumen) {
                DokumenSiswa::create([
                    'nis' => $nis,
                    'jenis_dokumen' => $validated['dokumen'],
                    'file_path' => null,
                ]);
            }
        }

        return redirect()
            ->route('admin.dokumensiswa.index')
            ->with('success', 'Data dokumen siswa berhasil diperbarui.');
    }

    public function create()
    {
        $rombel = [
            'X DKV 1',
            'X DKV 2',
            'X SIJA 1',
            'X SIJA 2',
            'X TITL 1',
            'X TITL 2',
        ];
        return view('admin.dokumensiswa.create', compact('rombel'));
    }

    public function destroy($nis)
{
    // Ambil semua dokumen milik siswa berdasarkan NIS
    $dokumenList = DokumenSiswa::where('nis', $nis)->get();

    if ($dokumenList->isEmpty()) {
        return redirect()->back()->with('error', 'Tidak ada dokumen yang ditemukan untuk dihapus.');
    }

    // Hapus file fisik jika ada
    foreach ($dokumenList as $dok) {
        if ($dok->file_path && Storage::exists('public/' . $dok->file_path)) {
            Storage::delete('public/' . $dok->file_path);
        }
    }

    // Hapus seluruh record dokumen siswa dari database
    DokumenSiswa::where('nis', $nis)->delete();

    return redirect()
        ->route('admin.dokumensiswa.index')
        ->with('success', 'Semua dokumen siswa berhasil dihapus.');
}

public function kirimPeringatan($nis)
    {
        // 1. Cek data siswa
        $siswa = Siswa::where('nis', $nis)->first();

        if (!$siswa) {
            return back()->with('error', 'Siswa tidak ditemukan.');
        }

        // 2. Buat Notifikasi
        Notifikasi::create([
            'nis'      => $nis,
            'judul'    => '⚠️ Peringatan Dokumen',
            'pesan'    => 'Halo ' . $siswa->nama_lengkap . ', admin mendeteksi dokumen Anda belum lengkap. Mohon segera lengkapi di menu Upload Dokumen.',
            'kategori' => 'warning',
            'is_read'  => false
        ]);

        // 3. Kembali dengan pesan sukses
        return back()->with('success', 'Peringatan berhasil dikirim ke ' . $siswa->nama_lengkap);
    }   
}
