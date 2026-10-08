@extends('layouts.admin')

@section('title', 'Kartu Pelajar Siswa')
@section('page_title', 'Kartu Pelajar — Wali Kelas')

@section('content')

<style>
    /* Global Wrapper */
    .kartu-wrap { padding: 15px; }

    /* Banner Informasi & Search */
    .banner-wali {
        background: #1e3a67; 
        padding: 20px; 
        border-radius: 10px; 
        color: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .banner-wali .title {
        font-size: 1.1rem; 
        font-weight: 600;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .search-form {
        display: flex; 
        gap: 10px;
    }
    .search-input {
        flex: 1; 
        padding: 10px 15px; 
        border-radius: 8px; 
        border: none; 
        outline: none;
        font-size: 0.95rem;
    }
    .btn-search {
        background: #fff; 
        color: #1e3a67; 
        padding: 10px 20px; 
        border-radius: 8px; 
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }
    .btn-search:hover { background: #e2e8f0; }

    /* Card Table Container */
    .card-table { 
        background: #fff; 
        border-radius: 10px; 
        padding: 15px; 
        margin-top: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .table-header {
        padding: 10px 5px;
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        border-bottom: 2px solid #f1f5f9;
        margin-bottom: 15px;
    }

    /* Responsive Table Table */
.table-responsive {
        width: 100%;
        overflow-x: auto;
        border-radius: 8px;
    }

    .table-list { 
        width: 100%; 
        border-collapse: collapse; 
        min-width: 800px; /* Menjaga agar kolom tidak berhimpit di layar kecil */
        table-layout: fixed; /* Opsional: Memaksa lebar kolom konsisten */
    }

    /* Memastikan Header dan Cell memiliki padding dan alignment yang sama */
    .table-list th, 
    .table-list td { 
        padding: 15px 20px; /* Padding yang lebih luas agar teks bernapas */
        text-align: left;    /* Lurus kiri untuk semua teks */
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-list th {
        font-weight: 700; 
        font-size: 0.85rem; 
        color: #64748b; 
        background: #f8fafc;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Penyesuaian lebar kolom spesifik agar proporsional */
    .col-no { width: 80px; text-align: center !important; }
    .col-nis { width: 150px; }
    .col-nama { width: auto; }
    .col-jurusan { width: 250px; }
    .col-aksi { width: 150px; text-align: center !important; }

    /* Menyamakan alignment konten di dalam cell aksi */
    .text-center { text-align: center !important; }

    /* Button Action */
    .btn-cetak {
        background: #1e3a67; 
        color: #fff !important; 
        padding: 7px 15px; 
        border-radius: 6px;
        text-decoration: none; 
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        transition: 0.3s;
    }
    .btn-cetak:hover { background: #2c528d; transform: translateY(-1px); }

    /* Pagination Styling */
    nav[role="navigation"] > div:first-child { display:none !important; }
    nav[role="navigation"] {
        display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 20px;
    }
    .pagination .page-item .page-link {
        padding: 8px 20px; border-radius: 8px; background: #1e3a67; color: white; font-weight: 600; border: none;
    }
    .pagination .page-item.disabled .page-link { background: #cbd5e1; }
    nav[role="navigation"] svg { width: 16px !important; }

    /* Mobile Responsive (Max 768px) */
    @media (max-width: 768px) {
        .search-form { flex-direction: column; }
        .btn-search { width: 100%; }
        .table-header { flex-direction: column; align-items: flex-start; gap: 10px; }
        .banner-wali { padding: 15px; }
    }
</style>

<div class="kartu-wrap">

    {{-- INFORMASI ROMBEL WALI --}}
    <div class="banner-wali">
        <div class="title">
            <i class="fas fa- chalkboard-teacher"></i>
            Rombel yang Anda ampu : {{ $rombel }}
        </div>

        <form action="{{ route('wali.kartupelajar.index') }}" method="GET" class="search-form">
            <input name="q" type="text" class="search-input" placeholder="Cari nama atau NIS siswa..." value="{{ request('q') }}">
            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i> Cari
            </button>
        </form>
    </div>

    <div class="card-table">
        <div class="table-header">
            <div style="font-weight:700; color:#1e3a67; font-size:1.1rem;">Daftar Siswa</div>
            <div style="font-size:0.85rem; background:#e2e8f0; padding:4px 12px; border-radius:20px; color:#475569; font-weight:600;">
                Total: {{ $siswas->total() }} Siswa
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-list">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align:center;">No</th>
                        <th>NIS</th>
                        <th>Nama Lengkap</th>
                        <th>Jurusan</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $i => $siswa)
                    <tr>
                        <td style="text-align:center; font-weight: 600; color: #94a3b8;">{{ $i + $siswas->firstItem() }}</td>
                        <td style="font-family: monospace; font-weight: 600;">{{ $siswa->nis }}</td>
                        <td style="font-weight: 600;">{{ $siswa->nama_lengkap }}</td>
                        <td>{{ $siswa->jurusan }}</td>
                        <td style="text-align:center;">
                            <a href="{{ route('wali.kartupelajar.preview', $siswa->nis) }}" class="btn-cetak">
                                <i class="fas fa-id-card"></i> Lihat Kartu
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:30px; color:#94a3b8;">Data siswa tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $siswas->appends(request()->query())->links() }}
        </div>
    </div>
</div>

@endsection