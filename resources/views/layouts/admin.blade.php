<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin | Sintesa')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <link rel="icon" href="{{ asset('images/skaduta_logo.png') }}" type="image/png"/>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <style>
        /* =========================================
           1. RESET & GLOBAL
           ========================================= */
        * { box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f4f7f6;
            min-height: 100vh;
        }

        /* =========================================
           2. SIDEBAR STYLES
           ========================================= */
        .sidebar {
            width: 300px;
            background-color: #17375d;
            color: #fff;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100%;
            overflow-y: auto; /* Tetap auto agar bisa di-scroll */
            z-index: 1050;
            left: 0;
            top: 0;
            transition: transform 0.3s ease;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);

            /* --- TRIK MENGHILANGKAN SCROLLBAR SECARA VISUAL --- */
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }

        /* Chrome, Safari and Opera */
        .sidebar::-webkit-scrollbar {
            display: none;
        }

        .sidebar .header { display: flex; align-items: center; padding: 0 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px; }
        .header-logo { width: 40px; height: 40px; margin-right: 10px; }
        .header-title { font-size: 14px; font-weight: 700; color: #fff; margin: 0; white-space: nowrap; }

        .profile-admin { text-align: center; margin-bottom: 20px; }
        .profile-admin-img { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; margin-bottom: 5px; }
        .profile-admin-name { font-size: 12px; font-weight: 600; margin-bottom: 2px; }
        .profile-admin-role { font-size: 12px; opacity: 0.85; }

        .sidebar nav ul { list-style: none; padding: 0; margin: 0; }
        .sidebar nav ul li { margin-bottom: 5px; }
        .sidebar nav ul li a { display: flex; align-items: center; padding: 12px 20px; color: #fff; text-decoration: none; font-size: 0.95rem; transition: 0.3s; }
        .sidebar nav ul li a:hover, .sidebar nav ul li a.active { background-color: #0d2a4a; border-left: 4px solid #4a90e2; }
        .sidebar nav ul li a i { margin-right: 10px; font-size: 1.1rem; width: 25px; text-align: center; }

        .dropdown-toggle { position: relative; cursor: pointer; }
        .dropdown-toggle i.fa-caret-down { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-size: 0.8rem; }
        .dropdown-menu { display: none; background-color: #0d2a4a; padding: 0; }
        .dropdown.active .dropdown-menu { display: block; }
        .dropdown-menu li a { padding-left: 55px; }

        .logout { margin-top: auto; padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .logout a { display: block; text-align: center; padding: 12px; background: #e74c3c; color: #fff; border-radius: 5px; text-decoration: none; }

        /* =========================================
           3. MAIN CONTENT & NAVBAR
           ========================================= */
        .main-content {
            margin-left: 300px;
            padding: 30px;
            width: auto;
            transition: margin-left 0.3s ease;
        }

        .navbar {
            background-color: #fff;
            padding: 15px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h2 { margin: 0; color: #333; font-size: 1.2rem; }
        .user-profile img { width: 35px; height: 35px; border-radius: 50%; margin-left: 15px; }

        .mobile-toggle {
            display: none; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #333; margin-right: 15px; padding: 0;
        }

        /* =========================================
           4. STYLE KHUSUS DASHBOARD (YANG HILANG)
           ========================================= */
        .welcome-card {
            background-color: #17375d; color: #fff; padding: 30px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .welcome-card h1 { margin: 0 0 5px; font-size: 1.8rem; }
        .welcome-card p { margin: 0; font-size: 1rem; color: rgba(255,255,255,0.8); }

        .info-cards {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;
        }
        .info-card {
            background-color: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: space-between;
        }
        .info-card .content p { margin: 0; font-size: 0.9rem; color: #777; }
        .info-card .content h3 { margin: 5px 0 0; font-size: 1.6rem; color: #333; }
        
        .info-card .icon {
            width: 60px; height: 60px; border-radius: 50%; background-color: #e0f0ff; display: flex; justify-content: center; align-items: center;
        }
        .info-card .icon img { width: 30px; height: 30px; }

        .dashboard-grid {
            display: grid; grid-template-columns: 2fr 1fr; gap: 30px;
        }
        .chart-card, .admin-action-card, .konseling-action-card {
            background-color: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 20px;
        }
        .right-column-cards { display: flex; flex-direction: column; gap: 20px; }
        
        .action-item, .notification-item {
            display: flex; align-items: center; margin-bottom: 15px; font-size: 0.95rem;
        }
        .action-item img { width: 25px; height: 25px; margin-right: 15px; }
        .notification-item {
            background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 8px; color: #664d03;
        }
        .notification-item i { margin-right: 15px; font-size: 1.2rem; }
        .chart-container { height: 300px; width: 100%; }

        /* =========================================
           5. RESPONSIF MOBILE
           ========================================= */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            
            .main-content { margin-left: 0; padding: 15px; }
            .mobile-toggle { display: block; }
            
            .navbar { padding: 10px 15px; }
            .navbar h2 { font-size: 1rem; }

            .dashboard-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="sidebar">
       <div class="header header-white">
            <img src="{{ asset('images/skaduta_logo.png') }}" alt="Logo SMK" class="header-logo">
            <h3 class="header-title">SINTESA SMKN 2 YOGYAKARTA</h3>
       </div>

       <div class="profile-admin">
            <img src="{{ auth('guru')->user()->foto ? asset('uploads/foto_guru/' . auth('guru')->user()->foto) : asset('images/profil_admin_tem.jfif') }}" 
                 alt="Foto Guru" class="profile-admin-img" />
            <div class="profile-admin-text">
                <h4 class="profile-admin-name">{{ auth('guru')->user()->nama ?? 'Guru' }}</h4>
                <p class="profile-admin-role">{{ ucfirst(str_replace('_', ' ', auth('guru')->user()->role)) }}</p>
            </div>
       </div>

       <nav>
            <ul>
                @if(auth('guru')->user()->role == 'admin')
                    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="{{ route('admin.datasiswa.index') }}" class="{{ request()->is('admin/datasiswa*') ? 'active' : '' }}"><i class="fas fa-users"></i> Data Siswa</a></li>
                    <li><a href="{{ route('admin.kartupelajar.index') }}" class="{{ request()->is('admin/kartupelajar*') ? 'active' : '' }}"><i class="fas fa-id-card"></i> Kartu Pelajar</a></li>
                    <li class="dropdown {{ request()->is('admin/konseling*') || request()->is('admin/keterlambatan*') || request()->is('admin/presensi*') ? 'open' : '' }}">
                        <a href="#" class="dropdown-toggle"><i class="fas fa-clipboard-check"></i> Kehadiran <i class="fas fa-caret-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('admin.presensi.dashboard') }}" class="{{ request()->is('admin/presensi*') ? 'active' : '' }}">Presensi</a></li>
                            <li><a href="{{ route('admin.konseling.index') }}">Konseling</a></li>
                            <li><a href="{{ route('admin.keterlambatan.index') }}">Keterlambatan</a></li>
                        </ul>
                    </li>
                    
                    <li><a href="{{ route('admin.dokumensiswa.index') }}" class="{{ request()->is('admin/dokumensiswa*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Dokumen Siswa</a></li>
                    <li><a href="{{ route('admin.prestasi.index') }}" class="{{ request()->is('admin/prestasisiswa*') ? 'active' : '' }}"><i class="fas fa-trophy"></i> Prestasi Siswa</a></li>
                    <li><a href="{{ route('admin.role.index') }}" class="{{ request()->is('admin/role*') ? 'active' : '' }}"><i class="fas fa-user-cog"></i> Manajemen Role</a></li>
                    <li><a href="{{ route('admin.password.index') }}" class="{{ request()->is('admin/password*') ? 'active' : '' }}"><i class="fas fa-key"></i> Kelola Password</a></li>
                @endif
                
                @if(auth('guru')->user()->role == 'guru_bk')
                    <li><a href="{{ route('bk.dashboard') }}" class="{{ request()->is('bk/dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard BK</a></li>
                    <li><a href="{{ route('bk.konseling.index') }}"><i class="fas fa-comments"></i> Konseling</a></li>
                    <li><a href="{{ route('bk.keterlambatan.index') }}"><i class="fas fa-clock"></i> Keterlambatan</a></li>
                    <li><a href="{{ route('bk.dokumen.index') }}"><i class="fas fa-file-alt"></i> Dokumen Siswa</a></li>
                    <li><a href="{{ route('bk.prestasi.index') }}"><i class="fas fa-trophy"></i> Prestasi</a></li>
                @endif

                @if(auth('guru')->user()->role == 'kesiswaan')
                    <li><a href="{{ route('kesiswaan.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="{{ route('admin.datasiswa.index') }}"><i class="fas fa-users"></i> Data Siswa</a></li>
                    <li><a href="{{ route('admin.kartupelajar.index') }}"><i class="fas fa-id-card"></i> Kartu Pelajar</a></li>
                    <li class="dropdown {{ request()->is('admin/keterlambatan*') || request()->is('admin/presensi*') ? 'open' : '' }}">
                        <a href="#" class="dropdown-toggle"><i class="fas fa-clipboard-check"></i> Kehadiran <i class="fas fa-caret-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('admin.presensi.dashboard') }}" class="{{ request()->is('admin/presensi*') ? 'active' : '' }}">Presensi</a></li>
                            <li><a href="{{ route('admin.keterlambatan.index') }}">Keterlambatan</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('admin.dokumensiswa.index') }}"><i class="fas fa-file-alt"></i> Dokumen Siswa</a></li>
                    <li><a href="{{ route('admin.prestasi.index') }}"><i class="fas fa-trophy"></i> Prestasi</a></li>
                @endif

                @if(auth('guru')->user()->role == 'guru' && auth('guru')->user()->walikelas != null)
                    <li><a href="{{ route('wali.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="{{ route('wali.datasiswa') }}"><i class="fas fa-users"></i> Data Siswa</a></li>
                    <li><a href="{{ route('wali.kartupelajar.index') }}"><i class="fas fa-id-card"></i> Kartu Pelajar</a></li>
                    <li><a href="{{ route('wali.dokumensiswa') }}"><i class="fas fa-file-alt"></i> Dokumen Siswa</a></li>
                    <li><a href="{{ route('wali.prestasi.index') }}"><i class="fas fa-trophy"></i> Prestasi Siswa</a></li>
                    <li><a href="{{ route('wali.password.index') }}"><i class="fas fa-lock"></i> Password</a></li>
                @endif
            </ul>
       </nav>

       <div class="logout">
            <a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i> Log Out</a>
       </div>
    </div>

    <div class="main-content">
        <div class="navbar">
            <button class="mobile-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h2>@yield('page_title', 'Dashboard Admin')</h2>
            <div class="user-profile">
                <img src="{{ asset('images/profil_admin_tem.jfif') }}" alt="Admin Profile">
            </div>
        </div>

        @yield('content')
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const dropdownToggles = document.querySelectorAll(".dropdown-toggle");
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener("click", function (e) {
                e.preventDefault();
                document.querySelectorAll(".sidebar nav ul li.dropdown").forEach(drop => {
                    if (drop !== this.parentElement) drop.classList.remove("active");
                });
                this.parentElement.classList.toggle("active");
            });
        });
    });

    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('show');
    }

    document.addEventListener('click', function(e) {
        const sidebar = document.querySelector('.sidebar');
        const toggle = document.querySelector('.mobile-toggle');
        if (window.innerWidth <= 991 && sidebar.classList.contains('show')) {
            if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
    </script>
</body>
</html>