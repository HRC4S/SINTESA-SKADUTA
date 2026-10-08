@extends('layouts.admin')

@section('title', 'Dashboard Kesiswaan')
@section('page_title', 'Dashboard Kesiswaan')

@section('content')

{{-- Import Google Fonts --}}
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    :root {
        --primary: #123B6B;
        --secondary: #64748b;
        --bg-body: #f8fafc;
        --card-bg: #ffffff;
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --radius: 12px;
    }

    body { font-family: 'Poppins', sans-serif; background-color: var(--bg-body); }

    /* ===== WELCOME CARD ===== */
    .welcome-card {
        background: linear-gradient(135deg, var(--primary) 0%, #123B6B 100%);
        padding: 20px 30px; border-radius: var(--radius); color: white;
        margin-bottom: 20px; position: relative; overflow: hidden; box-shadow: var(--shadow);
    }
    .welcome-content h1 { margin: 0; font-size: 20px; font-weight: 700; }
    .welcome-content p { margin: 2px 0 0; opacity: 0.9; font-size: 12px; font-weight: 300; }

    /* ===== STATS GRID (Lebih Kecil) ===== */
    .stats-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px; margin-bottom: 20px;
    }

    .stat-card {
        background: var(--card-bg); padding: 15px; border-radius: var(--radius);
        box-shadow: var(--shadow); display: flex; flex-direction: column; justify-content: center;
        border-left: 4px solid transparent; transition: transform 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); }

    .border-blue { border-left-color: #94B4C1; }
    .border-orange { border-left-color: #94B4C1; }
    .border-green { border-left-color: #94B4C1 ; }

    .stat-header { display: flex; justify-content: space-between; align-items: center; }
    .stat-icon {
        width: 35px; height: 35px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; background: #f1f5f9; color: var(--primary);
    }
    .stat-value h3 { margin: 0; font-size: 20px; font-weight: 700; color: #1e293b; }
    .stat-label { font-size: 11px; color: var(--secondary); font-weight: 600; text-transform: uppercase; margin-bottom: 2px; }

    /* Progress Bar Dokumen */
    .progress-wrapper { margin-top: 10px; }
    .progress-info { display: flex; justify-content: space-between; font-size: 10px; margin-bottom: 4px; font-weight: 600; }
    .progress-bg { width: 100%; height: 5px; background: #e2e8f0; border-radius: 3px; overflow: hidden; }
    .progress-fill { height: 100%; border-radius: 3px; transition: width 1s ease-in-out; }

    /* ===== MAIN CONTENT GRID ===== */
    .main-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
    
    .chart-card {
        background: var(--card-bg); padding: 20px; border-radius: var(--radius);
        box-shadow: var(--shadow); margin-bottom: 20px;
    }
    .card-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;
    }
    .card-title { font-size: 14px; font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 8px; }

    /* Notifikasi Style */
    .notif-list { display: flex; flex-direction: column; gap: 8px; }
    .notif-item {
        display: flex; align-items: center; gap: 12px; padding: 10px;
        border-radius: 8px; background: #fff; border: 1px solid #f1f5f9;
        transition: 0.2s; text-decoration: none; color: inherit;
    }
    .notif-item:hover { background: #f8fafc; border-color: #e2e8f0; transform: translateX(3px); }
    .notif-icon {
        width: 32px; height: 32px; border-radius: 8px; background: #fee2e2; color: #dc2626;
        display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;
    }
    .notif-info h6 { margin: 0; font-size: 12px; font-weight: 600; color: #334155; }
    .notif-info span { font-size: 10px; color: #94a3b8; }

    .filter-select {
        padding: 4px 8px; border-radius: 6px; border: 1px solid #e2e8f0;
        font-size: 11px; color: #333; outline: none; cursor: pointer; background: #f8fafc;
    }

    @media (max-width: 992px) { .main-grid { grid-template-columns: 1fr; } }
</style>

{{-- 1. WELCOME --}}
<div class="welcome-card">
    <div class="welcome-content">
        <h1>Selamat Datang, Kesiswaan</h1>
        <p>Panel Informasi Kesiswaan SMKN 2 Yogyakarta</p>
    </div>
</div>

{{-- 2. STATISTIK UTAMA (Ukuran Kecil) --}}
<div class="stats-grid">
    <div class="stat-card border-blue">
        <div class="stat-header">
            <div class="stat-value">
                <div class="stat-label">Total Siswa</div>
                <h3>{{ $totalSiswa }}</h3>
            </div>
            <div class="stat-icon" style="background:#eff6ff; color:#3b82f6;"><i class="fas fa-users"></i></div>
        </div>
    </div>

    <div class="stat-card border-orange">
        <div class="stat-header">
            <div class="stat-value">
                <div class="stat-label">Pelanggaran</div>
                <h3>{{ $totalKeterlambatan }}</h3>
            </div>
            <div class="stat-icon" style="background:#fff7ed; color:#f97316;"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>

    <div class="stat-card border-green">
        <div class="stat-header">
            <div class="stat-value">
                <div class="stat-label">Dokumen</div>
                <h3>{{ $sudahUpload }} <small style="font-size:11px; color:#94a3b8;">/{{ $totalSiswa }}</small></h3>
            </div>
            <div class="stat-icon" style="background:#f0fdf4; color:#10b981;"><i class="fas fa-file-alt"></i></div>
        </div>
        <div class="progress-wrapper">
            <div class="progress-info">
                <span style="color:#10b981;">{{ $persenSudah }}%</span>
                <span style="color:#ef4444;">{{ $belumUpload }} Sisa</span>
            </div>
            <div class="progress-bg">
                <div class="progress-fill" style="width: {{ $persenSudah }}%; background-color:#10b981;"></div>
            </div>
        </div>
    </div>
</div>

<div class="main-grid">
    <div class="left-column">
        {{-- GRAFIK JURUSAN --}}
        <div class="chart-card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-chart-bar"></i> Siswa Per Jurusan</div>
                <select id="filterAngkatan" class="filter-select">
                    <option value="all">Semua Angkatan</option>
                    @foreach($listAngkatan as $thn)
                        <option value="{{ $thn }}">Angkatan {{ $thn }}</option>
                    @endforeach
                </select>
            </div>
            <div style="position: relative; height: 300px;">
                <canvas id="chartJurusanHorizontal"></canvas>
            </div>
        </div>

        {{-- GRAFIK PRESTASI --}}
        <div class="chart-card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-trophy"></i> Tren Prestasi</div>
                <form action="{{ url()->current() }}" method="GET">
                    @foreach(request()->except(['year_prestasi']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <select name="year_prestasi" onchange="this.form.submit()" class="filter-select" style="background:#fffbeb; border-color:#fcd34d; color:#b45309;">
                        @foreach($listTahunPrestasi as $thn)
                            <option value="{{ $thn }}" {{ $tahunPilihan == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div style="position: relative; height: 200px;">
                <canvas id="chartPrestasi"></canvas>
            </div>
        </div>
    </div>

    <div class="right-column">
        <div class="chart-card" style="height: 100%;">
            <div class="card-header">
                <div class="card-title" style="color:#dc2626;"><i class="fas fa-bell"></i> Terlambat</div>
                <a href="{{ route('admin.keterlambatan.index') }}" style="font-size:11px; text-decoration:none; color:#64748b;">Semua</a>
            </div>
            <div class="notif-list">
                @forelse($keterlambatanTerbaru as $t)
                    <a href="{{ route('admin.keterlambatan.index') }}" class="notif-item">
                        <div class="notif-icon"><i class="fas fa-clock"></i></div>
                        <div class="notif-info">
                            <h6>{{ Str::limit($t->siswa->nama_lengkap ?? 'Siswa', 20) }}</h6>
                            <span>{{ $t->created_at->format('d/m, H:i') }}</span>
                        </div>
                    </a>
                @empty
                    <div style="text-align:center; padding:20px; color:#94a3b8; font-size:12px;">Kosong</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // Fungsi Menyingkat Nama Jurusan (DIUBAH AGAR PERSIS SEPERTI DASHBOARD ADMIN)
    function singkatJurusan(name) {
        return name
            .replace(/Teknik/g, 'T.')
            .replace(/dan/g, '&')
            .split(' ')
            .map(word => word.length > 3 ? word[0] : word)
            .join('')
            .toUpperCase();
    }

    const rawDataJurusan = {!! json_encode($dataJurusan) !!};
    const ctxJurusan = document.getElementById('chartJurusanHorizontal').getContext('2d');
    let chartJurusan;

    function renderJurusanChart(filteredData) {
        let grouped = {};
        filteredData.forEach(item => {
            let labelSingkat = singkatJurusan(item.jurusan);
            if (!grouped[labelSingkat]) grouped[labelSingkat] = 0;
            grouped[labelSingkat] += parseInt(item.total);
        });

        let chartArray = Object.keys(grouped).map(key => ({ jurusan: key, total: grouped[key] }));
        chartArray.sort((a, b) => b.total - a.total);

        const labels = chartArray.map(i => i.jurusan);
        const values = chartArray.map(i => i.total);

        if (chartJurusan) chartJurusan.destroy();

        chartJurusan = new Chart(ctxJurusan, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Siswa',
                    data: values,
                    backgroundColor: 'rgba(30, 58, 138, 0.25)',
                    borderColor: '#1e3a8a',
                    borderWidth: 2,
                    borderRadius: 4,
                    barThickness: 20
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, ticks: { font: { size: 10 } } },
                    y: { ticks: { font: { weight: '600', size: 11 } } }
                }
            }
        });
    }

    renderJurusanChart(rawDataJurusan);
    document.getElementById('filterAngkatan').addEventListener('change', function() {
        const val = this.value;
        const filtered = (val === 'all') ? rawDataJurusan : rawDataJurusan.filter(i => i.angkatan == val);
        renderJurusanChart(filtered);
    });

    // Chart Prestasi
    const ctxPrestasi = document.getElementById('chartPrestasi').getContext('2d');
    new Chart(ctxPrestasi, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Prestasi',
                data: {!! json_encode($prestasiData) !!},
                borderColor: '#eab308',
                backgroundColor: 'rgba(234, 179, 8, 0.1)',
                borderWidth: 2,
                pointRadius: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } } },
                x: { ticks: { font: { size: 10 } } }
            }
        }
    });
});
</script>

@endsection