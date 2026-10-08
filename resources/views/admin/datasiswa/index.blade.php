@extends('layouts.admin')

@section('title', 'Data Siswa')
@section('page_title', 'Data Siswa')

@section('content')
@if(session('success'))
    <div style="background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:15px;">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:15px;">
        {{ session('error') }}
    </div>
@endif

<div class="filter-wrapper-main" style="margin-bottom:20px;">
    
    <div class="topbar-filter">
        
        {{-- Hapus style width:100% agar tidak mendorong tombol ke bawah --}}
        <form method="GET" action="{{ route('admin.datasiswa.index') }}" class="filter-form">
            <div class="filter-container">
                <div class="filter-group">
                    <label for="rombel">Pilih Rombel</label>
                    <select name="rombel" id="rombel" class="filter-select">
                        <option value="" {{ request('rombel') == '' ? 'selected' : '' }}>Semua</option>
                        <optgroup label="Kelas X">
                            <option value="X DKV 1">X DKV 1</option><option value="X DKV 2">X DKV 2</option>
                            <option value="X DPIB 1">X DPIB 1</option><option value="X DPIB 2">X DPIB 2</option><option value="X DPIB 3">X DPIB 3</option>
                            <option value="X GEOMATIKA">X GEOMATIKA</option><option value="X KGS">X KGS</option>
                            <option value="X MEKATRONIKA">X MEKATRONIKA</option><option value="X SIJA 1">X SIJA 1</option><option value="X SIJA 2">X SIJA 2</option>
                            <option value="X TAV">X TAV</option><option value="X TITL 1">X TITL 1</option><option value="X TITL 2">X TITL 2</option><option value="X TITL 3">X TITL 3</option><option value="X TITL 4">X TITL 4</option>
                            <option value="X TKR 1">X TKR 1</option><option value="X TKR 2">X TKR 2</option><option value="X TKR 3">X TKR 3</option><option value="X TKR 4">X TKR 4</option>
                            <option value="X TP 1">X TP 1</option><option value="X TP 2">X TP 2</option><option value="X TP 3">X TP 3</option><option value="X TP 4">X TP 4</option>
                        </optgroup>
                        <optgroup label="Kelas XI">
                            <option value="XI DKV 1">XI DKV 1</option><option value="XI DKV 2">XI DKV 2</option>
                            <option value="XI DPIB 1">XI DPIB 1</option><option value="XI DPIB 2">XI DPIB 2</option><option value="XI DPIB 3">XI DPIB 3</option>
                            <option value="XI GEOMATIKA">XI GEOMATIKA</option><option value="XI KGS">XI KGS</option>
                            <option value="XI SIJA 1">XI SIJA 1</option><option value="XI SIJA 2">XI SIJA 2</option>
                            <option value="XI MEKATRONIKA">XI MEKATRONIKA</option><option value="XI TAV">XI TAV</option>
                            <option value="XI TITL 1">XI TITL 1</option><option value="XI TITL 2">XI TITL 2</option><option value="XI TITL 3">XI TITL 3</option><option value="XI TITL 4">XI TITL 4</option>
                            <option value="XI TKR 1">XI TKR 1</option><option value="XI TKR 2">XI TKR 2</option><option value="XI TKR 3">XI TKR 3</option><option value="XI TKR 4">XI TKR 4</option>
                            <option value="XI TP 1">XI TP 1</option><option value="XI TP 2">XI TP 2</option><option value="XI TP 3">XI TP 3</option><option value="XI TP 4">XI TP 4</option>
                        </optgroup>
                        <optgroup label="Kelas XII">
                            <option value="XII DKV 1">XII DKV 1</option><option value="XII DKV 2">XII DKV 2</option>
                            <option value="XII DPIB 1">XII DPIB 1</option><option value="XII DPIB 2">XII DPIB 2</option><option value="XII DPIB 3">XII DPIB 3</option>
                            <option value="XII GEOMATIKA">XII GEOMATIKA</option><option value="XII KGS">XII KGS</option>
                            <option value="XII SIJA 1">XII SIJA 1</option><option value="XII SIJA 2">XII SIJA 2</option>
                            <option value="XII MEKATRONIKA">XII MEKATRONIKA</option><option value="XII TAV">XII TAV</option>
                            <option value="XII TITL 1">XII TITL 1</option><option value="XII TITL 2">XII TITL 2</option><option value="XII TITL 3">XII TITL 3</option><option value="XII TITL 4">XII TITL 4</option>
                            <option value="XII TKR 1">XII TKR 1</option><option value="XII TKR 2">XII TKR 2</option><option value="XII TKR 3">XII TKR 3</option><option value="XII TKR 4">XII TKR 4</option>
                            <option value="XII TP 1">XII TP 1</option><option value="XII TP 2">XII TP 2</option><option value="XII TP 3">XII TP 3</option><option value="XII TP 4">XII TP 4</option>
                        </optgroup>
                        <optgroup label="Kelas XIII (Khusus)">
                            <option value="XIII KGS">XIII KGS</option><option value="XIII SIJA 1">XIII SIJA 1</option><option value="XIII SIJA 2">XIII SIJA 2</option>
                        </optgroup>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="jurusan">Pilih Jurusan</label>
                    <select name="jurusan" id="jurusan" class="filter-select">
                        <option value="">Semua</option>
                        <option value="Desain Komunikasi Visual" {{ request('jurusan') == 'Desain Komunikasi Visual' ? 'selected' : '' }}>Desain Komunikasi Visual</option>
                        <option value="Desain Pemodelan dan Informasi Bangunan" {{ request('jurusan') == 'Desain Pemodelan dan Informasi Bangunan' ? 'selected' : '' }}>Desain Pemodelan dan Informasi Bangunan</option>
                        <option value="Teknik Geospasial" {{ request('jurusan') == 'Teknik Geospasial' ? 'selected' : '' }}>Teknik Geospasial</option>
                        <option value="Konstruksi Gedung dan Sanitasi" {{ request('jurusan') == 'Konstruksi Gedung dan Sanitasi' ? 'selected' : '' }}>Konstruksi Gedung dan Sanitasi</option>
                        <option value="Teknik Mekatronika" {{ request('jurusan') == 'Teknik Mekatronika' ? 'selected' : '' }}>Teknik Mekatronika</option>
                        <option value="Sistem Informasi Jaringan dan Aplikasi ( Pengembangan Perangkat Lunak dan Gim )" {{ request('jurusan') == 'Sistem Informasi Jaringan dan Aplikasi ( Pengembangan Perangkat Lunak dan Gim )' ? 'selected' : '' }}>Sistem Informasi Jaringan dan Aplikasi ( Pengembangan Perangkat Lunak dan Gim )</option>
                        <option value="Teknik Audio Video" {{ request('jurusan') == 'Teknik Audio Video' ? 'selected' : '' }}>Teknik Audio Video</option>
                        <option value="Teknik Instalasi Tenaga Listrik" {{ request('jurusan') == 'Teknik Instalasi Tenaga Listrik' ? 'selected' : '' }}>Teknik Instalasi Tenaga Listrik</option>
                        <option value="Teknik Kendaraan Ringan" {{ request('jurusan') == 'Teknik Kendaraan Ringan' ? 'selected' : '' }}>Teknik Kendaraan Ringan</option>
                        <option value="Teknik Pemesinan" {{ request('jurusan') == 'Teknik Pemesinan' ? 'selected' : '' }}>Teknik Pemesinan</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="angkatan">Angkatan</label>
                    <select name="angkatan" id="angkatan" class="filter-select">
                        <option value="">Semua</option>
                        @foreach($angkatan_list as $a)
                            <option value="{{ $a }}" {{ request('angkatan') == $a ? 'selected' : '' }}>
                                Angkatan {{ $a }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group search-group">
                    <label for="search">Cari</label>
                    <div class="search-box">
                        <input type="text" name="search" id="search" placeholder="Ketik untuk mencari..." value="{{ request('search') }}">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </form>

        @if(auth('guru')->user()->role !== 'kesiswaan')
        <div class="action-buttons-top">
            <a href="{{ route('admin.datasiswa.create') }}" class="btn-tambah">
                <i class="fas fa-plus"></i> Tambah Data
            </a>

            <form action="{{ route('admin.datasiswa.import') }}" method="POST" enctype="multipart/form-data" class="import-form">
                @csrf
                <label for="file" class="btn-import">
                    <i class="fas fa-file-excel"></i> Import
                </label>
                <input type="file" id="file" name="file" accept=".xlsx,.xls,.csv" style="display:none;" onchange="this.form.submit();">
            </form>
        </div>
        @endif

    </div>

    <div class="info-wrapper">
        @if ($rombel)
            <div class="info-text">
                <strong>Jumlah siswa di {{ $rombel }}:</strong> {{ $jumlah }}
            </div>
        @else
            <div class="info-text">
                <strong>Total semua siswa:</strong> {{ $jumlah }}
            </div>
        @endif

        @if(request('rombel'))
            <form 
                action="{{ route('admin.naikkanRombel', request('rombel')) }}" 
                method="POST"
                onsubmit="return confirm('Yakin ingin menaikkan kelas seluruh siswa di rombel {{ request('rombel') }}?');"
                class="form-naik-rombel"
            >
                @csrf
                @method('PUT')

                <button type="submit" class="btn-tambah" style="background:#8e44ad;">
                    <i class="fas fa-arrow-circle-up"></i> Naikkan Kelas Rombel Ini
                </button>
            </form>
        @endif
    </div>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Lengkap</th>
                <th>Rombel</th>
                <th>Kompetensi Keahlian</th>
                <th style="min-width: 250px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswas as $i => $siswa)
            <tr>
                <td style="text-align:center;">{{ $i + $siswas->firstItem() }}</td>
                <td>{{ $siswa->nis }}</td>
                <td>{{ $siswa->nama_lengkap }}</td>
                <td>{{ $siswa->rombel }}</td>
                <td>{{ $siswa->jurusan }}</td>
                <td>
                    <div class="aksi-container">
                    <a href="{{ route('admin.datasiswa.show', $siswa->nis) }}" class="aksi-lihat">
                        <i class="fas fa-eye"></i> Lihat
                    </a>
                    @if(auth()->user()->role == 'admin')
                        <a href="{{ route('admin.datasiswa.edit', $siswa->nis) }}" class="aksi-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.datasiswa.destroy', $siswa->nis) }}" 
                            method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="aksi-hapus"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                        <form action="{{ route('admin.datasiswa.toggleAkses', $siswa->nis) }}" method="POST">
                            @csrf @method('PUT')
                            @if($siswa->akses_edit)
                                <button type="submit" class="aksi-toggle" style="color:#16a085;"><i class="fas fa-unlock"></i> Aktif</button>
                            @else
                                <button type="submit" class="aksi-toggle" style="color:#e74c3c;"><i class="fas fa-lock"></i> Nonaktif</button>
                            @endif
                        </form>
                        @if(request('rombel'))
                        <form action="{{ route('admin.naikkanSiswa', $siswa->nis) }}"
                            method="POST" onsubmit="return confirm('Naikkan kelas {{ $siswa->nama_lengkap }}?');">
                            @csrf @method('PUT')
                            <button type="submit" class="aksi-naik" title="Naikkan Kelas"><i class="fas fa-arrow-circle-up"></i> Naikkan</button>
                        </form>
                        @endif
                    @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center; padding:10px;">Belum ada data siswa.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div style="margin-top:20px; display:flex; justify-content:center;">
    <style>
        nav[role="navigation"] > div:first-child { display: none !important; }
        nav[role="navigation"] { display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 15px; }
        nav[role="navigation"] svg { width: 16px !important; height: 16px !important; vertical-align: middle; }
        .pagination a, .pagination span { padding: 6px 12px; border-radius: 6px; background: #f8f9fa; color: #4B0082; font-weight: 500; text-decoration: none; transition: all 0.2s ease; }
        .pagination a:hover { background: #4B0082; color: #fff; }
        .pagination .active span { background: #4B0082; color: white; }
    </style>
    {{ $siswas->appends(request()->query())->links('pagination::simple-tailwind') }}
</div>

<script>
    document.getElementById('angkatan')?.addEventListener('change', function() { this.form.submit(); });
    document.getElementById('rombel').value = "{{ request('rombel') }}";
</script>

<style>
    /* =======================
       LAYOUT & RESPONSIVE
    ======================= */
    
    /* Wrapper Filter Utama (DESKTOP: Satu Baris) */
    .topbar-filter {
        display: flex;
        align-items: flex-end; /* Semua elemen rata bawah (sejajar input) */
        justify-content: flex-start; /* Mulai dari kiri */
        gap: 15px;
        flex-wrap: nowrap; /* 🔥 KUNCI: Jangan turun baris di desktop */
        width: 100%;
        margin-bottom: 15px;
        overflow-x: auto; /* Jaga-jaga kalau layar desktop sangat kecil */
        padding-bottom: 5px;
    }

    /* Container Filter Kiri */
    .filter-container {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        flex-wrap: nowrap; /* Filter input juga sejajar */
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        flex-shrink: 0; /* Mencegah input mengecil */
    }

    .filter-group label {
        font-weight: 600;
        margin-bottom: 6px;
        color: #222;
        font-size: 13px;
        white-space: nowrap;
    }

    /* Select & Input Style */
    .filter-select,
    .search-box input {
        background: #f8f9fa;
        border: none;
        border-radius: 8px;
        padding: 8px 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        font-size: 13px;
        width: 160px; /* Lebar default input */
        transition: all 0.2s ease;
    }

    .filter-select:focus,
    .search-box input:focus {
        outline: none;
        box-shadow: 0 3px 8px rgba(0, 150, 255, 0.3);
    }

    /* Search Box Wrapper */
    .search-box {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        width: 200px;
    }

    .search-box input {
        border: none;
        box-shadow: none;
        flex: 1;
        background: transparent;
        padding: 8px 12px;
        width: auto;
    }

    .search-box button {
        background: transparent;
        border: none;
        color: #333;
        padding: 8px 10px;
        cursor: pointer;
    }

    /* Tombol Kanan (Tambah & Import) - SEJAJAR */
    .action-buttons-top {
        display: flex;
        gap: 8px;
        flex-wrap: nowrap;
        margin-left: px; /* 🔥 KUNCI: Dorong ke kanan mentok */
        flex-shrink: 0; /* Jangan mengecil */
    }

    .btn-tambah, .btn-import {
        background: #1abc9c;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: 0.2s;
        font-size: 13px;
        cursor: pointer;
        white-space: nowrap; /* Teks jangan turun baris */
        height: 35px; /* Samakan tinggi dengan input */
    }

    .btn-import { background: #3498db; }
    .btn-tambah:hover { background: #16a085; }
    .btn-import:hover { background: #2980b9; }

    /* Info Wrapper */
    .info-wrapper {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 10px;
    }
    .info-text { text-align: right; color: #121212; font-size: 14px; }

    /* =======================
       TABEL RESPONSIVE
    ======================= */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        white-space: nowrap;
    }

    thead { background: #2c3e50; color: white; }
    th, td { padding: 10px 12px; border-bottom: 1px solid #ddd; text-align: left; vertical-align: middle; }
    th:nth-child(1) { text-align: center; }
    th:nth-child(6) { text-align: center; }
    tbody tr:hover { background: #f8f9fa; }

    /* Aksi Container */
    .aksi-container { display: flex; justify-content: center; align-items: center; gap: 10px; }
    .aksi-container a, .aksi-container button { font-size: 14px; text-decoration: none; border: none; background: none; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; }
    .aksi-lihat { color: #007bff; } .aksi-edit { color: #ff9800; } .aksi-hapus { color: #e74c3c; } .aksi-toggle { background: none; border: none; cursor: pointer; font-size: 14px; } .aksi-naik { color: #8e44ad; background: none; border: none; cursor: pointer; font-size: 14px; }

    /* =======================
       MEDIA QUERY (MOBILE)
    ======================= */
    @media (max-width: 991px) {
        .topbar-filter {
            flex-wrap: wrap; /* Di HP boleh turun baris */
            gap: 15px;
        }

        .filter-container {
            flex-wrap: wrap;
            width: 100%;
        }

        .filter-group {
            width: 100%; /* Input full width di HP */
        }

        .filter-select, .search-box, .search-box input {
            width: 100% !important;
        }

        .action-buttons-top {
            margin-left: 0;
            width: 100%;
            flex-direction: column; /* Tombol stack ke bawah di HP */
        }

        .btn-tambah, .btn-import {
            width: 100%;
            justify-content: center;
        }

        .info-wrapper {
            align-items: flex-start;
            margin-top: 10px;
        }
        .info-text { text-align: left; }
    }
</style>

@endsection