@extends('layouts.admin')

@section('title', 'Dokumen Siswa')
@section('page_title', 'Dokumen Siswa')

@section('content')

{{-- Alert --}}
@if(session('success'))
    <div style="background:#d4edda; color:#155724; padding:12px; border-radius:6px; margin-bottom:15px; font-size:14px; border-left: 5px solid #28a745;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

{{-- FILTER & SEARCH --}}
<div class="filter-wrapper">
    <form method="GET" action="{{ route('admin.dokumensiswa.index') }}" class="filter-form">
        <div class="filter-group search-group">
            <label for="search">Cari Data</label>
            <div class="search-box">
                <input type="text" name="search" id="search" placeholder="Cari NIS / Nama..." value="{{ request('search') }}">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
</div>

{{-- TABEL DOKUMEN --}}
<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Lengkap</th>
                <th>Status Dokumen</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswa as $i => $s)
                {{-- LOGIKA HITUNG KELENGKAPAN --}}
                @php
                    $jumlahUpload = $s->dokumen_siswa_count ?? 0;
                    $targetWajib  = $totalDokumenWajib ?? 5; // Default 5 jika tidak ada settingan
                    $isLengkap    = $jumlahUpload >= $targetWajib;
                @endphp

            <tr>
                <td style="text-align:center;">{{ $i + $siswa->firstItem() }}</td>
                <td><span style="font-weight:600; color:#555;">{{ $s->nis }}</span></td>
                <td>{{ $s->nama_lengkap }}</td>
                
                {{-- KOLOM STATUS DOKUMEN --}}
                <td style="text-align:center;">
                    @if($isLengkap)
                        <span class="badge-status badge-success">
                            <i class="fas fa-check-circle"></i> Lengkap ({{ $jumlahUpload }})
                        </span>
                    @else
                        <span class="badge-status badge-warning">
                            <i class="fas fa-exclamation-circle"></i> Belum Lengkap ({{ $jumlahUpload }}/{{ $targetWajib }})
                        </span>
                    @endif
                </td>

                <td>
                    <div class="aksi-container">
                        
                        {{-- TOMBOL NOTIFIKASI / REMINDER (Hanya muncul jika BELUM LENGKAP) --}}
                        @if(!$isLengkap)
                            <form action="{{ route('admin.dokumensiswa.ingatkan', $s->nis) }}" method="POST" onsubmit="return confirm('Kirim notifikasi peringatan ke siswa ini?')">
                                @csrf
                                <button type="submit" class="btn-icon btn-ingatkan" title="Kirim Peringatan">
                                    <i class="fas fa-bell"></i> Ingatkan
                                </button>
                            </form>
                        @endif

                        {{-- Lihat --}}
                        <a href="{{ route('admin.dokumensiswa.show', $s->nis) }}" class="btn-icon btn-lihat" title="Lihat Detail">
                            <i class="fas fa-folder-open"></i> Detail
                        </a>

                        {{-- Edit & Hapus (Kecuali Kesiswaan) --}}
                        @if(auth('guru')->user()->role !== 'kesiswaan')
                            <form action="{{ route('admin.dokumensiswa.destroy', $s->nis) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon btn-hapus" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; padding:30px; color:#777;">
                    <i class="far fa-folder-open" style="font-size:32px; display:block; margin-bottom:10px; opacity:0.5;"></i>
                    <p>Belum ada data siswa ditemukan.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- PAGINATION --}}
<div style="margin-top:20px; display:flex; justify-content:center;">
    {{ $siswa->appends(request()->query())->links('pagination::simple-tailwind') }}
</div>

{{-- STYLE CSS --}}
<style>
    /* === BASE LAYOUT === */
    .filter-wrapper { margin-bottom: 20px; }
    .filter-form { display: flex; gap: 20px; flex-wrap: wrap; align-items: flex-end; }
    .filter-group { display: flex; flex-direction: column; }
    .filter-group label { font-weight: 600; margin-bottom: 6px; font-size: 14px; color: #333; }
    
    /* Search Box */
    .search-box {
        display: flex; align-items: center; background: #fff;
        border: 1px solid #ddd; border-radius: 8px; padding: 8px 12px;
        width: 280px; transition: 0.3s;
    }
    .search-box:focus-within { border-color: #123B6B; box-shadow: 0 0 0 3px rgba(18, 59, 107, 0.1); }
    .search-box input { border: none; background: transparent; outline: none; flex: 1; font-size: 14px; }
    .search-box button { background: transparent; border: none; cursor: pointer; color: #555; }

    /* === TABLE STYLE === */
    .table-responsive {
        width: 100%; overflow-x: auto; border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03); background: white; border: 1px solid #eee;
    }
    table { width: 100%; border-collapse: collapse; min-width: 850px; }
    thead { background: #123B6B; color: white; text-transform: uppercase; letter-spacing: 0.5px; font-size: 13px; }
    th, td { padding: 14px 18px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; text-align: left; font-size: 14px; }
    
    /* Lebar Kolom */
    th:nth-child(1), td:nth-child(1) { width: 5%; text-align: center; }
    th:nth-child(2) { width: 15%; }
    th:nth-child(3) { width: 30%; }
    th:nth-child(4), td:nth-child(4) { width: 20%; text-align: center; }
    th:nth-child(5), td:nth-child(5) { width: 25%; text-align: center; }

    tbody tr:hover { background: #f8fbff; }

    /* === BADGES STATUS === */
    .badge-status {
        padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 12px;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .badge-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .badge-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }

    /* === TOMBOL AKSI === */
    .aksi-container { display: flex; justify-content: center; gap: 10px; align-items: center; }
    
    .btn-icon {
        border: none; background: none; cursor: pointer; font-size: 13px; font-weight: 600;
        text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 10px; border-radius: 6px; transition: 0.2s;
    }
    
    /* Tombol Ingatkan (Penting!) */
    .btn-ingatkan { background: #ffc107; color: #000; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .btn-ingatkan:hover { background: #e0a800; transform: translateY(-1px); }

    /* Tombol Detail */
    .btn-lihat { background: #e3f2fd; color: #1976d2; }
    .btn-lihat:hover { background: #bbdefb; }

    /* Tombol Hapus */
    .btn-hapus { background: #ffebee; color: #c62828; padding: 6px 8px; }
    .btn-hapus:hover { background: #ffcdd2; }

    @media (max-width: 768px) {
        .filter-form { flex-direction: column; align-items: stretch; }
        .search-box { width: 100%; }
    }
</style>

@endsection