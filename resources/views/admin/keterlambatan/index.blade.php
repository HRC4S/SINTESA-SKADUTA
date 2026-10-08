@extends('layouts.admin')

@section('title', 'Keterlambatan dan Perizinan')
@section('page_title', 'Keterlambatan dan Perizinan')

@section('content')
{{-- Load Icons & Fonts --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Poppins', sans-serif; }

    /* HEADER */
    .header-keterlambatan {
        background-color: #123B6B;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 25px;
        border-radius: 8px 8px 0 0;
    }
    .header-keterlambatan h4 { margin: 0; font-weight: 600; font-size: 1.2rem; }
    .tanggal-jam { text-align: right; font-size: 13px; color: #fff; font-weight: 600; line-height: 1.4; }

    /* FILTER WRAPPER */
    .filter-wrapper {
        background: #f8f9fa; padding: 15px 25px;
        border: 1px solid #ddd; border-top: none;
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;
    }
    .filter-form { display: flex; align-items: center; gap: 10px; }
    .filter-form input[type="date"] { border: 1px solid #ccc; padding: 8px 12px; border-radius: 6px; font-size: 14px; outline: none; }
    .filter-form button { background-color: #123B6B; color: white; border: none; padding: 8px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: 0.3s; }
    .filter-form button:hover { background-color: #0f2e52; }

    .btn-tambah {
        border: 2px solid #123B6B; color: #123B6B; background-color: #fff;
        padding: 8px 18px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: 0.3s;
        display: inline-block; white-space: nowrap;
    }
    .btn-tambah:hover { background-color: #123B6B; color: #fff; }
    .btn-tambah.solid { background-color: #123B6B; color: #fff; }
    .btn-tambah.solid:hover { background-color: #0f2e52; }

    .jenis-tabs { display: flex; gap: 6px; flex-wrap: wrap; }
    .jenis-tab {
        padding: 7px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 600;
        text-decoration: none; background: #eef1fb; color: #123B6B; transition: 0.2s; white-space: nowrap;
    }
    .jenis-tab:hover { background: #dbe1fa; }
    .jenis-tab.active { background: #123B6B; color: #fff; }

    .badge-jenis { padding: 3px 9px; border-radius: 12px; font-size: 10.5px; font-weight: 700; display: inline-block; text-transform: uppercase; letter-spacing: 0.3px; }
    .badge-masuk { background: #dbeafe; color: #1e40af; }
    .badge-keluar { background: #fde9d0; color: #92400e; }

    /* TABLE STYLES */
    .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; margin-top: 10px; }
    table { width: 100%; border-collapse: collapse; min-width: 900px; }
    thead { background-color: #2c3e50; }
    th, td { border: 1px solid #ddd; padding: 12px 15px; text-align: center; font-size: 13px; vertical-align: middle; }
    th { color: #fff; font-weight: 600; white-space: nowrap; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; }
    tr:nth-child(even) { background-color: #f9f9f9; }
    
    .td-nama { text-align: left !important; padding-left: 15px; min-width: 150px; }

    /* STATUS BADGE */
    .badge { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block; color: white; }
    .bg-pending { background: #facc15; color: #854d0e; border: 1px solid #fde047; }
    .bg-terima  { background: #16a34a; border: 1px solid #22c55e; }
    .bg-tolak   { background: #dc2626; border: 1px solid #ef4444; }

    /* ACTION BUTTONS (Lihat Bukti & Cetak) */
    .btn-icon {
        width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;
        border-radius: 6px; text-decoration: none; transition: 0.2s; font-size: 14px;
    }
    .btn-view { background: #e0f2fe; color: #0284c7; }
    .btn-view:hover { background: #bae6fd; color: #0369a1; }
    
    .btn-print { background: #f0fdf4; color: #16a34a; }
    .btn-print:hover { background: #dcfce7; color: #15803d; }

    /* APPROVAL BUTTONS (Terima / Tolak) */
    .approval-wrapper { display: flex; gap: 6px; justify-content: center; }
    .btn-approve {
        background: #dcfce7; color: #166534; border: 1px solid #86efac;
        padding: 6px 10px; border-radius: 6px; cursor: pointer; transition: 0.2s;
    }
    .btn-approve:hover { background: #22c55e; color: white; border-color: #22c55e; }

    .btn-reject {
        background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5;
        padding: 6px 10px; border-radius: 6px; cursor: pointer; transition: 0.2s;
    }
    .btn-reject:hover { background: #ef4444; color: white; border-color: #ef4444; }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .header-keterlambatan { flex-direction: column; text-align: center; gap: 10px; }
        .jenis-tabs { justify-content: center; }
        .filter-wrapper { flex-direction: column; align-items: stretch; padding: 15px; }
        .filter-form { width: 100%; display: flex; gap: 10px; }
        .filter-form input[type="date"] { flex: 1; }
        .btn-tambah { width: 100%; text-align: center; padding: 10px; }
        .p-3 { padding: 15px !important; }
    }
</style>

<div class="card shadow-sm" style="background: white; border-radius: 8px; overflow: hidden;">

    {{-- Header --}}
    <div class="header-keterlambatan">
        <h4>Manajemen Keterlambatan &amp; Izin Kelas</h4>
        <div class="tanggal-jam" id="tanggalJamAdmin"></div>
    </div>

    {{-- Tab Jenis --}}
    <div style="background:#fff; padding: 15px 25px 0; border: 1px solid #ddd; border-top: none; border-bottom: none;">
        <div class="jenis-tabs">
            <a href="{{ route('admin.keterlambatan.index', ['tanggal' => $tanggal]) }}" class="jenis-tab {{ !$jenis ? 'active' : '' }}">Semua</a>
            <a href="{{ route('admin.keterlambatan.index', ['tanggal' => $tanggal, 'jenis' => 'masuk']) }}" class="jenis-tab {{ $jenis == 'masuk' ? 'active' : '' }}">Izin Masuk Kelas</a>
            <a href="{{ route('admin.keterlambatan.index', ['tanggal' => $tanggal, 'jenis' => 'keluar']) }}" class="jenis-tab {{ $jenis == 'keluar' ? 'active' : '' }}">Izin Keluar Kelas</a>
        </div>
    </div>

    {{-- Filter & Tambah --}}
    <div class="filter-wrapper">
        <form method="GET" action="{{ route('admin.keterlambatan.index') }}" class="filter-form">
            <input type="hidden" name="jenis" value="{{ $jenis }}">
            <i class="fa-solid fa-calendar-days" style="color:#123B6B;"></i>
            <input type="date" name="tanggal" value="{{ $tanggal ?? '' }}">
            <button type="submit">Filter</button>
        </form>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('admin.keterlambatan.rekapBulanan') }}" class="btn-tambah solid">
                <i class="fa-solid fa-calendar-alt"></i> Rekap Bulanan
            </a>
            <a href="{{ route('admin.keterlambatan.create') }}" class="btn-tambah">
                <i class="fa-solid fa-plus-circle"></i> Tambah Manual
            </a>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="p-3">
        <p style="font-weight: 600; margin-top:5px; margin-bottom: 15px; color: #333;">
            Daftar pengajuan Surat Izin Terlambat (SIT) siswa
        </p>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="16%">Nama Siswa</th>
                        <th width="10%">Jenis</th>
                        <th width="8%">Jam Ke</th>
                        <th width="21%">Keterangan</th>
                        <th width="9%">Bukti</th>
                        <th width="14%">Status</th>
                        <th width="17%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($keterlambatans as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td class="td-nama">
                                <strong>{{ $item->siswa->nama_lengkap ?? '-' }}</strong><br>
                                <span style="font-size: 11px; color: #666;">
                                    {{ $item->siswa->rombel ?? '' }}
                                </span>
                            </td>

                            <td>
                                <span class="badge-jenis {{ $item->jenis == 'keluar' ? 'badge-keluar' : 'badge-masuk' }}">
                                    {{ $item->jenis == 'keluar' ? 'Keluar' : 'Masuk' }}
                                </span>
                            </td>

                            <td>
                                <span style="font-weight: 600; color: #d35400;">
                                    {{ $item->jam_ke ?: '-' }}
                                </span>
                            </td>

                            <td style="text-align: left;">{{ Str::limit($item->keterangan, 30) }}</td>

                            {{-- KOLOM BUKTI --}}
                            <td>
                                @if($item->dokumen)
                                    <a href="{{ asset('uploads/dokumen_izin/' . $item->dokumen) }}" target="_blank" class="btn-icon btn-view" title="Lihat Bukti">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                @else
                                    <span style="color:#ccc;">-</span>
                                @endif
                            </td>

                            {{-- KOLOM STATUS --}}
                            <td>
                                @if(strtolower($item->status) == 'terima')
                                    <span class="badge bg-terima">Diterima</span>
                                @elseif(strtolower($item->status) == 'tolak')
                                    <span class="badge bg-tolak">Ditolak</span>
                                @else
                                    <span class="badge bg-pending">Menunggu</span>
                                @endif
                            </td>

                            {{-- KOLOM AKSI / APPROVAL --}}
                            <td>
                                @if(strtolower($item->status) == 'menunggu' || strtolower($item->status) == 'pending')
                                    <div class="approval-wrapper">
                                        {{-- Tombol Terima --}}
                                        <form action="{{ route('admin.keterlambatan.updateStatus', $item->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="terima">
                                            <button type="submit" class="btn-approve" title="Terima Izin">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>

                                        {{-- Tombol Tolak --}}
                                        <form action="{{ route('admin.keterlambatan.updateStatus', $item->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="tolak">
                                            <button type="submit" class="btn-reject" title="Tolak Izin" onclick="return confirm('Yakin ingin menolak izin ini?')">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </form>
                                    </div>
                                @elseif(strtolower($item->status) == 'terima')
                                    {{-- Jika Diterima -> Bisa Cetak --}}
                                    <a href="{{ route('admin.keterlambatan.cetak', $item->id) }}" class="btn-icon btn-print" target="_blank" title="Cetak Surat">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                @else
                                    <span style="font-size:12px; color:#aaa;">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted" style="padding: 30px;">
                                <i class="fa-regular fa-folder-open" style="font-size: 24px; margin-bottom: 10px; display:block;"></i>
                                Tidak ada data pengajuan izin pada tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const tanggal = now.toLocaleDateString('id-ID', options);
        const jam = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second:'2-digit' }).replace('.', ':');
        document.getElementById('tanggalJamAdmin').innerHTML = `${tanggal}<br>${jam} WIB`;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

@endsection