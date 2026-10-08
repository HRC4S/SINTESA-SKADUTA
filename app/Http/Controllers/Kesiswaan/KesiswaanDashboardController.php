<?php

namespace App\Http\Controllers\Kesiswaan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Keterlambatan;
use App\Models\DokumenSiswa;
use App\Models\Prestasi;
use Carbon\Carbon;

class KesiswaanDashboardController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        // === 1. CARD INFORMASI UTAMA ===
        $totalSiswa = Siswa::count();
        $totalKeterlambatan = Keterlambatan::count();

        $sudahUpload = DokumenSiswa::distinct('nis')->count('nis');
        $persenSudah = $totalSiswa > 0 ? round(($sudahUpload / $totalSiswa) * 100) : 0;
        $belumUpload = max(0, $totalSiswa - $sudahUpload);

        // === 2. DATA GRAFIK JURUSAN ===
        $dataJurusan = Siswa::select(
                'jurusan',
                DB::raw("CONCAT('20', LEFT(nis, 2)) as angkatan"),
                DB::raw('count(*) as total')
            )
            ->groupBy('jurusan', DB::raw("CONCAT('20', LEFT(nis, 2))"))
            ->get();

        $listAngkatan = Siswa::select(DB::raw("CONCAT('20', LEFT(nis, 2)) as angkatan"))
            ->distinct()
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');

        // === 3. DATA GRAFIK PRESTASI (PERBAIKAN DISINI) ===
        
        // A. Ambil daftar tahun (Menggunakan created_at atau tanggal_prestasi)
        // Kita gunakan tanggal_prestasi agar sesuai data
        $listTahunPrestasi = Prestasi::selectRaw('YEAR(tanggal_prestasi) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // B. Tentukan tahun pilihan
        $tahunPilihan = $request->input('year_prestasi');

        // Jika user tidak memilih (awal load), dan ada data tahun, pakai tahun terbaru dari DB
        if (!$tahunPilihan) {
            $tahunPilihan = $listTahunPrestasi->first() ?? date('Y');
        }

        // C. Query data (FIX GROUP BY)
        $prestasiStats = Prestasi::selectRaw('MONTH(tanggal_prestasi) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_prestasi', $tahunPilihan)
            ->groupBy(DB::raw('MONTH(tanggal_prestasi)')) // <--- PERBAIKAN PENTING: Group by Raw Function
            ->pluck('total', 'bulan')
            ->toArray();

        // D. Mapping Data 1-12 Bulan
        $prestasiData = [];
        for ($i = 1; $i <= 12; $i++) {
            // Cek apakah ada data di bulan $i, jika tidak beri nilai 0
            $prestasiData[] = isset($prestasiStats[$i]) ? $prestasiStats[$i] : 0;
        }

        // === 4. NOTIFIKASI ===
        $keterlambatanTerbaru = Keterlambatan::with('siswa')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        return view('kesiswaan.dashboard', compact(
            'totalSiswa',
            'totalKeterlambatan',
            'sudahUpload',
            'belumUpload',
            'persenSudah',
            'dataJurusan',
            'listAngkatan',
            'prestasiData',
            'keterlambatanTerbaru',
            'listTahunPrestasi',
            'tahunPilihan'
        ));
    }
}