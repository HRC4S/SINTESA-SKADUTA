@extends('layouts.admin')

@section('title', 'Dashboard BK')
@section('page_title', 'Dashboard Guru BK')

@section('content')

{{-- Load Google Fonts & Icons --}}
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    :root {
        --primary: #123B6B;
        --secondary: #64748b;
        --bg-body: #f1f5f9;
        --card-bg: #ffffff;
        --shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        --radius: 16px;
    }

    body { font-family: 'Poppins', sans-serif; background-color: var(--bg-body); }

    /* ===== NOTIFIKASI BAR (LONCENG STYLE + CLOSE BUTTON) ===== */
    .notif-wrapper {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 35px;
    }

    .notif-item {
        background: #fff;
        padding: 15px 40px 15px 20px; /* Padding kanan lebih besar untuk tombol X */
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border-left: 5px solid transparent;
        transition: all 0.3s ease;
        position: relative; /* Penting untuk tombol close absolute */
    }
    
    .notif-item:hover {
        transform: translateX(5px);
    }

    /* Tombol Close (X) */
    .btn-close-notif {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        color: #cbd5e1;
        font-size: 18px;
        cursor: pointer;
        transition: 0.2s;
        line-height: 1;
    }
    .btn-close-notif:hover {
        color: #ef4444; /* Merah saat hover */
        transform: scale(1.2);
    }

    /* Warna & Style */
    .notif-blue { border-left-color: #3b82f6; }
    .notif-blue .bell-icon { background: #eff6ff; color: #3b82f6; }
    
    .notif-red { border-left-color: #ef4444; }
    .notif-red .bell-icon { background: #fef2f2; color: #ef4444; }

    .notif-gray { border-left-color: #cbd5e1; opacity: 0.8; }
    .notif-gray .bell-icon { background: #f1f5f9; color: #94a3b8; }

    /* Ikon Lonceng */
    .bell-icon {
        width: 45px; height: 45px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    
    /* Animasi Lonceng */
    .animate-bell { animation: bellShake 2s infinite; }
    @keyframes bellShake {
        0% { transform: rotate(0); } 10% { transform: rotate(15deg); } 20% { transform: rotate(-15deg); }
        30% { transform: rotate(10deg); } 40% { transform: rotate(-10deg); } 50% { transform: rotate(0); } 100% { transform: rotate(0); }
    }

    .notif-content { flex: 1; }
    .notif-content h5 { margin: 0; font-size: 15px; font-weight: 700; color: #1e293b; }
    .notif-content p { margin: 2px 0 0; font-size: 13px; color: #64748b; }

    .btn-notif-action {
        background: #f8fafc; color: #475569; padding: 8px 15px; border-radius: 8px;
        font-size: 12px; font-weight: 600; text-decoration: none; transition: 0.2s;
        border: 1px solid #e2e8f0; white-space: nowrap;
    }
    .btn-notif-action:hover { background: #1e3a8a; color: white; border-color: #1e3a8a; }

    /* ===== WELCOME CARD (TANPA TANGGAL) ===== */
    .welcome-card {
        background: linear-gradient(135deg, var(--primary) 0%, #102C57 100%);
        padding: 40px; border-radius: var(--radius); color: white; margin-bottom: 30px;
        position: relative; overflow: hidden; box-shadow: var(--shadow);
    }
    .welcome-card::before { content: ''; position: absolute; top: -60px; right: -60px; width: 250px; height: 250px; background: rgba(255,255,255,0.1); border-radius: 50%; }
    .welcome-card::after { content: ''; position: absolute; bottom: -40px; right: 80px; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%; }
    .welcome-content { position: relative; z-index: 2; }
    .welcome-content h1 { margin: 0; font-size: 26px; font-weight: 700; letter-spacing: 0.5px; }
    .welcome-content p { margin: 8px 0 0; opacity: 0.9; font-size: 15px; font-weight: 300; }

    /* ===== STATS CARDS ===== */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card {
        background: var(--card-bg); padding: 25px; border-radius: var(--radius); box-shadow: var(--shadow);
        display: flex; align-items: center; gap: 20px; transition: 0.3s; border-bottom: 4px solid transparent;
    }
    .stat-card:hover { box-shadow: 0 10px 25px rgba(0,0,0,0.1); transform: translateY(-3px); }
    .stat-icon { width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 26px; }

    .card-waiting { border-bottom-color: #f59e0b; }
    .icon-waiting { background: #fffbeb; color: #f59e0b; }
    .card-done { border-bottom-color: #10b981; }
    .icon-done { background: #ecfdf5; color: #10b981; }
    .card-late { border-bottom-color: #ef4444; }
    .icon-late { background: #fef2f2; color: #ef4444; }
    .stat-info h3 { margin: 0; font-size: 20px; font-weight: 700; color: #0f172a; line-height: 1.3; }
    .stat-info span { font-size: 13px; color: var(--secondary); font-weight: 500; }

    /* ===== CHART & ACTIVITY ===== */
    .chart-wrapper { background: white; padding: 30px; border-radius: var(--radius); box-shadow: var(--shadow); margin-bottom: 30px; }
    .chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .chart-header h4 { margin: 0; font-size: 16px; font-weight: 700; color: var(--primary); }

    .split-wrapper { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-top: 30px; }
    .list-card { background: var(--card-bg); border-radius: var(--radius); padding: 25px; box-shadow: var(--shadow); height: 100%; display: flex; flex-direction: column; }
    .list-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; }
    .list-header h4 { margin: 0; font-size: 16px; font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 10px; }
    .list-header a { font-size: 12px; font-weight: 600; text-decoration: none; color: var(--secondary); }
    .list-header a:hover { color: var(--primary); }

    .activity-item {
        display: flex; align-items: center; gap: 15px; padding: 12px 10px; border-radius: 10px; margin-bottom: 5px;
        text-decoration: none; color: inherit; transition: 0.2s; border-bottom: 1px solid #f8fafc;
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-item:hover { background-color: #f8fafc; transform: translateX(5px); }

    .user-avatar { width: 42px; height: 42px; border-radius: 12px; background: #e2e8f0; color: #64748b; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 600; flex-shrink: 0; }
    .item-details { flex: 1; overflow: hidden; }
    .item-details h6 { margin: 0; font-size: 14px; font-weight: 600; color: #334155; }
    .item-details span { font-size: 11px; color: #94a3b8; display: block; margin-top: 3px; }
    .status-pill { font-size: 11px; padding: 4px 10px; border-radius: 20px; font-weight: 600; white-space: nowrap; }
    .pill-yellow { background: #fff7ed; color: #c2410c; }
    .pill-green { background: #f0fdf4; color: #15803d; }
    .pill-red { background: #fef2f2; color: #b91c1c; }

    @media (max-width: 992px) { .split-wrapper { grid-template-columns: 1fr; } }
</style>

{{-- 1. NOTIFIKASI / ALERT (STYLE LONCENG + TOMBOL CLOSE) --}}
<div class="notif-wrapper">
    
    {{-- Notif Konseling --}}
    @if(isset($konselingMenunggu) && $konselingMenunggu > 0)
        <div class="notif-item notif-blue">
            <button class="btn-close-notif" onclick="closeNotif(this)">&times;</button>
            <div class="bell-icon animate-bell">
                <i class="fas fa-bell"></i>
            </div>
            <div class="notif-content">
                <h5>Permintaan Konseling Baru</h5>
                <p>Halo Guru BK, ada <strong>{{ $konselingMenunggu }} siswa</strong> yang menunggu persetujuan jadwal.</p>
            </div>
            <a href="{{ route('admin.konseling.index') }}" class="btn-notif-action">
                Tinjau <i class="fas fa-arrow-right" style="margin-left:5px;"></i>
            </a>
        </div>
    @endif

    {{-- Notif Keterlambatan --}}
    @if(isset($terlambatHariIni) && $terlambatHariIni > 0)
        <div class="notif-item notif-red">
            <button class="btn-close-notif" onclick="closeNotif(this)">&times;</button>
            <div class="bell-icon animate-bell">
                <i class="fas fa-bell"></i>
            </div>
            <div class="notif-content">
                <h5>Laporan Keterlambatan</h5>
                <p>Perhatian, tercatat <strong>{{ $terlambatHariIni }} siswa</strong> datang terlambat hari ini.</p>
            </div>
            <a href="{{ route('admin.keterlambatan.index') }}" class="btn-notif-action">
                Lihat <i class="fas fa-arrow-right" style="margin-left:5px;"></i>
            </a>
        </div>
    @endif

    {{-- Pesan Aman (Jika semua 0) --}}
    @if((!isset($konselingMenunggu) || $konselingMenunggu == 0) && (!isset($terlambatHariIni) || $terlambatHariIni == 0))
        <div class="notif-item notif-gray">
            <button class="btn-close-notif" onclick="closeNotif(this)">&times;</button>
            <div class="bell-icon">
                <i class="far fa-bell-slash"></i>
            </div>
            <div class="notif-content">
                <h5>Tidak Ada Notifikasi</h5>
                <p>Semua aman! Tidak ada pengajuan pending atau keterlambatan hari ini.</p>
            </div>
        </div>
    @endif

</div>

{{-- 2. WELCOME CARD --}}
<div class="welcome-card">
    <div class="welcome-content">
        <h1>Selamat Datang di Dashboard BK</h1>
        <p>Pantau perkembangan siswa dan kelola jadwal konseling dengan mudah dan efisien.</p>
    </div>
</div>

{{-- 3. STATISTIK UTAMA --}}
<div class="stats-grid">
    <div class="stat-card card-waiting">
        <div class="stat-icon icon-waiting"><i class="fas fa-hourglass-half"></i></div>
        <div class="stat-info">
            <h3>{{ $konselingMenunggu }} Konseling</h3>
            <span>Masuk & menunggu persetujuan</span>
        </div>
    </div>
    <div class="stat-card card-done">
        <div class="stat-icon icon-done"><i class="fas fa-clipboard-check"></i></div>
        <div class="stat-info">
            <h3>{{ $konselingSelesai }} Konseling</h3>
            <span>Telah berhasil diselesaikan</span>
        </div>
    </div>
    <div class="stat-card card-late">
        <div class="stat-icon icon-late"><i class="fas fa-user-clock"></i></div>
        <div class="stat-info">
            <h3>{{ $terlambatHariIni }} Siswa</h3>
            <span>Datang terlambat hari ini</span>
        </div>
    </div>
</div>

{{-- 4. CHART SECTION --}}
<div class="chart-wrapper">
    <div class="chart-header">
        <h4><i class="fas fa-chart-area me-2"></i> Statistik Konseling Bulanan</h4>
    </div>
    <div style="position: relative; height: 350px; width: 100%;">
        <canvas id="chartKonseling"></canvas>
    </div>
</div>

{{-- 5. LIST AKTIVITAS --}}
<div class="split-wrapper">
    <div class="list-card">
        <div class="list-header">
            <h4><i class="fas fa-comments" style="color: #2563eb;"></i> Konseling Terbaru</h4>
            <a href="{{ route('admin.konseling.index') }}">Lihat Semua</a>
        </div>
        @forelse($recentKonseling as $bk)
            <a href="{{ route('admin.konseling.show', $bk->id) }}" class="activity-item">
                <div class="user-avatar" style="background: #eff6ff; color: #1e3a8a;">
                    {{ substr($bk->nama_siswa ?? 'S', 0, 1) }}
                </div>
                <div class="item-details">
                    <h6>{{ Str::limit($bk->nama_siswa, 20) }}</h6>
                    <span>{{ $bk->kelas ?? 'Siswa' }} • {{ $bk->created_at->diffForHumans() }}</span>
                </div>
                <div>
                    @if($bk->status == 'Menunggu') <span class="status-pill pill-yellow">Pending</span>
                    @elseif($bk->status == 'Selesai' || $bk->status == 'Disetujui') <span class="status-pill pill-green">Selesai</span>
                    @else <span class="status-pill pill-red">Ditolak</span> @endif
                </div>
            </a>
        @empty
            <div class="text-center py-4 text-muted" style="font-size:13px;">Belum ada data konseling.</div>
        @endforelse
    </div>

    <div class="list-card">
        <div class="list-header">
            <h4><i class="fas fa-clock" style="color: #dc2626;"></i> Keterlambatan Terbaru</h4>
            <a href="{{ route('admin.keterlambatan.index') }}">Lihat Semua</a>
        </div>
        @forelse($recentKeterlambatan as $late)
            <a href="{{ route('admin.keterlambatan.index') }}" class="activity-item">
                <div class="user-avatar" style="background: #fef2f2; color: #991b1b;">
                    {{ substr($late->nama_siswa ?? 'S', 0, 1) }}
                </div>
                <div class="item-details">
                    <h6>{{ Str::limit($late->nama_siswa, 20) }}</h6>
                    <span>Jam ke {{ $late->jam_ke ?: '-' }} • {{ \Carbon\Carbon::parse($late->tanggal)->format('d M') }}</span>
                </div>
                <div><span class="status-pill pill-red">{{ $late->jenis == 'keluar' ? 'Keluar' : 'Masuk' }}</span></div>
            </a>
        @empty
            <div class="text-center py-4 text-muted" style="font-size:13px;">Belum ada data keterlambatan.</div>
        @endforelse
    </div>
</div>

{{-- SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Chart Logic
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('chartKonseling').getContext('2d');
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(18, 59, 107, 0.2)'); 
        gradient.addColorStop(1, 'rgba(18, 59, 107, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($bulan), 
                datasets: [{
                    label: 'Jumlah Pengajuan',
                    data: @json($jumlah), 
                    borderColor: '#123B6B',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#123B6B',
                    pointRadius: 5,
                    fill: true,
                    tension: 0.4 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#f1f5f9' }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });
    });

    // 2. Close Notification Logic
    function closeNotif(button) {
        const item = button.closest('.notif-item');
        item.style.opacity = '0';
        item.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            item.remove();
        }, 300);
    }
</script>

@endsection