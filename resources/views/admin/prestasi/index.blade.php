@extends('layouts.admin')

@section('title', 'Prestasi Siswa')
@section('page_title', 'Prestasi Siswa')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    /* ===========================
       BASE STYLES
       =========================== */
    .card-custom {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        overflow: hidden;
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
    .tanggal-jam { font-size: 13px; text-align: right; opacity: 0.9; }

    /* FILTER SECTION */
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
        font-size: 14px; font-family: 'Poppins', sans-serif;
        color: #333; outline: none; transition: 0.2s; min-width: 200px;
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
    .btn-tambah:hover { background-color: #123B6B; color: #fff; }

    /* TABLE SECTION */
    .table-responsive {
        width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;
    }
    table { width: 100%; border-collapse: collapse; min-width: 900px; }
    thead { background-color: #2c3e50; color: white; }
    th, td {
        padding: 14px 15px; border-bottom: 1px solid #eee;
        text-align: left; font-size: 14px; vertical-align: middle;
    }
    th:first-child, td:first-child { text-align: center; width: 5%; }
    .col-aksi { text-align: center; width: 10%; }
    tbody tr:hover { background-color: #f9f9f9; }

    /* === WARNA BADGE KATEGORI (Baru) === */
    .badge-kategori {
        padding: 5px 10px; border-radius: 6px;
        font-size: 12px; font-weight: 600;
        text-transform: capitalize; letter-spacing: 0.3px;
        display: inline-block; min-width: 80px; text-align: center;
    }
    
    /* Warna untuk Sertifikat (Hijau Teal) */
    .badge-sertifikat { background-color: #e0f2f1; color: #00695c; border: 1px solid #b2dfdb; }
    
    /* Warna untuk Lomba (Kuning/Oranye) */
    .badge-lomba { background-color: #fff8e1; color: #f57f17; border: 1px solid #ffe082; }
    
    /* Warna untuk Seminar (Biru) */
    .badge-seminar { background-color: #e3f2fd; color: #1565c0; border: 1px solid #90caf9; }
    
    /* Warna untuk Lainnya (Abu/Ungu) */
    .badge-lainnya { background-color: #f3e5f5; color: #7b1fa2; border: 1px solid #ce93d8; }

    /* Default (jika tidak match) */
    .badge-default { background-color: #f5f5f5; color: #616161; }


    /* AKSI BUTTONS */
    .aksi-group { display: flex; justify-content: center; gap: 10px; }
    .btn-icon {
        border: none; background: none; cursor: pointer;
        font-size: 16px; transition: 0.2s; padding: 5px; border-radius: 4px;
    }
    .btn-view { color: #007bff; } .btn-view:hover { background: #e3f2fd; }
    .btn-delete { color: #dc3545; } .btn-delete:hover { background: #fee2e2; }

    /* LINK FILE */
    .link-file {
        color: #1e3a8a; text-decoration: none; font-weight: 500;
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 13px; background: #f0f4ff; padding: 5px 10px; border-radius: 6px;
    }
    .link-file:hover { background: #dbeafe; }

    /* EMPTY STATE */
    .empty-state { text-align: center; padding: 40px; color: #777; }
    .empty-state i { font-size: 30px; margin-bottom: 10px; color: #ccc; }

    /* MOBILE RESPONSIVE */
    @media (max-width: 768px) {
        .header-prestasi { flex-direction: column; text-align: center; gap: 10px; }
        .prestasi-filter-wrapper { flex-direction: column; align-items: stretch; }
        .filter-form { flex-direction: column; width: 100%; }
        .filter-select, .btn-filter, .btn-tambah { width: 100%; justify-content: center; }
        .filter-icon { display: none; }
    }
</style>

<div class="card-custom">

    {{-- Header --}}
    <div class="header-prestasi">
        <h4>Manajemen Prestasi Siswa</h4>
        <div class="tanggal-jam" id="tanggal-jam"></div>
    </div>

    {{-- Filter & Tambah --}}
    <div class="prestasi-filter-wrapper">
        <form method="GET" action="{{ route('admin.prestasi.index') }}" class="filter-form">
            <i class="fa-solid fa-filter filter-icon" style="font-size: 18px; color:#123B6B;"></i>
            
            <select name="jenis" class="filter-select">
                <option value="">-- Semua Jenis Prestasi --</option>
                <option value="sertifikat" {{ request('jenis') == 'sertifikat' ? 'selected' : '' }}>Sertifikat</option>
                <option value="lomba" {{ request('jenis') == 'lomba' ? 'selected' : '' }}>Lomba</option>
                <option value="seminar" {{ request('jenis') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                <option value="lainnya" {{ request('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>

            <button type="submit" class="btn-filter">Terapkan Filter</button>
        </form>

        <a href="{{ route('admin.prestasi.create') }}" class="btn-tambah">
            <i class="fas fa-plus"></i> Tambah Prestasi
        </a>
    </div>

    {{-- Tabel --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th style="width: 25%;">Judul Prestasi</th>
                    <th>Jenis</th>
                    <th>Tanggal</th>
                    <th>File / Link</th>
                    <th class="col-aksi">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($prestasi as $index => $p)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        
                        <td>
                            <strong>{{ $p->siswa->nama_lengkap ?? '-' }}</strong>
                            <div style="font-size:12px; color:#888;">{{ $p->siswa->nis ?? '' }}</div>
                        </td>

                        <td>{{ $p->siswa->rombel ?? '-' }}</td>

                        <td>{{ $p->judul }}</td>

                        <td>
                            {{-- LOGIKA WARNA BADGE BERDASARKAN JENIS --}}
                            @php
                                $badgeClass = 'badge-default';
                                if($p->jenis == 'sertifikat') $badgeClass = 'badge-sertifikat';
                                elseif($p->jenis == 'lomba') $badgeClass = 'badge-lomba';
                                elseif($p->jenis == 'seminar') $badgeClass = 'badge-seminar';
                                elseif($p->jenis == 'lainnya') $badgeClass = 'badge-lainnya';
                            @endphp

                            <span class="badge-kategori {{ $badgeClass }}">
                                {{ ucfirst($p->jenis) }}
                            </span>
                        </td>

                        <td>
                            {{ $p->tanggal_prestasi ? \Carbon\Carbon::parse($p->tanggal_prestasi)->format('d M Y') : '-' }}
                        </td>

                        <td>
                            @if ($p->file)
                                @php
                                    $pathGuru = 'uploads/prestasi/' . $p->file;
                                    $pathSiswa = 'storage/prestasi/' . $p->file;
                                    $finalUrl = file_exists(public_path($pathGuru)) ? asset($pathGuru) : asset($pathSiswa);
                                @endphp
                                <a href="{{ $finalUrl }}" target="_blank" class="link-file">
                                    <i class="fa-solid fa-file-pdf"></i> File
                                </a>
                            @elseif ($p->link)
                                <a href="{{ $p->link }}" target="_blank" class="link-file">
                                    <i class="fa-solid fa-link"></i> Link
                                </a>
                            @else
                                <span style="color:#aaa; font-size:12px;">-</span>
                            @endif
                        </td>

                        <td class="col-aksi">
                            <div class="aksi-group">
                                <a href="{{ route('admin.prestasi.show', $p->id) }}" class="btn-icon btn-view" title="Lihat Detail">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <form method="POST" action="{{ route('admin.prestasi.destroy', $p->id) }}" onsubmit="return confirm('Hapus prestasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-delete" title="Hapus">
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
                            Belum ada data prestasi yang ditemukan.
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