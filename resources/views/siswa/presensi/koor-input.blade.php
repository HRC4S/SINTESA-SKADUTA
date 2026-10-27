@extends('layouts.siswa')

@section('title', 'Input Presensi Kelas')
@section('page_title', 'Input Presensi Kelas')

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
    * { font-family: 'Poppins', sans-serif; }
    body { background-color: var(--bg-light); }
    .dashboard-container { max-width: 900px; margin: 0 auto; padding-bottom: 50px; }

    .header-card {
        background: linear-gradient(135deg, #17375d 0%, #102a48 100%);
        color: #fff; border-radius: 12px; padding: 25px 30px; margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(23, 55, 93, 0.2); position: relative; overflow: hidden;
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;
    }
    .header-card::before { content: ''; position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; }
    .user-info h2 { font-size: 20px; font-weight: 700; margin: 0; }
    .user-info p { font-size: 13px; opacity: 0.9; margin-top: 5px; }
    .header-card input[type=date] {
        padding: 9px 14px; border-radius: 10px; border: none; font-size: 13px;
        font-family: 'Poppins', sans-serif; background: rgba(255,255,255,0.15); color: #fff;
    }
    .header-card input[type=date]::-webkit-calendar-picker-indicator { filter: invert(1); }

    .alert-flash { padding: 14px 18px; border-radius: 12px; font-size: 13.5px; margin-bottom: 18px; }
    .alert-success { background: #dcfce7; color: #166534; }
    .alert-error { background: #fee2e2; color: #991b1b; }
    .alert-warning { background: #fef3c7; color: #92400e; }

    .content-card { background: var(--white); border-radius: var(--radius); padding: 22px; box-shadow: var(--shadow); }
    .content-title { font-size: 15.5px; font-weight: 700; color: var(--primary); margin: 0 0 5px; display: flex; align-items: center; gap: 8px; }
    .content-desc { font-size: 12.5px; color: var(--secondary); margin-bottom: 18px; }

    /* ===== STUDENT ROW (card, no horizontal scroll needed) ===== */
    .siswa-list { display: flex; flex-direction: column; gap: 10px; }
    .siswa-row {
        display: flex; align-items: center; gap: 14px; padding: 12px 14px;
        border: 1px solid #eef0f4; border-radius: 12px; flex-wrap: wrap; transition: 0.15s;
    }
    .siswa-row:hover { background: #f8fafc; }
    .siswa-info { flex: 1; min-width: 150px; display: flex; align-items: center; gap: 10px; }
    .avatar-circle { width: 34px; height: 34px; border-radius: 50%; background: #eef1fb; color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; flex: 0 0 auto; }
    .siswa-nama { font-weight: 600; font-size: 13.5px; color: #1e293b; }
    .siswa-nis { font-size: 11px; color: #94a3b8; }

    /* PILL STATUS SELECTOR (samain kayak admin) */
    .status-selector { display: flex; gap: 6px; flex-wrap: wrap; }
    .status-opt {
        position: relative; padding: 7px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;
        cursor: pointer; border: 1.5px solid #e2e8f0; color: #94a3b8; background: #fff; transition: 0.15s; user-select: none;
    }
    .status-opt input { position: absolute; opacity: 0; width: 0; height: 0; }
    .status-opt.opt-hadir:has(input:checked), .status-opt.opt-hadir.selected { background: #dcfce7; border-color: #16a34a; color: #166534; }
    .status-opt.opt-sakit:has(input:checked), .status-opt.opt-sakit.selected { background: #fef3c7; border-color: #d97706; color: #92400e; }
    .status-opt.opt-izin:has(input:checked), .status-opt.opt-izin.selected  { background: #dbeafe; border-color: #2563eb; color: #1e40af; }
    .status-opt.opt-alpa:has(input:checked), .status-opt.opt-alpa.selected  { background: #fee2e2; border-color: #dc2626; color: #991b1b; }
    .status-opt:active { transform: scale(0.96); }

    .badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
    .badge-hadir { background: #dcfce7; color: #166534; }
    .badge-sakit { background: #fef3c7; color: #92400e; }
    .badge-izin { background: #dbeafe; color: #1e40af; }
    .badge-alpa { background: #fee2e2; color: #991b1b; }

    .btn-submit {
        width: 100%; background: var(--primary); color: white; padding: 14px; border: none;
        border-radius: 12px; font-size: 15px; font-weight: 600; cursor: pointer; margin-top: 18px;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-submit:hover { background: var(--primary-hover); }

    .link-riwayat { display: block; text-align: center; margin-top: 14px; color: var(--primary); font-size: 13px; font-weight: 600; text-decoration: none; }
    .link-riwayat:hover { text-decoration: underline; }

    .progress-hint { font-size: 12px; color: var(--secondary); text-align: center; margin-top: 10px; }

    /* ===== MOBILE: jadi kartu penuh, tombol besar dan gampang dipencet ===== */
    @media (max-width: 640px) {
        .header-card { flex-direction: column; text-align: center; padding: 18px; }
        .content-card { padding: 16px; border-radius: 14px; }
        .siswa-row { flex-direction: column; align-items: stretch; text-align: center; padding: 14px 12px; }
        .siswa-info { justify-content: center; }
        .status-selector { justify-content: center; }
        .status-opt { flex: 1 1 40%; text-align: center; padding: 10px 8px; font-size: 12.5px; }
    }
</style>

<div class="dashboard-container">

    <div class="header-card">
        <div class="user-info">
            <h2><i class="bi bi-clipboard-check"></i> Presensi Kelas {{ $rombel }}</h2>
            <p>Koor: {{ $koor->nama_lengkap }} (NIS {{ $koor->nis }})</p>
        </div>
        <form method="GET">
            <input type="date" name="tanggal" value="{{ $tanggal }}" max="{{ now()->toDateString() }}" onchange="this.form.submit()">
        </form>
    </div>

    @if (session('success'))
        <div class="alert-flash alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-flash alert-error">{{ session('error') }}</div>
    @endif

    <div class="content-card">
        @if ($sudahDiinput)
            <div class="alert-flash alert-warning">
                Presensi tanggal <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</strong>
                sudah diinput dan <strong>tidak bisa diubah</strong> dari sini. Kalau ada kesalahan, hubungi Admin/Kesiswaan.
            </div>

            <div class="siswa-list">
                @foreach ($siswaSekelas as $s)
                    @php $st = $existingStatus[$s->nis] ?? null; @endphp
                    <div class="siswa-row">
                        <div class="siswa-info">
                            <div class="avatar-circle">{{ strtoupper(substr($s->nama_lengkap, 0, 1)) }}</div>
                            <div>
                                <div class="siswa-nama">{{ $s->nama_lengkap }}</div>
                                <div class="siswa-nis">NIS {{ $s->nis }}</div>
                            </div>
                        </div>
                        <div>
                            @if ($st) <span class="badge badge-{{ $st }}">{{ ucfirst($st) }}</span> @else - @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="content-title"><i class="bi bi-check2-square"></i> Isi Presensi Hari Ini</p>
            <p class="content-desc">Ketuk status tiap siswa. Default: <strong>Hadir</strong>. Presensi ini hanya bisa disimpan <strong>sekali</strong> untuk tanggal ini.</p>

            <form action="{{ route('siswa.presensi.koor.store') }}" method="POST" id="form-presensi"
                  onsubmit="return confirm('Yakin? Presensi hari ini hanya bisa disimpan sekali dan tidak bisa diubah lagi olehmu.')">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                <div class="siswa-list">
                    @foreach ($siswaSekelas as $s)
                        <div class="siswa-row">
                            <div class="siswa-info">
                                <div class="avatar-circle">{{ strtoupper(substr($s->nama_lengkap, 0, 1)) }}</div>
                                <div>
                                    <div class="siswa-nama">{{ $s->nama_lengkap }}</div>
                                    <div class="siswa-nis">NIS {{ $s->nis }}</div>
                                </div>
                            </div>

                            <div class="status-selector">
                                @foreach (['hadir' => 'Hadir', 'sakit' => 'Sakit', 'izin' => 'Izin', 'alpa' => 'Alpa'] as $opt => $label)
                                    <label class="status-opt opt-{{ $opt }}">
                                        <input type="radio" name="status[{{ $s->nis }}]" value="{{ $opt }}" {{ $opt === 'hadir' ? 'checked' : '' }} required>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <p class="progress-hint">{{ $siswaSekelas->count() }} siswa di kelas {{ $rombel }}</p>

                <button type="submit" class="btn-submit"><i class="bi bi-save"></i> Simpan Presensi</button>
            </form>
        @endif

        <a href="{{ route('siswa.presensi.koor.riwayat') }}" class="link-riwayat">Lihat riwayat presensi yang pernah diinput</a>
    </div>
</div>

<script>
// Fallback untuk browser yang belum dukung CSS :has()
document.querySelectorAll('.status-selector').forEach(function (group) {
    const opts = group.querySelectorAll('.status-opt');
    opts.forEach(function (opt) {
        const input = opt.querySelector('input');
        if (input.checked) opt.classList.add('selected');
        input.addEventListener('change', function () {
            opts.forEach(o => o.classList.remove('selected'));
            opt.classList.add('selected');
        });
    });
});
</script>
@endsection
