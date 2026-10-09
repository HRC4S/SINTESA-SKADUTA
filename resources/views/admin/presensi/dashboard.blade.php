@extends('layouts.admin')

@section('title', 'Presensi Siswa')
@section('page_title', 'Presensi Siswa')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    * { font-family: 'Poppins', sans-serif; }
    .card-custom { background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; }

    .header-prestasi {
        background-color: #123B6B; color: white; display: flex;
        justify-content: space-between; align-items: center;
        padding: 20px 25px; flex-wrap: wrap; gap: 12px;
    }
    .header-prestasi h4 { margin: 0; font-weight: 600; font-size: 1.2rem; }
    .header-prestasi p { margin: 4px 0 0; font-size: 12.5px; opacity: 0.85; }

    /* ===== STAT SUMMARY ===== */
    .stat-row {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;
        padding: 22px 25px 0;
    }
    .stat-box { border-radius: 10px; padding: 16px 18px; display: flex; align-items: center; gap: 12px; }
    .stat-box.total { background: #eef1fb; }
    .stat-box.sudah { background: #f0fdf4; }
    .stat-box.belum { background: #fef2f2; }
    .stat-icon { width: 40px; height: 40px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #fff; flex: 0 0 auto; }
    .stat-box.total .stat-icon { background: #123B6B; }
    .stat-box.sudah .stat-icon { background: #16a34a; }
    .stat-box.belum .stat-icon { background: #dc2626; }
    .stat-num { font-size: 20px; font-weight: 700; color: #1e293b; line-height: 1.1; }
    .stat-label { font-size: 12px; color: #64748b; margin-top: 2px; }

    .presensi-toolbar {
        background: #f8f9fa; padding: 18px 25px; margin-top: 20px; border-top: 1px solid #eee; border-bottom: 1px solid #eee;
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 15px;
    }
    .toolbar-links { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn-tambah {
        border: 2px solid #123B6B; color: #123B6B; background-color: transparent;
        padding: 9px 18px; border-radius: 8px; text-decoration: none;
        font-weight: 600; font-size: 13px; transition: 0.3s;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-tambah:hover { background-color: #123B6B; color: #fff; }
    .btn-tambah.solid { background-color: #123B6B; color: #fff; }
    .btn-tambah.solid:hover { background-color: #0f2e52; }

    .filter-select {
        padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px;
        font-size: 14px; font-family: 'Poppins', sans-serif;
        color: #333; outline: none; transition: 0.2s;
    }
    .filter-select:focus { border-color: #123B6B; }

    .kelas-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
        gap: 18px; padding: 25px;
    }

    .kelas-card { border: 1px solid #eee; border-radius: 12px; padding: 18px; transition: 0.2s; background: #fff; position: relative; }
    .kelas-card:hover { box-shadow: 0 6px 16px rgba(0,0,0,0.08); transform: translateY(-2px); }
    .kelas-card.sudah { border-left: 4px solid #16a34a; }
    .kelas-card.belum { border-left: 4px solid #dc2626; }

    .kelas-card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; gap: 8px; }
    .kelas-nama-wrap { display: flex; align-items: center; gap: 10px; }
    .kelas-icon { width: 36px; height: 36px; border-radius: 9px; background: #eef1fb; color: #123B6B; display: flex; align-items: center; justify-content: center; font-size: 14px; flex: 0 0 auto; }
    .kelas-nama { font-weight: 700; font-size: 15px; color: #123B6B; margin: 0; }

    .status-pill { font-size: 10.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; white-space: nowrap; text-transform: uppercase; letter-spacing: 0.3px; }
    .status-pill.sudah { background: #dcfce7; color: #166534; }
    .status-pill.belum { background: #fee2e2; color: #991b1b; }

    .kelas-meta { font-size: 12.5px; color: #64748b; margin: 5px 0; padding-left: 46px; }
    .kelas-meta strong { color: #222; }
    .kelas-meta.kosong { color: #aaa; font-style: italic; }

    .kelas-link {
        display: inline-flex; align-items: center; gap: 6px; margin-top: 12px; margin-left: 46px;
        font-size: 12.5px; font-weight: 700; color: #123B6B; text-decoration: none;
        padding: 6px 0;
    }
    .kelas-link:hover { gap: 9px; }
    .kelas-link i { transition: 0.2s; }

    .empty-state { text-align: center; padding: 40px; color: #777; }
    .empty-state i { font-size: 30px; margin-bottom: 10px; color: #ccc; }

    @media (max-width: 900px) {
        .stat-row { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
        .header-prestasi { flex-direction: column; text-align: center; }
        .presensi-toolbar { flex-direction: column; align-items: stretch; }
        .toolbar-links { justify-content: center; }
        .presensi-toolbar form { display: flex; justify-content: center; }
        .kelas-grid { grid-template-columns: 1fr; padding: 16px; }
    }
</style>

@php
    $totalKelas = $data->count();
    $totalSudah = $data->where('sudah_diinput', true)->count();
    $totalBelum = $totalKelas - $totalSudah;
@endphp

<div class="card-custom">

    <div class="header-prestasi">
        <div>
            <h4><i class="fas fa-clipboard-check"></i> Presensi Siswa</h4>
            <p>Pantau status presensi harian tiap kelas.</p>
        </div>
        <div class="tanggal-jam" id="tanggal-jam"></div>
    </div>

    <div class="stat-row">
        <div class="stat-box total">
            <div class="stat-icon"><i class="fas fa-school"></i></div>
            <div><div class="stat-num">{{ $totalKelas }}</div><div class="stat-label">Total Kelas</div></div>
        </div>
        <div class="stat-box sudah">
            <div class="stat-icon"><i class="fas fa-check"></i></div>
            <div><div class="stat-num">{{ $totalSudah }}</div><div class="stat-label">Sudah Diinput</div></div>
        </div>
        <div class="stat-box belum">
            <div class="stat-icon"><i class="fas fa-exclamation"></i></div>
            <div><div class="stat-num">{{ $totalBelum }}</div><div class="stat-label">Belum Diinput</div></div>
        </div>
    </div>

    <div class="presensi-toolbar">
        <div class="toolbar-links">
            <a href="{{ route('admin.presensi.rekapBulanan') }}" class="btn-tambah solid">
                <i class="fas fa-calendar-alt"></i> Rekap Bulanan
            </a>
            <a href="{{ route('admin.presensi.rekapTotal') }}" class="btn-tambah">
                <i class="fas fa-chart-bar"></i> Rekap Total
            </a>
        </div>

        <form method="GET">
            <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()" class="filter-select">
        </form>
    </div>

    <div class="kelas-grid">
        @forelse ($data as $kelas)
            <div class="kelas-card {{ $kelas['sudah_diinput'] ? 'sudah' : 'belum' }}">
                <div class="kelas-card-top">
                    <div class="kelas-nama-wrap">
                        <div class="kelas-icon"><i class="fas fa-users"></i></div>
                        <p class="kelas-nama">{{ $kelas['rombel'] }}</p>
                    </div>
                    <span class="status-pill {{ $kelas['sudah_diinput'] ? 'sudah' : 'belum' }}">
                        {{ $kelas['sudah_diinput'] ? 'Sudah' : 'Belum' }}
                    </span>
                </div>

                <p class="kelas-meta">Koor Kelas: <strong>{{ $kelas['koor_nama'] ?? 'Belum ditentukan' }}</strong></p>

                @if ($kelas['sudah_diinput'])
                    <p class="kelas-meta">Diinput oleh: {{ $kelas['diinput_oleh'] ?? $kelas['diedit_oleh'] ?? '-' }}</p>
                @else
                    <p class="kelas-meta kosong">Belum ada data untuk tanggal ini.</p>
                @endif

                <a href="{{ route('admin.presensi.kelas', ['rombel' => $kelas['rombel'], 'tanggal' => $tanggal]) }}" class="kelas-link">
                    {{ $kelas['sudah_diinput'] ? 'Lihat / Edit' : 'Input Sekarang' }} <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        @empty
            <div class="empty-state" style="grid-column: 1/-1;">
                <i class="far fa-folder-open"></i><br>
                Belum ada data kelas (rombel).
            </div>
        @endforelse
    </div>
</div>

<script>
function updateClock() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const tanggal = now.toLocaleDateString('id-ID', options);
    const jam = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':');
    document.getElementById('tanggal-jam').innerHTML = `${tanggal}<br>${jam} WIB`;
}
setInterval(updateClock, 1000);
updateClock();
</script>
@endsection
