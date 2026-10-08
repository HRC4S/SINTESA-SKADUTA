@extends('layouts.admin')

@section('title', 'Prestasi Siswa')
@section('page_title', 'Prestasi Siswa — Wali Kelas')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    /* ===========================
       BASE STYLES (Admin Look)
       =========================== */
    .card-custom {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        overflow: hidden;
        font-family: 'Poppins', sans-serif;
    }

    /* HEADER */
    .header-prestasi {
        background-color: #123B6B;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 25px;
    }
    .header-prestasi h4 { margin: 0; font-weight: 600; font-size: 1.2rem; }
    .tanggal-jam { font-size: 13px; text-align: right; opacity: 0.9; line-height: 1.4; }

    /* FILTER & ACTION SECTION */
    .prestasi-filter-wrapper {
        background: #f8f9fa;
        padding: 20px 25px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    .filter-form { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    
    .filter-select {
        padding: 10px 15px;
        border: 1px solid #ddd; border-radius: 8px;
        font-size: 14px; color: #333; outline: none; transition: 0.2s; min-width: 200px;
    }
    .filter-select:focus { border-color: #123B6B; }

    .btn-filter {
        background-color: #123B6B; color: white; border: none;
        padding: 10px 20px; border-radius: 8px; cursor: pointer;
        font-weight: 600; font-size: 14px; transition: 0.2s;
    }
    .btn-filter:hover { background-color: #0f2e52; }

    .btn-tambah {
        border: 2px solid #123B6B; color: #123B6B; background-color: transparent;
        padding: 9px 20px; border-radius: 8px; text-decoration: none;
        font-weight: 600; font-size: 14px; transition: 0.3s;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-tambah:hover { background-color: #123B6B; color: #fff; text-decoration: none; }

    /* TABLE SECTION */
    .table-responsive { width: 100%; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 900px; }
    thead { background-color: #2c3e50; color: white; }
    th, td { padding: 14px 15px; border-bottom: 1px solid #eee; text-align: left; font-size: 14px; }
    th { font-weight: 500; }
    tbody tr:hover { background-color: #f9f9f9; }

    /* BADGE COLORS */
    .badge-kategori {
        padding: 5px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;
        text-transform: capitalize; display: inline-block; min-width: 85px; text-align: center;
    }
    .badge-sertifikat { background-color: #e0f2f1; color: #00695c; border: 1px solid #b2dfdb; }
    .badge-lomba { background-color: #fff8e1; color: #f57f17; border: 1px solid #ffe082; }
    .badge-seminar { background-color: #e3f2fd; color: #1565c0; border: 1px solid #90caf9; }
    .badge-lainnya { background-color: #f3e5f5; color: #7b1fa2; border: 1px solid #ce93d8; }

    /* ACTION BUTTONS */
    .aksi-group { display: flex; gap: 8px; }
    .btn-icon {
        border: none; background: #f0f2f5; cursor: pointer;
        font-size: 15px; padding: 7px 10px; border-radius: 6px; transition: 0.2s;
    }
    .btn-view { color: #123B6B; } .btn-view:hover { background: #123B6B; color: white; }
    
    .link-file {
        color: #1e3a8a; text-decoration: none; font-weight: 500;
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 13px; background: #f0f4ff; padding: 5px 10px; border-radius: 6px;
    }

    /* EMPTY STATE */
    .empty-state { text-align: center; padding: 50px; color: #999; }
    .empty-state i { font-size: 40px; margin-bottom: 15px; color: #ddd; }

    @media (max-width: 768px) {
        .header-prestasi { flex-direction: column; text-align: center; gap: 10px; }
        .prestasi-filter-wrapper { flex-direction: column; align-items: stretch; }
        .filter-form { flex-direction: column; }
        .filter-select, .btn-filter, .btn-tambah { width: 100%; justify-content: center; }
    }
</style>

<div class="card-custom">
    {{-- Header --}}
    <div class="header-prestasi">
        <div>
            <h4><i class="fa-solid fa-trophy mr-2"></i> Prestasi Siswa Kelolaan</h4>
            <small style="opacity: 0.8;">Hanya menampilkan siswa di kelas Anda</small>
        </div>
        <div class="tanggal-jam" id="tanggal-jam"></div>
    </div>

    {{-- Filter & Tambah --}}
    <div class="prestasi-filter-wrapper">
        <form method="GET" action="{{ route('wali.prestasi.index') }}" class="filter-form">
            <select name="jenis" class="filter-select">
                <option value="">-- Semua Jenis --</option>
                <option value="sertifikat" {{ request('jenis') == 'sertifikat' ? 'selected' : '' }}>Sertifikat</option>
                <option value="lomba" {{ request('jenis') == 'lomba' ? 'selected' : '' }}>Lomba</option>
                <option value="seminar" {{ request('jenis') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                <option value="lainnya" {{ request('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            <button type="submit" class="btn-filter"><i class="fa-solid fa-magnifying-glass mr-1"></i> Filter</button>
        </form>

        {{-- Wali Kelas kini bisa menambah prestasi --}}
        <a href="{{ route('wali.prestasi.create') }}" class="btn-tambah">
            <i class="fas fa-plus"></i> Tambah Prestasi
        </a>
    </div>

    {{-- Tabel --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="text-align: center; width: 50px;">No.</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th style="width: 25%;">Judul Prestasi</th>
                    <th>Jenis</th>
                    <th>Tanggal</th>
                    <th>Bukti</th>
                    <th style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($prestasi as $index => $p)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $p->siswa->nama_lengkap ?? '-' }}</strong>
                        <div style="font-size:11px; color:#888;">NIS: {{ $p->siswa->nis ?? '' }}</div>
                    </td>
                    <td>{{ $p->siswa->rombel ?? '-' }}</td>
                    <td>{{ $p->judul }}</td>
                    <td>
                        @php
                            $badge = 'badge-lainnya';
                            if($p->jenis == 'sertifikat') $badge = 'badge-sertifikat';
                            elseif($p->jenis == 'lomba') $badge = 'badge-lomba';
                            elseif($p->jenis == 'seminar') $badge = 'badge-seminar';
                        @endphp
                        <span class="badge-kategori {{ $badge }}">{{ ucfirst($p->jenis) }}</span>
                    </td>
                    <td>
                        {{ $p->tanggal_prestasi ? \Carbon\Carbon::parse($p->tanggal_prestasi)->format('d M Y') : '-' }}
                    </td>
                    <td>
                        @if ($p->file)
                            <a href="{{ asset('storage/prestasi/'.$p->file) }}" target="_blank" class="link-file">
                                <i class="fa-solid fa-file-lines"></i> File
                            </a>
                        @elseif ($p->link)
                            <a href="{{ $p->link }}" target="_blank" class="link-file">
                                <i class="fa-solid fa-link"></i> Link
                            </a>
                        @else
                            <span class="text-muted" style="font-size: 12px;">-</span>
                        @endif
                    </td>
                    <td>
                    <div class="aksi-group">
                        <a href="{{ route('wali.prestasi.show', $p->siswa->nis) }}" class="btn-icon btn-view" title="Lihat Semua Prestasi Siswa Ini">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <form action="{{ route('wali.prestasi.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus prestasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon" style="color: #dc3545;" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="empty-state">
                        <i class="far fa-folder-open"></i><br>
                        Tidak ada data prestasi untuk siswa di kelas Anda.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
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