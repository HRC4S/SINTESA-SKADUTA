<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PresensiSayaController extends Controller
{
    /**
     * 🔹 Riwayat presensi siswa yang login, per bulan.
     */
    public function index(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();

        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $presensis = Presensi::where('nis', $siswa->nis)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get();

        $rekap = [
            'hadir' => $presensis->where('status', 'hadir')->count(),
            'sakit' => $presensis->where('status', 'sakit')->count(),
            'izin'  => $presensis->where('status', 'izin')->count(),
            'alpa'  => $presensis->where('status', 'alpa')->count(),
        ];

        return view('siswa.presensi.riwayat', compact('presensis', 'rekap', 'bulan', 'tahun'));
    }

    /**
     * 🔹 Upload bukti (sakit/izin) untuk presensi milik SENDIRI.
     * Wajib cek kepemilikan (nis) supaya tidak jadi IDOR.
     */
    public function uploadBukti(Request $request, int $id)
    {
        $siswa = Auth::guard('siswa')->user();

        $presensi = Presensi::where('id', $id)
            ->where('nis', $siswa->nis) // 🔒 kepemilikan wajib dicek
            ->firstOrFail();

        if (! $presensi->wajibBukti()) {
            return back()->with('error', 'Status presensi ini tidak memerlukan bukti.');
        }

        $request->validate([
            'bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ], [
            'bukti.mimes' => 'Bukti harus berupa file JPG, PNG, atau PDF.',
            'bukti.max'   => 'Ukuran file maksimal 2 MB.',
        ]);

        // Hapus bukti lama kalau ada replace
        if ($presensi->bukti_path && Storage::disk('public')->exists($presensi->bukti_path)) {
            Storage::disk('public')->delete($presensi->bukti_path);
        }

        $path = $request->file('bukti')->store('bukti_presensi/' . $siswa->nis, 'public');

        $presensi->update([
            'bukti_path'        => $path,
            'bukti_uploaded_at' => now(),
        ]);

        return back()->with('success', 'Bukti berhasil diunggah.');
    }
}
