@extends('layouts.admin')

@section('title', 'Manajemen Role')
@section('page_title', 'Manajemen Role')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    * { font-family: 'Poppins', sans-serif; }

    .card-custom { background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 20px; }

    /* ===== HEADER ===== */
    .header-prestasi { background-color: #123B6B; color: white; display: flex; justify-content: space-between; align-items: center; padding: 20px 25px; flex-wrap: wrap; gap: 12px; }
    .header-prestasi h4 { margin: 0; font-weight: 600; font-size: 1.2rem; }
    .header-prestasi p { margin: 4px 0 0; font-size: 12.5px; opacity: 0.85; }

    /* ===== TAB MENU ===== */
    .tab-container { display: flex; gap: 8px; padding: 18px 25px 0; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .tab-menu {
        padding: 9px 18px; border-radius: 8px; background: #eef1fb; color: #123B6B;
        font-weight: 600; font-size: 13.5px; text-decoration: none; white-space: nowrap; transition: 0.2s; flex: 0 0 auto;
    }
    .tab-menu:hover { background: #dbe1fa; }
    .tab-menu.active { background: #123B6B; color: white; box-shadow: 0 4px 6px rgba(18,59,107,0.2); }

    /* ===== QUICK ACTION: JADIKAN KOOR KELAS ===== */
    .quick-action {
        margin: 20px 25px 0; padding: 16px 18px; border-radius: 10px;
        background: linear-gradient(135deg, #fff7ed 0%, #fffaf0 100%);
        border: 1px solid #fde3c7;
        display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
    }
    .quick-action-icon {
        width: 42px; height: 42px; border-radius: 10px; background: #e67e22; color: #fff;
        display: flex; align-items: center; justify-content: center; font-size: 18px; flex: 0 0 auto;
    }
    .quick-action-text { flex: 1; min-width: 180px; }
    .quick-action-text strong { display: block; font-size: 14px; color: #123B6B; }
    .quick-action-text span { font-size: 12px; color: #888; }
    .quick-action-form { display: flex; gap: 8px; flex-wrap: wrap; flex: 1; min-width: 260px; }
    .quick-action-form input {
        flex: 1; min-width: 160px; padding: 9px 14px; border: 1px solid #f0d9b8; border-radius: 8px;
        font-size: 13.5px; font-family: 'Poppins', sans-serif; outline: none; background: #fff;
    }
    .quick-action-form input:focus { border-color: #e67e22; }
    .btn-quick {
        background-color: #e67e22; color: #fff; border: none; padding: 9px 18px; border-radius: 8px;
        font-weight: 600; font-size: 13.5px; cursor: pointer; white-space: nowrap; transition: 0.2s;
    }
    .btn-quick:hover { background-color: #cf6c17; }

    .alert-box { margin: 14px 25px 0; padding: 11px 16px; border-radius: 8px; font-size: 13.5px; }
    .alert-success { background: #dcfce7; color: #166534; }
    .alert-error { background: #fee2e2; color: #991b1b; }

    /* ===== TOOLBAR ===== */
    .top-bar { display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap; padding: 22px 25px 0; }
    .search-form { flex: 1; min-width: 240px; }
    .search-wrapper { position: relative; width: 100%; }
    .search-box { border: 1px solid #ddd; border-radius: 8px; padding: 10px 14px 10px 38px; width: 100%; font-size: 13.5px; box-sizing: border-box; }
    .search-box:focus { border-color: #123B6B; outline: none; }
    .search-wrapper i { position: absolute; top: 50%; left: 13px; transform: translateY(-50%); color: #9ca3af; font-size: 13px; }

    .btn-add {
        background: #123B6B; color: white; border-radius: 8px; font-weight: 600; padding: 10px 20px;
        text-decoration: none; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px;
        white-space: nowrap; transition: 0.2s;
    }
    .btn-add:hover { background: #0f2e52; }

    /* ===== TABLE ===== */
    .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; padding: 20px 25px 25px; }
    table { width: 100%; border-collapse: collapse; min-width: 780px; }
    thead { background: #2c3e50; color: white; }
    th, td { padding: 12px 14px; text-align: left; border-bottom: 1px solid #eee; font-size: 13.5px; vertical-align: middle; }
    th { font-weight: 600; }
    th.center, td.center { text-align: center; }
    tbody tr:hover { background-color: #f9fafb; }

    .user-cell { display: flex; align-items: center; gap: 10px; }
    .avatar-circle {
        width: 34px; height: 34px; border-radius: 50%; background: #eef1fb; color: #123B6B;
        display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; flex: 0 0 auto;
    }
    .user-name { font-weight: 600; color: #222; }

    .role-badge { border-radius: 20px; padding: 5px 12px; font-size: 11.5px; font-weight: 600; display: inline-block; min-width: 80px; text-align: center; color: white; }

    .btn-action { border: none; background: none; cursor: pointer; font-size: 15px; margin: 0 3px; transition: 0.2s; padding: 6px; border-radius: 6px; }
    .btn-edit { color: #f39c12; } .btn-edit:hover { background: #fef3e2; }
    .btn-delete { color: #dc3545; } .btn-delete:hover { background: #fee2e2; }

    .empty-state { text-align: center; padding: 40px; color: #777; }
    .empty-state i { font-size: 30px; margin-bottom: 10px; display: block; color: #ccc; }

    /* ===== MOBILE RESPONSIVE ===== */
    @media (max-width: 768px) {
        .header-prestasi { flex-direction: column; text-align: center; }
        .quick-action { flex-direction: column; align-items: stretch; text-align: center; }
        .quick-action-icon { margin: 0 auto; }
        .quick-action-form { flex-direction: column; }
        .quick-action-form input, .btn-quick { width: 100%; }
        .top-bar { flex-direction: column; align-items: stretch; }
        .btn-add { justify-content: center; }
    }
</style>

<div class="card-custom">

    <div class="header-prestasi">
        <div>
            <h4><i class="fas fa-user-shield"></i> Manajemen Role</h4>
            <p>Kelola akun & hak akses Admin, Guru, BK, Kesiswaan, Siswa, dan Koor Kelas.</p>
        </div>
        <div class="tanggal-jam" id="tanggal-jam"></div>
    </div>

    {{-- TAB FILTER --}}
    <div class="tab-container">
        <a href="{{ route('admin.role.index', ['role' => 'admin']) }}" class="tab-menu {{ request('role') == 'admin' ? 'active' : '' }}">Admin</a>
        <a href="{{ route('admin.role.index', ['role' => 'guru_bk']) }}" class="tab-menu {{ request('role') == 'guru_bk' ? 'active' : '' }}">Guru BK</a>
        <a href="{{ route('admin.role.index', ['role' => 'guru']) }}" class="tab-menu {{ request('role') == 'guru' ? 'active' : '' }}">Guru</a>
        <a href="{{ route('admin.role.index', ['role' => 'kesiswaan']) }}" class="tab-menu {{ request('role') == 'kesiswaan' ? 'active' : '' }}">Kesiswaan</a>
        <a href="{{ route('admin.role.index', ['role' => 'Siswa']) }}" class="tab-menu {{ request('role') == 'Siswa' ? 'active' : '' }}">Siswa</a>
        <a href="{{ route('admin.role.index', ['role' => 'koor_kelas']) }}" class="tab-menu {{ request('role') == 'koor_kelas' ? 'active' : '' }}">Koor Kelas</a>
    </div>

    {{-- QUICK ACTION: JADIKAN KOOR KELAS --}}
    <div class="quick-action">
        <div class="quick-action-icon"><i class="fas fa-user-check"></i></div>
        <div class="quick-action-text">
            <strong>Jadikan Koor Kelas</strong>
            <span>Masukkan NIS — koor lama di kelas yang sama otomatis diturunkan jadi siswa biasa.</span>
        </div>
        <form method="POST" action="{{ route('admin.role.assignKoor') }}" class="quick-action-form">
            @csrf
            <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Masukkan NIS siswa" required>
            <button type="submit" class="btn-quick"><i class="fas fa-user-check"></i> Jadikan Koor Kelas</button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert-box alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-box alert-error">{{ session('error') }}</div>
    @endif

    {{-- TOOLBAR --}}
    <div class="top-bar">
        <form method="GET" action="{{ route('admin.role.index') }}" class="search-form">
            <input type="hidden" name="role" value="{{ request('role', 'admin') }}">
            <div class="search-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="search-box" placeholder="Cari Nama, NIS, atau Email...">
            </div>
        </form>
        <a href="{{ route('admin.role.create') }}" class="btn-add"><i class="fas fa-plus"></i> Tambah User</a>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th class="center" style="width: 5%;">No</th>
                    <th>Nama Pengguna</th>
                    <th>NIS / NIP</th>
                    <th>Email</th>
                    <th class="center">Role</th>
                    @if(request('role') == 'guru')
                        <th>Wali Kelas</th>
                    @endif
                    <th class="center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $index => $role)
                    @php
                        $namaTampil = $role->nama_lengkap ?? $role->nama ?? '-';
                        $inisial = strtoupper(substr($namaTampil, 0, 1));
                    @endphp
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar-circle">{{ $inisial }}</div>
                                <span class="user-name">{{ $namaTampil }}</span>
                            </div>
                        </td>
                        <td>{{ $role->nis ?? $role->nip ?? '-' }}</td>
                        <td>{{ $role->email ?? '-' }}</td>
                        <td class="center">
                            @php
                                $bgColor = '#6c757d';
                                if ($role->role == 'admin') $bgColor = '#0d6efd';
                                elseif ($role->role == 'guru_bk') $bgColor = '#ffc107';
                                elseif ($role->role == 'kesiswaan') $bgColor = '#6610f2';
                                elseif ($role->role == 'guru') $bgColor = '#28a745';
                                elseif ($role->role == 'Siswa') $bgColor = '#17a2b8';
                                elseif (strtolower($role->role) == 'koor_kelas') $bgColor = '#e67e22';
                            @endphp
                            <span class="role-badge" style="background-color: {{ $bgColor }};">
                                {{ strtolower($role->role) == 'koor_kelas' ? 'Koor Kelas' : ($role->role == 'guru' ? 'Guru' : ucfirst($role->role)) }}
                            </span>
                        </td>
                        @if(request('role') == 'guru')
                            <td>{{ $role->walikelas ?? '-' }}</td>
                        @endif
                        <td class="center">
                            <a href="{{ route('admin.role.edit', $role->nip ?? $role->nis) }}" class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.role.destroy', $role->nip ?? $role->nis) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" onclick="return confirm('Yakin hapus user ini? Data tidak bisa dikembalikan.')" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ request('role') == 'guru' ? 7 : 6 }}" class="empty-state">
                            <i class="far fa-folder-open"></i>
                            Belum ada data user ditemukan.
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
