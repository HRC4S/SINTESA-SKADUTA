@extends('layouts.siswa')

@section('title', 'Presensi Saya')
@section('page_title', 'Presensi Saya')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    :root {
        --primary: #1e3a8a; --primary-hover: #172554; --secondary: #64748b;
        --bg-light: #f1f5f9; --white: #ffffff;
        --shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        --radius: 16px;
    }
    body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); }

    .dashboard-container { max-width: 1200px; margin: 0 auto; padding-bottom: 50px; }

    .header-card {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        padding: 30px; border-radius: var(--radius); color: var(--white);
        display: flex; justify-content: space-between; align-items: center;
        box-shadow: var(--shadow); margin-bottom: 30px; position: relative; overflow: hidden;
    }
    .header-card::before { content: ''; position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; }
    .user-info h2 { font-size: 24px; font-weight: 700; margin: 0; }
    .user-info p { font-size: 14px; opacity: 0.9; margin-top: 5px; }
    .header-icon i { font-size: 60px; color: rgba(255,255,255,0.8); filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1)); transform: rotate(-10deg); display: inline-block; }

    .filter-bar { display: flex; gap: 10px; }
    .filter-bar select {
        padding: 8px 14px; border-radius: 10px; border: none; font-size: 13px;
        font-family: 'Poppins', sans-serif; background: rgba(255,255,255,0.15); color: #fff;
    }
    .filter-bar select option { color: #222; }

    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px; margin-bottom: 30px; }
    .stat-card { background: var(--white); padding: 20px; border-radius: var(--radius); box-shadow: var(--shadow); text-align: center; }
    .stat-card h3 { margin: 0; font-size: 26px; font-weight: 700; }
    .stat-card p { margin: 6px 0 0; font-size: 13px; color: var(--secondary); font-weight: 500; }
    .stat-hadir h3 { color: #16a34a; }
    .stat-sakit h3 { color: #d97706; }
    .stat-izin h3 { color: #2563eb; }
    .stat-alpa h3 { color: #dc2626; }

    .content-card { background: var(--white); border-radius: var(--radius); padding: 25px; box-shadow: var(--shadow); }
    .content-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
    .content-title { font-size: 18px; font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 10px; }

    .alert-flash { padding: 12px 16px; border-radius: 10px; font-size: 14px; margin-bottom: 18px; }
    .alert-success { background: #dcfce7; color: #166534; }
    .alert-error { background: #fee2e2; color: #991b1b; }

    .table-responsive { width: 100%; overflow-x: auto; }
    .custom-table { width: 100%; border-collapse: collapse; min-width: 500px; }
    .custom-table thead { background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; }
    .custom-table th { text-align: left; padding: 14px 16px; font-size: 12.5px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
    .custom-table td { padding: 14px 16px; vertical-align: middle; font-size: 14px; color: #334155; border-bottom: 1px solid #f1f5f9; }
    .custom-table tr:hover { background-color: #f8fafc; }

    .badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
    .badge-hadir { background: #dcfce7; color: #166534; }
    .badge-sakit { background: #fef3c7; color: #92400e; }
    .badge-izin { background: #dbeafe; color: #1e40af; }
    .badge-alpa { background: #fee2e2; color: #991b1b; }

    .btn-upload {
        background: var(--primary); color: #fff; border: none; padding: 7px 14px;
        border-radius: 8px; font-size: 12.5px; font-weight: 600; cursor: pointer;
    }
    .btn-upload:hover { background: var(--primary-hover); }
    .btn-link { font-size: 12.5px; font-weight: 600; color: var(--primary); text-decoration: none; margin-right: 8px; }
    .btn-link:hover { text-decoration: underline; }
    .empty-state { text-align: center; padding: 40px; color: #94a3b8; }
    .empty-state i { font-size: 30px; margin-bottom: 10px; display: block; }

    /* ===== MODAL ===== */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); display: none; justify-content: center; align-items: center; z-index: 9999; }
    .modal-overlay.active { display: flex; }
    .modal-box { background: var(--white); width: 90%; max-width: 420px; border-radius: 20px; padding: 26px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
    .modal-title { font-size: 16px; font-weight: 700; color: var(--primary); margin: 0; }
    .btn-close { background: #f1f5f9; border: none; font-size: 18px; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; color: var(--secondary); }
    .btn-close:hover { background: #e2e8f0; }
    .form-control { width: 100%; padding: 10px 15px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px; font-family: inherit; box-sizing: border-box; margin-bottom: 6px; }
    .form-control:focus { border-color: var(--primary); outline: none; }
    .form-hint { font-size: 12px; color: var(--secondary); margin-bottom: 16px; }
    .btn-submit { width: 100%; background: var(--primary); color: white; padding: 11px; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; }
    .btn-submit:hover { background: var(--primary-hover); }

    @media (max-width: 768px) {
        .header-card { flex-direction: column; text-align: center; gap: 15px; padding: 20px; }
        .filter-bar { justify-content: center; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .content-header { flex-direction: column; align-items: stretch; }
    }
</style>

<div class="dashboard-container">

    <div class="header-card">
        <div class="user-info">
            <h2>Presensi Saya</h2>
            <p><i class="bi bi-calendar-check"></i> {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</p>
        </div>
        <form method="GET" class="filter-bar">
            <select name="bulan" onchange="this.form.submit()">
                @foreach (range(1,12) as $m)
                    <option value="{{ $m }}" {{ $m == $bulan ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                @endforeach
            </select>
            <select name="tahun" onchange="this.form.submit()">
                @foreach (range(now()->year, now()->year - 2) as $y)
                    <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-hadir"><h3>{{ $rekap['hadir'] }}</h3><p>Hadir</p></div>
        <div class="stat-card stat-sakit"><h3>{{ $rekap['sakit'] }}</h3><p>Sakit</p></div>
        <div class="stat-card stat-izin"><h3>{{ $rekap['izin'] }}</h3><p>Izin</p></div>
        <div class="stat-card stat-alpa"><h3>{{ $rekap['alpa'] }}</h3><p>Alpa</p></div>
    </div>

    <div class="content-card">
        <div class="content-header">
            <div class="content-title"><i class="bi bi-clock-history"></i> Riwayat Presensi</div>
        </div>

        @if (session('success'))
            <div class="alert-flash alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-flash alert-error">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr><th>Tanggal</th><th>Status</th><th>Bukti</th></tr>
                </thead>
                <tbody>
                    @forelse ($presensis as $p)
                        <tr>
                            <td>{{ $p->tanggal->translatedFormat('d F Y') }}</td>
                            <td><span class="badge badge-{{ $p->status }}">{{ $p->label_status }}</span></td>
                            <td>
                                @if ($p->wajibBukti())
                                    @if ($p->sudahUploadBukti())
                                        <a href="{{ asset('storage/' . $p->bukti_path) }}" target="_blank" class="btn-link"><i class="bi bi-eye"></i> Lihat</a>
                                        <button type="button" class="btn-link" style="border:none;background:none;cursor:pointer;" onclick="openModal('modal-{{ $p->id }}')">Ganti</button>
                                    @else
                                        <button type="button" class="btn-upload" onclick="openModal('modal-{{ $p->id }}')">Upload Bukti</button>
                                    @endif

                                    <div class="modal-overlay" id="modal-{{ $p->id }}">
                                        <div class="modal-box">
                                            <div class="modal-header">
                                                <h3 class="modal-title">Upload Bukti — {{ $p->tanggal->translatedFormat('d F Y') }}</h3>
                                                <button type="button" class="btn-close" onclick="closeModal('modal-{{ $p->id }}')">&times;</button>
                                            </div>
                                            <form action="{{ route('siswa.presensi.uploadBukti', $p->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf" required class="form-control">
                                                <p class="form-hint">Format JPG/PNG/PDF, maksimal 2 MB.</p>
                                                <button type="submit" class="btn-submit">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <span style="color:#cbd5e1;">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3"><div class="empty-state"><i class="bi bi-calendar-x"></i>Belum ada data presensi bulan ini.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
document.querySelectorAll('.modal-overlay').forEach(function (ov) {
    ov.addEventListener('click', function (e) { if (e.target === ov) ov.classList.remove('active'); });
});
</script>
@endsection
