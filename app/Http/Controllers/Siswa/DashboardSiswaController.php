<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Konseling; // ✅ pastikan ini ada
use App\Models\DokumenSiswa;
use App\Models\Notifikasi;


class DashboardSiswaController extends Controller
{
    public function dashboard()
    {    
        $siswa = Auth::guard('siswa')->user();

        $nis = auth('siswa')->user()->nis;
        // ✅ Ambil semua riwayat konseling siswa ini
        $konselings = Konseling::where('nis', $siswa->nis)
                        ->orderBy('tanggal', 'desc')
                        ->get();

        // Ambil notifikasi belum dibaca
        $notifikasi = Notifikasi::where('nis', $nis)
                    ->where('is_read', false)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('siswa.dashboard', compact('siswa', 'konselings', 'notifikasi'));
    }

    public function dataSiswa()
    {
        $siswa = Auth::guard('siswa')->user();
        return view('siswa.datasiswa.index', compact('siswa'));
    }

    public function dataOrangtua()
    {
        $siswa = Auth::guard('siswa')->user();
        return view('siswa.dataortu.index', compact('siswa'));
    }

    public function kartuPelajar()
    {
        $siswa = Auth::guard('siswa')->user();
        return view('siswa.kartupelajar.preview', compact('siswa'));
    }

    public function frame($nis)
    {
        $siswa = Auth::guard('siswa')->user();

        // Pastikan hanya bisa melihat kartunya sendiri
        if ($siswa->nis !== $nis) {
            abort(403, 'Tidak diizinkan melihat kartu siswa lain.');
        }

        return view('siswa.kartupelajar.frame', compact('siswa'));
    }

    public function konseling()
    {
        $siswa = Auth::guard('siswa')->user();

        // ✅ tampilkan daftar pengajuan konseling siswa
        $konselings = Konseling::where('nis', $siswa->nis)
                        ->orderBy('tanggal', 'desc')
                        ->get();

        return view('siswa.konseling.index', compact('siswa', 'konselings'));
    }

    public function keterlambatan()
    {
        $siswa = Auth::guard('siswa')->user();
        return view('siswa.keterlambatan.index', compact('siswa'));
    }

    public function DokumenSiswa()
{
    $siswa = Auth::guard('siswa')->user();

    $dokumens = DokumenSiswa::where('nis', $siswa->nis)->get();

    return view('siswa.dokumen.index', compact('siswa', 'dokumens'));
}

    public function bacaPeringatan($id)
    {
        $notif = Notifikasi::find($id);

        if (!$notif) {
            return back()->with('error', 'Notifikasi tidak ditemukan.');
        }

        // Update jadi sudah dibaca
        $notif->update([
            'is_read' => true
        ]);

        return back()->with('success', 'Notifikasi telah ditandai sebagai dibaca.');
    }

}
