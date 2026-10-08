@extends('layouts.admin')

@section('title', 'Dashboard Wali Kelas')
@section('page_title', 'Dashboard Wali Kelas')

@section('content')

{{-- Import Google Fonts --}}
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    :root {
        --primary: #1e3a8a;
        --secondary: #64748b;
        --bg-body: #f8fafc;
        --card-bg: #ffffff;
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --radius: 12px;
    }

    body { font-family: 'Poppins', sans-serif; background-color: var(--bg-body); }

    /* ===== WELCOME CARD ===== */
    .welcome-card {
    /* Menggunakan warna solid biru sesuai identitas SINTESA */
    background: #17375d; /* Biru tua yang konsisten dengan desain poster Anda */
    padding: 30px; 
    border-radius: var(--radius); 
    color: white;
    margin-bottom: 30px; 
    position: relative; 
    overflow: hidden; 
    box-shadow: var(--shadow);
}
    .welcome-card::before { content: ''; position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; }
    .welcome-content h1 { margin: 0; font-size: 24px; font-weight: 700; }
    .welcome-content p { margin: 5px 0 0; opacity: 0.9; font-size: 14px; font-weight: 300; }
    .class-badge { background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; margin-top: 10px; }

    /* ===== STATS GRID (4 KOLOM) ===== */
    .stats-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px; margin-bottom: 30px;
    }

    .stat-card {
        background: var(--card-bg); padding: 20px; border-radius: var(--radius);
        box-shadow: var(--shadow); display: flex; align-items: center; justify-content: space-between;
        transition: transform 0.2s; border-left: 4px solid transparent;
    }
    .stat-card:hover { transform: translateY(-3px); }

    .border-blue { border-left-color: #102C57; }
    .border-cyan { border-left-color: #C1ECE4; }
    .border-pink { border-left-color: #FFCCE1; }
    .border-yellow { border-left-color: #f59e0b; }

    .stat-info h3 { margin: 0; font-size: 26px; font-weight: 700; color: #1e293b; }
    .stat-info p { margin: 0; font-size: 13px; color: var(--secondary); font-weight: 500; }
    
    .stat-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 24px;
    }

    /* ===== MAIN LAYOUT (GRAFIK & NOTIF) ===== */
    .dashboard-layout {
        display: grid; grid-template-columns: 2fr 1fr; gap: 25px;
    }

    .card-box {
        background: var(--card-bg); border-radius: var(--radius); padding: 25px;
        box-shadow: var(--shadow); height: 100%;
    }
    
    .card-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px;
    }
    .card-title { font-size: 16px; font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 8px; }

    /* Chart Container (Supaya Pie Chart tidak kegedean) */
    .chart-container { position: relative; height: 250px; width: 100%; display: flex; justify-content: center; }

    /* Notifikasi List */
    .notif-list { display: flex; flex-direction: column; gap: 12px; }
    .notif-item {
        display: flex; align-items: center; gap: 15px; padding: 12px;
        border-radius: 10px; background: #fff; border: 1px solid #f1f5f9;
        text-decoration: none; color: inherit; transition: 0.2s;
    }
    .notif-item:hover { background: #f8fafc; border-color: #e2e8f0; }
    
    .notif-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;
    }
    .bg-red-light { background: #fee2e2; color: #dc2626; }
    .bg-blue-light { background: #eff6ff; color: #3b82f6; }

    .notif-info h6 { margin: 0; font-size: 13px; font-weight: 600; color: #334155; }
    .notif-info span { font-size: 11px; color: #94a3b8; display: block; margin-top: 2px; }

    /* Progress Bar Dokumen */
    .doc-progress { margin-top: 5px; }
    .progress-bar { width: 100%; height: 6px; background: #e2e8f0; border-radius: 3px; overflow: hidden; margin-top: 5px; }
    .progress-fill { height: 100%; background: #dc2626; border-radius: 3px; }

    /* Responsive */
    @media (max-width: 992px) { .dashboard-layout { grid-template-columns: 1fr; } }
</style>

{{-- 1. WELCOME CARD --}}
<div class="welcome-card">

    <h1>Selamat Datang, Wali Kelas {{ auth('guru')->user()->walikelas }}</h1>

    <p>Monitoring Data Siswa Rombel {{ auth('guru')->user()->walikelas }}</p>

</div>

{{-- 2. STATISTIK UTAMA --}}
<div class="stats-grid">
    {{-- Total Siswa --}}
    <div class="stat-card border-blue">
        <div class="stat-info">
            <p>Total Siswa</p>
            <h3>{{ $totalSiswa }}</h3>
        </div>
        <div class="stat-icon" style="background:#eff6ff; color:#3b82f6;">
            <i class="fas fa-users"></i>
        </div>
    </div>

    {{-- Laki-Laki --}}
    <div class="stat-card border-cyan">
        <div class="stat-info">
            <p>Laki-Laki</p>
            <h3>{{ $totalLaki }}</h3>
        </div>
        <div class="stat-icon" style="background:#ecfeff; color:#06b6d4;">
            <i class="fas fa-male"></i>
        </div>
    </div>

    {{-- Perempuan --}}
    <div class="stat-card border-pink">
        <div class="stat-info">
            <p>Perempuan</p>
            <h3>{{ $totalPerempuan }}</h3>
        </div>
        <div class="stat-icon" style="background:#fdf2f8; color:#ec4899;">
            <i class="fas fa-female"></i>
        </div>
    </div>

    {{-- Prestasi --}}
    <div class="stat-card border-yellow">
        <div class="stat-info">
            <p>Prestasi Kelas</p>
            <h3>{{ $totalPrestasi }}</h3>
        </div>
        <div class="stat-icon" style="background:#fffbeb; color:#f59e0b;">
            <i class="fas fa-trophy"></i>
        </div>
    </div>
</div>

{{-- 3. MAIN CONTENT (GRAFIK & SIDEBAR) --}}
<div class="dashboard-layout">
    
    {{-- KOLOM KIRI: GRAFIK & STATISTIK --}}
    <div class="left-column">
        
        {{-- Grafik Gender --}}
        <div class="card-box" style="margin-bottom: 25px;">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-chart-pie"></i> Komposisi Siswa</div>
            </div>
            <div class="chart-container">
                <canvas id="genderChart"></canvas>
            </div>
        </div>

    </div>

    {{-- KOLOM KANAN: STATUS & NOTIFIKASI --}}
    <div class="right-column">
        
        {{-- Status Dokumen Siswa --}}
        <div class="card-box" style="margin-bottom: 25px; height: auto;">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-file-alt"></i> Status Dokumen</div>
                <a href="{{ route('wali.dokumensiswa') }}" style="font-size:12px; text-decoration:none; color:#64748b;">Detail</a>
            </div>
            
            <div class="notif-item" style="display: block;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:5px;">
                    <span style="font-size:13px; font-weight:600; color:#334155;">Kelengkapan Dokumen</span>
                    <span style="font-size:12px; color:#dc2626; font-weight:600;">{{ $dokumenBelum }} Belum</span>
                </div>
                
                {{-- Hitung Persentase --}}
                @php
                    $persenDokumen = $totalSiswa > 0 ? round((($totalSiswa - $dokumenBelum) / $totalSiswa) * 100) : 0;
                @endphp
                
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $persenDokumen }}%; background: {{ $persenDokumen == 100 ? '#10b981' : '#f59e0b' }};"></div>
                </div>
                <div style="font-size:11px; color:#94a3b8; margin-top:5px;">
                    {{ $totalSiswa - $dokumenBelum }} dari {{ $totalSiswa }} siswa sudah lengkap.
                </div>
            </div>
        </div>

        {{-- Prestasi Terbaru (Mini List) --}}
        <div class="card-box" style="height: auto;">
            <div class="card-header">
                <div class="card-title" style="color:#f59e0b;"><i class="fas fa-star"></i> Prestasi Terbaru</div>
            </div>

            <div class="notif-list">
                @if($prestasiTerbaru)
                    <div class="notif-item">
                        <div class="notif-icon" style="background:#fffbeb; color:#f59e0b;">
                            <i class="fas fa-medal"></i>
                        </div>
                        <div class="notif-info">
                            <h6>{{ Str::limit($prestasiTerbaru->judul, 25) }}</h6>
                            <span>{{ \Carbon\Carbon::parse($prestasiTerbaru->tanggal_prestasi)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                @else
                    <div style="text-align:center; padding:15px; font-size:13px; color:#94a3b8;">
                        Belum ada data prestasi.
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- SCRIPT CHART --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('genderChart').getContext('2d');

    new Chart(ctx, {
        type: 'doughnut', // Ganti Pie jadi Doughnut agar lebih modern
        data: {
            labels: ['Laki-Laki', 'Perempuan'],
            datasets: [{
                data: [{{ $totalLaki }}, {{ $totalPerempuan }}],
                backgroundColor: ['#40679E', '#FFB1B1'], // Cyan & Pink
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%', // Lubang tengah
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, padding: 20 }
                }
            }
        }
    });
});
</script>

@endsection