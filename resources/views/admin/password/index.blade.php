@extends('layouts.admin')

@section('title', 'Manajemen Password')
@section('page_title', 'Manajemen Password')

@section('content')

{{-- Alert --}}
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

{{-- ================================
      STYLE CSS (Digabung & Responsif)
================================ --}}
<style>
    /* === TAB MENU === */
    .tab-container {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
        flex-wrap: wrap; /* Agar tab turun ke bawah di HP */
    }
    .tab-menu {
        padding: 10px 18px;
        border-radius: 8px;
        background: #ecf0f1;
        cursor: pointer;
        font-weight: 600;
        color: #1e3a8a; /* Menggunakan warna biru sesuai kode terakhir Anda */
        transition: .2s;
        text-align: center;
    }
    .tab-menu:hover, .tab-menu.active {
        background: #1e3a8a;
        color: white;
    }

    .tab-content { display: none; }
    .tab-content.active { display: block; }

    /* === FILTER & SEARCH === */
    .topbar-filter {
        display: flex;
        justify-content: flex-start;
        margin-bottom: 20px;
    }
    .filter-container {
        display: flex;
        gap: 20px;
        align-items: flex-end;
        flex-wrap: wrap;
        width: 100%;
    }
    .filter-group {
        width: auto;
    }
    .filter-group label {
        font-weight: 600;
        margin-bottom: 6px;
        display: block;
    }
    .search-box {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        padding: 8px;
        border-radius: 12px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        width: 260px;
        transition: width 0.3s ease;
    }
    .search-box input {
        border: none;
        background: transparent;
        outline: none;
        flex: 1;
        font-size: 14px;
        width: 100%; /* Fix width */
    }
    .search-box button {
        background: transparent;
        border: none;
        cursor: pointer;
        color: #333;
    }

    /* === TABEL (Scrollable di Mobile) === */
    .table-responsive {
        width: 100%;
        overflow-x: auto; /* Scroll samping otomatis jika layar kecil */
        -webkit-overflow-scrolling: touch;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        min-width: 800px; /* Minimal lebar agar tabel tidak gepeng */
    }

    thead { background: #2c3e50; color: white; }
    th, td {
        padding: 8px 10px;
        border-bottom: 1px solid #ddd;
        text-align: left;
        vertical-align: middle;
        font-size: 14px;
    }
    /* Lebar Kolom (Persis kode Anda) */
    th:nth-child(1) { width: 5%; text-align: center; }
    th:nth-child(2) { width: 15%; }
    th:nth-child(3) { width: 25%; }
    th:nth-child(4) { width: 18%; }
    th:nth-child(5) { width: 30%; }
    th:nth-child(6) { width: 14%; text-align: center; }

    tbody tr:hover { background: #f8f9fa; }

    /* === AKSI BUTTON === */
    .aksi-edit {
        color: #ff9800;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
    }
    .aksi-edit:hover { opacity: 0.7; }

    /* === FORM ADMIN (Tab 3) === */
    #tab-admin input[type="password"] {
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ccc;
        margin-top: 5px;
        box-sizing: border-box; /* Agar padding tidak melebar keluar */
    }
    #tab-admin button {
        background: #1e3a8a;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 10px;
    }
    #tab-admin button:hover { opacity: .85; }

    /* =========================================
       MEDIA QUERY: KHUSUS TAMPILAN MOBILE (HP)
       ========================================= */
    @media (max-width: 768px) {
        /* Tab Menu memenuhi layar */
        .tab-menu {
            flex: 1; 
            text-align: center;
            font-size: 13px;
            white-space: nowrap;
        }
        
        /* Filter & Search Stack ke Bawah */
        .topbar-filter { display: block; }
        .filter-container { flex-direction: column; align-items: stretch; gap: 10px; }
        .filter-group { width: 100%; }
        
        /* Search Box Full Width */
        .search-box { width: 100%; box-sizing: border-box; }

        /* Tombol Simpan Full Width */
        #tab-admin button { width: 100%; padding: 12px; }
    }
</style>

{{-- TAB HEADER --}}
<div class="tab-container">
    <div class="tab-menu active" data-target="#tab-siswa">Password Siswa</div>
    <div class="tab-menu" data-target="#tab-guru">Password Guru</div>
    <div class="tab-menu" data-target="#tab-admin">Password Admin</div>
</div>


{{-- ================================
      TAB 1 — PASSWORD SISWA
================================ --}}
<div id="tab-siswa" class="tab-content active">

    <div class="topbar-filter">
        <form method="GET" action="{{ route('admin.password.index') }}" style="width: 100%;">
            <div class="filter-container">
                <div class="filter-group">
                    <label>Cari Siswa</label>
                    <div class="search-box">
                        <input type="text" name="search_siswa" placeholder="Cari NIS / Nama..."
                            value="{{ request('search_siswa') }}">
                        <button><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Wrapper Responsive --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Rombel</th>
                    <th>Jurusan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $i => $s)
                <tr>
                    <td style="text-align:center">{{ $i + $siswas->firstItem() }}</td>
                    <td>{{ $s->nis }}</td>
                    <td>{{ $s->nama_lengkap }}</td>
                    <td>{{ $s->rombel }}</td>
                    <td>{{ $s->jurusan }}</td>
                    <td style="text-align:center;">
                        <a href="{{ route('admin.password.edit', ['type' => 'siswa', 'id' => $s->nis]) }}"
                            class="aksi-edit">
                            <i class="fas fa-key"></i> Ubah
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px; display:flex; justify-content:center;">
        {{ $siswas->appends(request()->query())->links('pagination::simple-tailwind') }}
    </div>

</div>


{{-- ================================
      TAB 2 — PASSWORD GURU
================================ --}}
<div id="tab-guru" class="tab-content">

    <div class="topbar-filter">
        <form method="GET" action="{{ route('admin.password.index') }}" style="width: 100%;">
            <div class="filter-container">
                <div class="filter-group">
                    <label>Cari Guru</label>
                    <div class="search-box">
                        <input type="text" name="search_guru" placeholder="Cari NIP / Nama..."
                            value="{{ request('search_guru') }}">
                        <button><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Wrapper Responsive --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gurus as $i => $g)
                <tr>
                    <td style="text-align:center">{{ $i + $gurus->firstItem() }}</td>
                    <td>{{ $g->nip }}</td>
                    <td>{{ $g->nama }}</td>
                    <td>{{ ucfirst($g->role) }}</td>
                    <td style="text-align:center;">
                        <a href="{{ route('admin.password.edit', ['type' => 'guru', 'id' => $g->nip]) }}"
                            class="aksi-edit">
                            <i class="fas fa-key"></i> Ubah
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px; display:flex; justify-content:center;">
        {{ $gurus->appends(request()->query())->links('pagination::simple-tailwind') }}
    </div>

</div>


{{-- ================================
      TAB 3 — PASSWORD ADMIN SENDIRI
================================ --}}
<div id="tab-admin" class="tab-content">

    <h3 style="margin-top: 0; color:#333;">Ubah Password Admin</h3>

    <form method="POST" action="{{ route('admin.password.updateSelf') }}">
        @csrf

        <div style="margin-bottom:15px;">
            <label style="font-weight:600; display:block; margin-bottom:5px;">Password Lama</label>
            <input type="password" name="current_password" required>
        </div>

        <div style="margin-bottom:15px;">
            <label style="font-weight:600; display:block; margin-bottom:5px;">Password Baru</label>
            <input type="password" name="new_password" required>
        </div>

        <div style="margin-bottom:15px;">
            <label style="font-weight:600; display:block; margin-bottom:5px;">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" required>
        </div>

        <button type="submit">
            Simpan Perubahan
        </button>

    </form>

</div>

{{-- SCRIPT TAB --}}
<script>
document.querySelectorAll('.tab-menu').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.tab-menu').forEach(t => t.classList.remove('active'));
        this.classList.add('active');

        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.querySelector(this.dataset.target).classList.add('active');
    });
});
</script>

@endsection