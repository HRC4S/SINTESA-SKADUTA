@extends('layouts.admin')

@section('title', 'Rekap Bulanan Keterlambatan')
@section('page_title', 'Rekap Bulanan Keterlambatan & Izin Kelas')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    * { font-family: 'Poppins', sans-serif; }
    .card-custom { background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; }

    .header-prestasi { background-color: #123B6B; color: white; display: flex; justify-content: space-between; align-items: center; padding: 20px 25px; flex-wrap: wrap; gap: 12px; }
    .header-prestasi h4 { margin: 0; font-weight: 600; font-size: 1.2rem; }
    .header-prestasi p { margin: 4px 0 0; font-size: 12.5px; opacity: 0.85; }

    .rekap-filter-wrapper { background: #f8f9fa; padding: 18px 25px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
    .filter-form { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .filter-select { padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; font-family: 'Poppins', sans-serif; color: #333; outline: none; }
    .filter-select:focus { border-color: #123B6B; }
    .back-link { color: #123B6B; font-size: 13px; text-decoration: none; font-weight: 600; }
    .back-link:hover { text-decoration: underline; }

    .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; padding: 20px 25px 25px; }
    table { width: 100%; border-collapse: collapse; min-width: 680px; }
    thead { background-color: #2c3e50; color: white; }
    th, td { padding: 12px 14px; border-bottom: 1px solid #eee; text-align: left; font-size: 13.5px; vertical-align: middle; }
    th.center, td.center { text-align: center; }
    tbody tr:hover { background-color: #f9fafb; }
    tbody tr.warn { background-color: #fff7ed; }
    tbody tr.warn:hover { background-color: #ffedd5; }

    .col-masuk { background: #eff6ff; color: #1e40af; font-weight: 700; }
    .col-keluar { background: #fff7ed; color: #92400e; font-weight: 700; }
    .col-total { font-weight: 700; color: #123B6B; }

    .warn-badge {
        background: #fee2e2; color: #991b1b; font-size: 10.5px; font-weight: 700;
        padding: 3px 9px; border-radius: 12px; margin-left: 6px; white-space: nowrap;
    }

    .empty-state { text-align: center; padding: 40px; color: #777; }

    @media (max-width: 768px) {
        .header-prestasi { flex-direction: column; text-align: center; }
        .rekap-filter-wrapper { flex-direction: column; align-items: stretch; }
        .filter-form { flex-direction: column; width: 100%; }
        .filter-select { width: 100%; }
    }
</style>

<div class="card-custom">
    <div class="header-prestasi">
        <div>
            <h4><i class="fas fa-calendar-alt"></i> Rekap Bulanan Keterlambatan &amp; Izin Kelas</h4>
            <p>Hanya menghitung pengajuan yang sudah <strong>disetujui</strong>. Siswa dengan izin masuk (terlambat) 2x atau lebih ditandai.</p>
        </div>
    </div>

    <div class="rekap-filter-wrapper">
        <form method="GET" class="filter-form">
            <select name="rombel" onchange="this.form.submit()" class="filter-select">
                <option value="">Semua Kelas</option>
                @foreach ($rombelList as $r)
                    <option value="{{ $r }}" {{ $r == $rombel ? 'selected' : '' }}>{{ $r }}</option>
                @endforeach
            </select>
            <select name="bulan" onchange="this.form.submit()" class="filter-select">
                @foreach (range(1,12) as $m)
                    <option value="{{ $m }}" {{ $m == $bulan ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                @endforeach
            </select>
            <select name="tahun" onchange="this.form.submit()" class="filter-select">
                @foreach (range(now()->year, now()->year - 2) as $y)
                    <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.keterlambatan.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Daftar</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th class="center col-masuk">Izin Masuk (Terlambat)</th>
                    <th class="center col-keluar">Izin Keluar</th>
                    <th class="center">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswaList as $s)
                    @php
                        $r = $rekap[$s->nis] ?? ['masuk' => 0, 'keluar' => 0];
                        $total = $r['masuk'] + $r['keluar'];
                        $peringatan = $r['masuk'] >= 2;
                    @endphp
                    @if ($total > 0)
                        <tr class="{{ $peringatan ? 'warn' : '' }}">
                            <td>
                                {{ $s->nama_lengkap }}
                                @if ($peringatan)
                                    <span class="warn-badge"><i class="fas fa-triangle-exclamation"></i> {{ $r['masuk'] }}x Terlambat</span>
                                @endif
                            </td>
                            <td>{{ $s->rombel }}</td>
                            <td class="center col-masuk">{{ $r['masuk'] }}</td>
                            <td class="center col-keluar">{{ $r['keluar'] }}</td>
                            <td class="center col-total">{{ $total }}</td>
                        </tr>
                    @endif
                @empty
                @endforelse
            </tbody>
        </table>

        @if ($siswaList->every(fn($s) => (($rekap[$s->nis]['masuk'] ?? 0) + ($rekap[$s->nis]['keluar'] ?? 0)) == 0))
            <div class="empty-state">
                <i class="far fa-folder-open" style="font-size:28px; display:block; margin-bottom:8px; color:#ccc;"></i>
                Tidak ada data izin/keterlambatan yang disetujui pada bulan ini.
            </div>
        @endif
    </div>
</div>
@endsection
