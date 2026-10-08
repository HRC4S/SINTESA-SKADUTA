@extends('layouts.admin')

@section('title', 'Manajemen Password')
@section('page_title', 'Manajemen Password')

@section('content')

{{-- Alert --}}
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<style>
    /* Global Component */
    .alert { padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; }
    .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

    /* Tab Menu */
    .tab-container {
        display: flex;
        gap: 8px;
        margin-bottom: 15px;
        border-bottom: 8px solid #eee;
        padding-bottom: 10px;
    }

    .tab-menu {
        padding: 8px 16px;
        font-size: 0.9rem;
        border-radius: 6px;
        background: #f8f9fa;
        cursor: pointer;
        font-weight: 600;
        color: #64748b;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }

    .tab-menu.active {
        background: #1e3a8a;
        color: white;
        border-color: #1e3a8a;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .tab-content { display: none; animation: fadeIn 0.3s ease; }
    .tab-content.active { display: block; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Filter & Search */
    .banner-rombel {
        background: #1e3a67;
        padding: 14px 16px;
        border-radius: 8px;
        color: #fff;
        margin-bottom: 15px;
    }

    .banner-rombel div {
        font-size: 1rem;
    }

    .search-form {
        margin-top: 10px;
        display: flex;
        gap: 8px;
    }

    .search-input {
        flex: 1;
        padding: 8px 12px;
        border-radius: 8px;
        border: none;
        outline: none;
        font-size: 0.9rem;
        color: #333;
    }

    .btn-search {
        background: #fff;
        color: #1e3a67;
        padding: 8px 12px;
        border-radius: 8px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
    }

    /* Table Responsive */
    .table-container {
        width: 100%;
        overflow-x: auto;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px; /* Menjaga kelurusan di desktop */
    }

    thead { background: #2c3e50; color: white; }
    th, td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; text-align: left; }
    
    .text-center { text-align: center !important; }

    /* Form Password Saya */
    .form-card {
        max-width: 500px;
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #334155; }
    .form-control {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        outline: none;
        transition: 0.3s;
    }
    .form-control:focus { border-color: #1e3a8a; box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1); }

    .btn-primary {
        background: #1e3a8a;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
    }

    /* Responsif HP */
    @media (max-width: 768px) {
        .tab-container { flex-direction: column; gap: 5px; }
        .search-form { flex-direction: column; }
        .btn-search { width: 100%; }
    }
</style>

<div class="tab-container">
    <div class="tab-menu active" data-target="#tab-siswa">
        <i class="fas fa-users-cog"></i> Password Siswa
    </div>
    <div class="tab-menu" data-target="#tab-wali">
        <i class="fas fa-user-lock"></i> Password Saya
    </div>
</div>

{{-- TAB 1 — PASSWORD SISWA --}}
<div id="tab-siswa" class="tab-content active">
    <div class="banner-rombel">
        <div style="font-size:1.1rem; font-weight:600;">
            <i class="fas fa-chalkboard-teacher"></i> Rombel yang Anda ampu : {{ $rombel }}
        </div>

        <form action="{{ route('wali.kartupelajar.index') }}" method="GET" class="search-form">
            <input name="q" type="text" class="search-input" placeholder="Cari nama atau NIS siswa..." value="{{ request('q') }}">
            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i> Cari
            </button>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 70px;">No</th>
                    <th style="width: 140px;">NIS</th>
                    <th>Nama Lengkap</th>
                    <th style="width: 120px;">Rombel</th>
                    <th style="width: 200px;">Jurusan</th>
                    <th class="text-center" style="width: 180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $i => $s)
                <tr>
                    <td class="text-center">{{ $i + $siswas->firstItem() }}</td>
                    <td style="font-family: monospace; font-weight: 600;">{{ $s->nis }}</td>
                    <td style="font-weight: 600;">{{ $s->nama_lengkap }}</td>
                    <td>{{ $s->rombel }}</td>
                    <td style="font-size: 0.85rem; color: #64748b;">{{ $s->jurusan }}</td>
                    <td class="text-center">
                        <a href="{{ route('wali.password.edit', $s->nis) }}" style="color:#f59e0b; text-decoration: none; font-weight: 700;">
                            <i class="fas fa-key"></i> Ubah Password
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center" style="padding: 40px; color: #94a3b8;">Tidak ada data ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px; display:flex; justify-content:center;">
        {{ $siswas->appends(request()->query())->links('pagination::simple-tailwind') }}
    </div>
</div>

{{-- TAB 2 — UBAH PASSWORD WALI --}}
<div id="tab-wali" class="tab-content">
    <div class="form-card">
        <h3 style="margin-top:0; margin-bottom:20px; color: #1e293b;"><i class="fas fa-shield-alt"></i> Keamanan Akun</h3>

        <form method="POST" action="{{ route('wali.password.updateSelf') }}">
            @csrf
            <div class="form-group">
                <label>Password Lama</label>
                <input type="password" name="current_password" class="form-control" required placeholder="Masukkan password saat ini">
            </div>

            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="new_password" class="form-control" required placeholder="Minimal 8 karakter">
            </div>

            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" class="form-control" required placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>

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