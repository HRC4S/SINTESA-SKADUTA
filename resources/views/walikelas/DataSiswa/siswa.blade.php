@extends('layouts.admin')

@section('title', 'Data Siswa Wali Kelas')
@section('page_title', 'Data Siswa Kelas ' . $rombel)

@section('content')
{{-- Notifikasi --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="filter-wrapper-main">
    {{-- Banner Informasi Rombel --}}
    <div class="rombel-info-banner">
        <div class="rombel-text">
            <i class="fas fa-chalkboard-teacher"></i> Rombel yang Anda ampu : <strong>{{ $rombel }}</strong>
        </div>

        {{-- Form Search --}}
        <form action="{{ route('wali.kartupelajar.index') }}" method="GET" class="search-form-wali">
            <div class="search-box">
                <input name="q" type="text" placeholder="Cari nama / NIS..." value="{{ request('q') }}">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>

    {{-- Info Jumlah & Tombol Aksi Massal --}}
    <div class="secondary-actions">
        <div class="info-text">
            <strong>Jumlah siswa di {{ $rombel }}:</strong> {{ $jumlah }}
        </div>
        
        <form action="{{ route('wali.datasiswa.naikkanSemua') }}" method="POST"
              onsubmit="return confirm('Yakin ingin menaikkan semua siswa di rombel ini?');">
            @csrf
            <button class="btn-naik-massal">
                <i class="fas fa-arrow-circle-up"></i> Naikkan Semua Siswa
            </button>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th style="width:50px;">No</th>
                <th>NIS</th>
                <th>NISN</th>
                <th>Nama Lengkap</th>
                <th>Jenis Kelamin</th>
                <th>Email</th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswas as $i => $s)
            <tr>
                <td style="text-align:center;">{{ $i + $siswas->firstItem() }}</td>
                <td>{{ $s->nis }}</td>
                <td>{{ $s->nisn }}</td>
                <td>{{ $s->nama_lengkap }}</td>
                <td>{{ $s->jenis_kelamin }}</td>
                <td>{{ $s->email }}</td>
                <td>
                    <div class="aksi-container">
                        {{-- Tombol Lihat --}}
                        <a href="{{ route('wali.datasiswa.show', $s->nis) }}" class="aksi-lihat" title="Lihat">
                            <i class="fas fa-eye"></i><span>Lihat</span>
                        </a>

                        {{-- Tombol Edit --}}
                        <a href="{{ route('wali.datasiswa.edit', $s->nis) }}" class="aksi-edit" title="Edit">
                            <i class="fas fa-edit"></i><span>Edit</span>
                        </a>

                        {{-- Tombol Hapus --}}
                        <form action="{{ route('wali.datasiswa.destroy', $s->nis) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus siswa ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="aksi-hapus" title="Hapus">
                                <i class="fas fa-trash"></i><span>Hapus</span>
                            </button>
                        </form>

                        {{-- Toggle Akses --}}
                        <form action="{{ route('wali.datasiswa.toggleAkses', $s->nis) }}" method="POST">
                            @csrf
                            @if($s->akses_edit)
                                <button type="submit" class="aksi-toggle" style="color:#16a085;" title="Kunci Akses">
                                    <i class="fas fa-unlock"></i><span>Aktif</span>
                                </button>
                            @else
                                <button type="submit" class="aksi-toggle" style="color:#e74c3c;" title="Buka Akses">
                                    <i class="fas fa-lock"></i><span>Nonaktif</span>
                                </button>
                            @endif
                        </form>

                        {{-- Tombol Naik Satu --}}
                        <form action="{{ route('wali.datasiswa.naikkanSatu', $s->nis) }}" method="POST"
                              onsubmit="return confirm('Naikkan kelas siswa ini?');">
                            @csrf
                            <button type="submit" class="aksi-naik" title="Naikkan Kelas">
                                <i class="fas fa-arrow-circle-up"></i><span>Naikkan</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding:20px;">Tidak ada data siswa pada rombel ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="pagination-wrapper">
    {{ $siswas->appends(request()->query())->links('pagination::simple-tailwind') }}
</div>

<style>
    /* =======================
       LAYOUT & COMPONENTS
    ======================= */
    .alert {
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        background: #d4edda;
        color: #155724;
    }

    .filter-wrapper-main {
        margin-bottom: 25px;
    }

    /* Banner Rombel */
    .rombel-info-banner {
        background: #1e3a67;
        padding: 20px;
        border-radius: 10px;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .rombel-text {
        font-size: 1.1rem;
        font-weight: 500;
    }

    /* Search Wali */
    .search-form-wali {
        flex: 1;
        max-width: 400px;
    }

    .search-box {
        display: flex;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        width: 100%;
    }

    .search-box input {
        border: none;
        padding: 10px 15px;
        flex: 1;
        outline: none;
        color: #333;
    }

    .search-box button {
        background: #f8f9fa;
        border: none;
        padding: 0 15px;
        color: #1e3a67;
        cursor: pointer;
    }

    /* Secondary Actions (Jumlah & Tombol Ungu) */
    .secondary-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .btn-naik-massal {
        background: #8e44ad;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: 0.3s;
    }

    .btn-naik-massal:hover {
        background: #732d91;
        transform: translateY(-1px);
    }

    /* =======================
       TABLE RESPONSIVE
    ======================= */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        white-space: nowrap;
    }

    .table thead {
        background: #2c3e50;
        color: #fff;
    }

    .table th, .table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #edf2f7;
        font-size: 14px;
    }

    /* =======================
       AKSI BUTTONS (Vertical Style)
    ======================= */
    .aksi-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }

    .aksi-lihat, .aksi-edit, .aksi-hapus, .aksi-toggle, .aksi-naik {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        transition: 0.2s;
    }

    .aksi-container i {
        font-size: 18px;
        margin-bottom: 4px;
    }

    .aksi-container span {
        font-size: 11px;
        font-weight: 500;
    }

    .aksi-lihat { color: #007bff; }
    .aksi-edit { color: #ff9800; }
    .aksi-hapus { color: #e74c3c; }
    .aksi-naik { color: #8e44ad; }

    .aksi-container button:hover, .aksi-container a:hover {
        opacity: 0.6;
    }

    /* =======================
       MOBILE OPTIMIZATION
    ======================= */
    @media (max-width: 768px) {
        .rombel-info-banner {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .search-form-wali {
            max-width: 100%;
            width: 100%;
        }

        .secondary-actions {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-naik-massal {
            width: 100%;
        }
    }

    .pagination-wrapper {
        margin-top: 25px;
        display: flex;
        justify-content: center;
    }
</style>
@endsection