@extends('layouts.admin')

@section('title', 'Presensi Kelas ' . $rombel)
@section('page_title', 'Presensi Kelas ' . $rombel)

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    * { font-family: 'Poppins', sans-serif; }
    .card-custom { background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; }

    .header-prestasi {
        background-color: #123B6B; color: white; display: flex;
        justify-content: space-between; align-items: flex-start;
        padding: 20px 25px; flex-wrap: wrap; gap: 15px;
    }
    .header-prestasi h4 { margin: 0 0 4px; font-weight: 600; font-size: 1.2rem; }
    .header-sub { font-size: 12.5px; opacity: 0.9; line-height: 1.6; }
    .header-prestasi .filter-select { min-width: 160px; }
    .filter-select { padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; font-family: 'Poppins', sans-serif; color: #333; outline: none; }

    .alert-box { margin: 20px 25px 0; padding: 12px 16px; border-radius: 8px; font-size: 14px; }
    .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    .siswa-list { padding: 20px 25px; display: flex; flex-direction: column; gap: 10px; }

    .siswa-row {
        display: flex; align-items: center; gap: 14px; padding: 12px 16px;
        border: 1px solid #eee; border-radius: 10px; flex-wrap: wrap; transition: 0.2s;
    }
    .siswa-row:hover { background: #f9fafb; }

    .siswa-info { flex: 1; min-width: 160px; display: flex; align-items: center; gap: 10px; }
    .avatar-circle { width: 34px; height: 34px; border-radius: 50%; background: #eef1fb; color: #123B6B; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; flex: 0 0 auto; }
    .siswa-nama { font-weight: 600; font-size: 13.5px; color: #222; }
    .siswa-nis { font-size: 11.5px; color: #94a3b8; }

    /* PILL STATUS SELECTOR */
    .status-selector { display: flex; gap: 6px; flex-wrap: wrap; }
    .status-opt {
        position: relative; padding: 6px 13px; border-radius: 20px; font-size: 12px; font-weight: 600;
        cursor: pointer; border: 1.5px solid #e2e8f0; color: #94a3b8; background: #fff; transition: 0.15s; user-select: none;
    }
    .status-opt input { position: absolute; opacity: 0; width: 0; height: 0; }
    .status-opt.opt-hadir:has(input:checked), .status-opt.opt-hadir.selected { background: #dcfce7; border-color: #16a34a; color: #166534; }
    .status-opt.opt-sakit:has(input:checked), .status-opt.opt-sakit.selected { background: #fef3c7; border-color: #d97706; color: #92400e; }
    .status-opt.opt-izin:has(input:checked), .status-opt.opt-izin.selected  { background: #dbeafe; border-color: #2563eb; color: #1e40af; }
    .status-opt.opt-alpa:has(input:checked), .status-opt.opt-alpa.selected  { background: #fee2e2; border-color: #dc2626; color: #991b1b; }
    .status-opt:hover { border-color: #cbd5e1; }

    .bukti-cell { flex: 0 0 auto; }
    .link-file { color: #1e3a8a; text-decoration: none; font-weight: 600; font-size: 12px; white-space: nowrap; }
    .link-file:hover { text-decoration: underline; }
    .no-bukti { color: #cbd5e1; font-size: 12px; }

    .btn-simpan {
        background-color: #123B6B; color: white; border: none;
        padding: 13px 20px; border-radius: 10px; cursor: pointer;
        font-weight: 600; font-size: 14px; width: 100%; margin-top: 5px;
        transition: 0.2s;
    }
    .btn-simpan:hover { background-color: #0f2e52; }

    .back-link { display: block; text-align: center; padding: 15px 0 20px; color: #123B6B; font-size: 13px; text-decoration: none; font-weight: 600; }
    .back-link:hover { text-decoration: underline; }

    @media (max-width: 768px) {
        .header-prestasi { flex-direction: column; text-align: center; }
        .siswa-list { padding: 16px; }
        .siswa-row { flex-direction: column; align-items: stretch; text-align: center; }
        .siswa-info { justify-content: center; }
        .status-selector { justify-content: center; }
        .bukti-cell { text-align: center; }
    }
</style>

<div class="card-custom">

    <div class="header-prestasi">
        <div>
            <h4>Kelas {{ $rombel }}</h4>
            <div class="header-sub">
                @if ($sesi)
                    Diinput oleh koor: <strong>{{ $sesi->koor->nama_lengkap ?? 'Admin/Kesiswaan langsung' }}</strong>
                    @if ($sesi->diedit_oleh)
                        <br>Terakhir diedit: {{ $sesi->diedit_oleh }} @if($sesi->diedit_at) ({{ $sesi->diedit_at->translatedFormat('d M Y H:i') }}) @endif
                    @endif
                @else
                    Belum ada data presensi untuk tanggal ini — kamu bisa input dari sini.
                @endif
            </div>
        </div>
        <form method="GET">
            <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()" class="filter-select">
        </form>
    </div>

    @if (session('success'))
        <div class="alert-box alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-box alert-error">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.presensi.simpan', $rombel) }}" method="POST">
        @csrf
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

        <div class="siswa-list">
            @foreach ($siswaSekelas as $s)
                @php $rec = $existing[$s->nis] ?? null; $current = $rec->status ?? 'hadir'; @endphp
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
                                <input type="radio" name="status[{{ $s->nis }}]" value="{{ $opt }}" {{ $current === $opt ? 'checked' : '' }} required>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>

                    <div class="bukti-cell">
                        @if ($rec && $rec->bukti_path)
                            <a href="{{ asset('storage/' . $rec->bukti_path) }}" target="_blank" class="link-file"><i class="fa-solid fa-paperclip"></i> Lihat Bukti</a>
                        @else
                            <span class="no-bukti">Tidak ada bukti</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div style="padding: 0 25px 25px;">
            <button type="submit" class="btn-simpan"><i class="fas fa-save"></i> Simpan Presensi</button>
        </div>
    </form>

    <a href="{{ route('admin.presensi.dashboard') }}" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard Presensi</a>
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
