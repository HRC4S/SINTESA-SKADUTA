@extends('layouts.siswa')

@section('title', 'Riwayat Presensi Kelas')
@section('page_title', 'Riwayat Presensi Kelas')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    :root { --primary: #1e3a8a; --primary-hover: #172554; --secondary: #64748b; --bg-light: #f1f5f9; --white: #ffffff;
        --shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); --radius: 16px; }
    body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); }
    .dashboard-container { max-width: 900px; margin: 0 auto; padding-bottom: 50px; }

    .content-card { background: var(--white); border-radius: var(--radius); padding: 25px; box-shadow: var(--shadow); }
    .content-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
    .content-title { font-size: 18px; font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 10px; margin: 0; }
    .btn-link { font-size: 13px; font-weight: 600; color: var(--primary); text-decoration: none; }
    .btn-link:hover { text-decoration: underline; }

    .table-responsive { width: 100%; overflow-x: auto; }
    .custom-table { width: 100%; border-collapse: collapse; min-width: 500px; }
    .custom-table thead { background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; }
    .custom-table th { text-align: left; padding: 14px 16px; font-size: 12.5px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
    .custom-table td { padding: 14px 16px; vertical-align: middle; font-size: 14px; color: #334155; border-bottom: 1px solid #f1f5f9; }
    .custom-table tr:hover { background-color: #f8fafc; }
    .empty-state { text-align: center; padding: 40px; color: #94a3b8; }
    .empty-state i { font-size: 30px; margin-bottom: 10px; display: block; }
    .pagination-wrap { margin-top: 16px; }

    @media (max-width: 768px) { .content-header { flex-direction: column; align-items: stretch; text-align: center; } }
</style>

<div class="dashboard-container">
    <div class="content-card">
        <div class="content-header">
            <div class="content-title"><i class="bi bi-clock-history"></i> Riwayat Input Presensi</div>
            <a href="{{ route('siswa.presensi.koor.create') }}" class="btn-link"><i class="bi bi-arrow-left"></i> Kembali ke input presensi</a>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead><tr><th>Tanggal</th><th>Jumlah Siswa</th><th>Terakhir Diedit</th></tr></thead>
                <tbody>
                    @forelse ($riwayat as $r)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d F Y') }}</td>
                            <td>{{ $r->detail_count }} siswa</td>
                            <td>
                                {{ $r->diedit_oleh ?? '-' }}
                                @if ($r->diedit_at)
                                    <br><span style="font-size:12px; color:#94a3b8;">{{ \Carbon\Carbon::parse($r->diedit_at)->translatedFormat('d M Y H:i') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3"><div class="empty-state"><i class="bi bi-calendar-x"></i>Belum ada riwayat presensi.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">{{ $riwayat->links() }}</div>
    </div>
</div>
@endsection
