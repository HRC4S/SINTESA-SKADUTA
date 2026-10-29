@extends('layouts.admin')

@section('title', 'Rekap Bulanan Presensi')
@section('page_title', 'Rekap Bulanan Presensi')

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
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 15px;
    }
    .filter-form { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .filter-select { padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; font-family: 'Poppins', sans-serif; color: #333; outline: none; }
    .filter-select:focus { border-color: #123B6B; }
    .back-link { color: #123B6B; font-size: 13px; text-decoration: none; font-weight: 600; }
    .back-link:hover { text-decoration: underline; }
    .btn-export {
        background-color: #16a34a; color: #fff; border: none; padding: 10px 18px; border-radius: 8px;
        font-weight: 600; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 7px;
        transition: 0.2s; white-space: nowrap;
    }
    .btn-export:hover { background-color: #15803d; }

    .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; padding: 20px 25px; }
    table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
    thead { background-color: #2c3e50; color: white; }
    th, td { padding: 8px 6px; border-bottom: 1px solid #eee; text-align: center; }
    th:first-child, td:first-child { text-align: left; position: sticky; left: 0; background: #2c3e50; z-index: 2; padding-left: 12px; }
    td:first-child { background: #fff; color: #222; font-weight: 500; white-space: nowrap; }
    tbody tr:hover td:not(:first-child) { background-color: #f9f9f9; }
    tbody tr:hover td:first-child { background-color: #f4f7f6; }

    .badge-h { background: #16a34a; }
    .badge-s { background: #d97706; }
    .badge-i { background: #2563eb; }
    .badge-a { background: #dc2626; }
    .badge-day { display: inline-block; width: 18px; height: 18px; line-height: 18px; border-radius: 4px; color: #fff; font-size: 10px; font-weight: 700; }
    .dash { color: #ccc; }

    .col-total-h { background: #f0fdf4; font-weight: 700; color: #166534; }
    .col-total-s { background: #fffbeb; font-weight: 700; color: #92400e; }
    .col-total-i { background: #eff6ff; font-weight: 700; color: #1e40af; }
    .col-total-a { background: #fef2f2; font-weight: 700; color: #991b1b; }

    .legend { padding: 0 25px 20px; font-size: 12px; color: #888; }

    .empty-state { text-align: center; padding: 40px; color: #777; }

    @media (max-width: 768px) {
        .header-prestasi { flex-direction: column; text-align: center; }
        .rekap-filter-wrapper { flex-direction: column; align-items: stretch; }
        .filter-form { flex-direction: column; width: 100%; }
        .filter-select { width: 100%; }
        .rekap-filter-wrapper > div { flex-direction: column; align-items: stretch !important; width: 100%; }
        .btn-export { justify-content: center; }
    }
</style>

<div class="card-custom">
    <div class="header-prestasi">
        <div>
            <h4><i class="fas fa-calendar-alt"></i> Rekap Bulanan Presensi</h4>
            <p style="margin:4px 0 0; font-size:12.5px; opacity:0.85;">Lihat kehadiran satu kelas penuh selama satu bulan.</p>
        </div>
    </div>

    <div class="rekap-filter-wrapper">
        <form method="GET" class="filter-form">
            <select name="rombel" onchange="this.form.submit()" class="filter-select">
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
        <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
            <a href="{{ route('admin.presensi.rekapBulanan.export', ['rombel' => $rombel, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
               class="btn-export">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ route('admin.presensi.dashboard') }}" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    @foreach ($tanggalList as $t)<th>{{ $t }}</th>@endforeach
                    <th class="col-total-h">H</th>
                    <th class="col-total-s">S</th>
                    <th class="col-total-i">I</th>
                    <th class="col-total-a">A</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswaSekelas as $s)
                    <tr>
                        <td>{{ $s->nama_lengkap }}</td>
                        @foreach ($tanggalList as $t)
                            @php $st = $grid[$s->nis][$t] ?? null; @endphp
                            <td>
                                @if ($st === 'hadir') <span class="badge-day badge-h">H</span>
                                @elseif ($st === 'sakit') <span class="badge-day badge-s">S</span>
                                @elseif ($st === 'izin') <span class="badge-day badge-i">I</span>
                                @elseif ($st === 'alpa') <span class="badge-day badge-a">A</span>
                                @else <span class="dash">·</span>
                                @endif
                            </td>
                        @endforeach
                        <td class="col-total-h">{{ $totalPerSiswa[$s->nis]['hadir'] }}</td>
                        <td class="col-total-s">{{ $totalPerSiswa[$s->nis]['sakit'] }}</td>
                        <td class="col-total-i">{{ $totalPerSiswa[$s->nis]['izin'] }}</td>
                        <td class="col-total-a">{{ $totalPerSiswa[$s->nis]['alpa'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="100" class="empty-state">Tidak ada siswa di kelas ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p class="legend">H = Hadir, S = Sakit, I = Izin, A = Alpa. Tanda (·) artinya tidak ada data presensi tanggal itu.</p>
</div>
@endsection
