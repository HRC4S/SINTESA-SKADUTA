@extends('layouts.admin')

@section('title', 'Rekap Total Presensi')
@section('page_title', 'Rekap Total Presensi')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    .card-custom { background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; }

    .header-prestasi {
        background-color: #123B6B; color: white; display: flex;
        justify-content: space-between; align-items: center;
        padding: 20px 25px; flex-wrap: wrap; gap: 12px;
    }
    .header-prestasi h4 { margin: 0; font-weight: 600; font-size: 1.2rem; }

    .rekap-filter-wrapper {
        background: #f8f9fa; padding: 18px 25px; border-bottom: 1px solid #eee;
        display: flex; justify-content: space-between; align-items: flex-end;
        flex-wrap: wrap; gap: 15px;
    }
    .filter-form { display: flex; align-items: flex-end; gap: 12px; flex-wrap: wrap; }
    .filter-group label { display: block; font-size: 12px; color: #777; margin-bottom: 5px; }
    .filter-select, .filter-date {
        padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px;
        font-size: 14px; font-family: 'Poppins', sans-serif; color: #333; outline: none;
    }
    .filter-select:focus, .filter-date:focus { border-color: #123B6B; }
    .btn-filter { background-color: #123B6B; color: white; border: none; padding: 11px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px; }
    .btn-filter:hover { background-color: #0f2e52; }
    .back-link { color: #123B6B; font-size: 13px; text-decoration: none; font-weight: 600; }
    .back-link:hover { text-decoration: underline; }
    .btn-export {
        background-color: #16a34a; color: #fff; border: none; padding: 10px 18px; border-radius: 8px;
        font-weight: 600; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 7px;
        transition: 0.2s; white-space: nowrap;
    }
    .btn-export:hover { background-color: #15803d; }
    .hint { padding: 12px 25px 0; font-size: 12px; color: #888; }

    .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; padding: 15px 25px 25px; }
    table { width: 100%; border-collapse: collapse; min-width: 780px; }
    thead { background-color: #2c3e50; color: white; }
    th, td { padding: 12px 14px; border-bottom: 1px solid #eee; text-align: left; font-size: 14px; vertical-align: middle; }
    th.center, td.center { text-align: center; }
    tbody tr:hover { background-color: #f9f9f9; }

    .col-h { background: #f0fdf4; color: #166534; font-weight: 600; }
    .col-s { background: #fffbeb; color: #92400e; font-weight: 600; }
    .col-i { background: #eff6ff; color: #1e40af; font-weight: 600; }
    .col-a { background: #fef2f2; color: #991b1b; font-weight: 600; }

    .persen { font-weight: 700; }
    .persen.tinggi { color: #16a34a; }
    .persen.sedang { color: #d97706; }
    .persen.rendah { color: #dc2626; }

    .empty-state { text-align: center; padding: 40px; color: #777; }

    @media (max-width: 768px) {
        .header-prestasi { flex-direction: column; text-align: center; }
        .rekap-filter-wrapper { flex-direction: column; align-items: stretch; }
        .filter-form { flex-direction: column; align-items: stretch; }
        .filter-select, .filter-date, .btn-filter { width: 100%; }
        .rekap-filter-wrapper > div { flex-direction: column; align-items: stretch !important; width: 100%; }
        .btn-export { justify-content: center; }
    }
</style>

<div class="card-custom">
    <div class="header-prestasi">
        <div>
            <h4><i class="fas fa-chart-bar"></i> Rekap Total Presensi</h4>
            <p style="margin:4px 0 0; font-size:12.5px; opacity:0.85;">Lihat total kehadiran per siswa, bisa difilter kelas & rentang tanggal.</p>
        </div>
    </div>

    <div class="rekap-filter-wrapper">
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <label>Kelas</label>
                <select name="rombel" class="filter-select">
                    <option value="">Semua Kelas</option>
                    @foreach ($rombelList as $r)
                        <option value="{{ $r }}" {{ $r == $rombel ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Dari Tanggal</label>
                <input type="date" name="dari" value="{{ $dari }}" class="filter-date">
            </div>
            <div class="filter-group">
                <label>Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ $sampai }}" class="filter-date">
            </div>
            <button type="submit" class="btn-filter">Tampilkan</button>
        </form>
        <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
            <a href="{{ route('admin.presensi.rekapTotal.export', ['rombel' => $rombel, 'dari' => $dari, 'sampai' => $sampai]) }}"
               class="btn-export">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ route('admin.presensi.dashboard') }}" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
        </div>
    </div>

    <p class="hint">Kosongkan tanggal untuk menghitung total dari seluruh data yang tersimpan.</p>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th class="center">Total Hari</th>
                    <th class="center col-h">Hadir</th>
                    <th class="center col-s">Sakit</th>
                    <th class="center col-i">Izin</th>
                    <th class="center col-a">Alpa</th>
                    <th class="center">% Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswaList as $s)
                    @php
                        $r = $rekap->get($s->nis);
                        $totalHari = $r->total_hari ?? 0;
                        $persen = $totalHari > 0 ? round(($r->total_hadir / $totalHari) * 100, 1) : null;
                        $persenClass = $persen === null ? '' : ($persen >= 90 ? 'tinggi' : ($persen >= 75 ? 'sedang' : 'rendah'));
                    @endphp
                    <tr>
                        <td>{{ $s->nis }}</td>
                        <td>{{ $s->nama_lengkap }}</td>
                        <td>{{ $s->rombel }}</td>
                        <td class="center">{{ $totalHari }}</td>
                        <td class="center col-h">{{ $r->total_hadir ?? 0 }}</td>
                        <td class="center col-s">{{ $r->total_sakit ?? 0 }}</td>
                        <td class="center col-i">{{ $r->total_izin ?? 0 }}</td>
                        <td class="center col-a">{{ $r->total_alpa ?? 0 }}</td>
                        <td class="center persen {{ $persenClass }}">{{ $persen !== null ? $persen . '%' : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="empty-state">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
