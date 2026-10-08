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
        $terlambatHariIni = Keterlambatan::whereDate('created_at', Carbon::today())->count();

        // ==== 3. NOTIFIKASI: KONSELING BARU HARI INI ====
        // Ini untuk alert notifikasi biru di atas
        $konselingBaru = Konseling::whereDate('created_at', Carbon::today())->count();

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
        $bulan = [];
        $jumlah = [];

        // Loop 12 Bulan (Januari - Desember)
        for ($i = 1; $i <= 12; $i++) {
            $bulan[] = Carbon::create()->month($i)->translatedFormat('F'); // Nama Bulan (Indo jika locale diset id)
            $jumlah[] = Konseling::whereMonth('created_at', $i)->count();
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