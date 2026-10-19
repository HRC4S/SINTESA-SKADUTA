<?php

namespace App\Http\Controllers\BK;

use App\Http\Controllers\Controller;
use App\Models\Konseling;
use App\Models\Keterlambatan; // Pastikan Model ini di-import
use Carbon\Carbon;

class DashboardBKController extends Controller
{
    public function index()
    {
        // ==== 1. STATISTIK KONSELING ====
        $konselingMenunggu = Konseling::where('status', 'Menunggu')->count();
        
        // Menggabungkan 'Disetujui' dan 'Selesai' menjadi satu kategori statistik
        $konselingSelesai  = Konseling::whereIn('status', ['Disetujui', 'Selesai'])->count();

        // ==== 2. STATISTIK KETERLAMBATAN (HARI INI) ====
        $awalHariIni = Carbon::today();
        $akhirHariIni = $awalHariIni->copy()->endOfDay();
        $terlambatHariIni = Keterlambatan::whereBetween('created_at', [$awalHariIni, $akhirHariIni])->count();

        // ==== 3. NOTIFIKASI: KONSELING BARU HARI INI ====
        // Ini untuk alert notifikasi biru di atas
        $konselingBaru = Konseling::whereBetween('created_at', [$awalHariIni, $akhirHariIni])->count();

        // ==== 4. RECENT ACTIVITY (DIPISAH) ====
        
        // Ambil 5 Konseling Terbaru (dengan relasi siswa agar nama muncul)
        $recentKonseling = Konseling::with('siswa') 
            ->latest() // Shortcut untuk orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Ambil 5 Keterlambatan Terbaru
        $recentKeterlambatan = Keterlambatan::with('siswa')
            ->latest()
            ->limit(5)
            ->get();

        // ==== 5. DATA CHART (Statistik Bulanan) ====
        $jumlahPerBulan = Konseling::query()
            ->selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'bulan');

        $bulan = [];
        $jumlah = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan[] = Carbon::create()->month($i)->translatedFormat('F');
            $jumlah[] = (int) ($jumlahPerBulan[$i] ?? 0);
        }

        // ==== 6. RETURN VIEW ====
        return view('bk.dashboard', compact(
            'konselingMenunggu',
            'konselingSelesai',
            'terlambatHariIni',
            'konselingBaru',
            'recentKonseling',
            'recentKeterlambatan',
            'bulan',
            'jumlah'
        ));
    }
}