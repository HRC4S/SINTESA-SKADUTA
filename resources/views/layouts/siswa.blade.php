<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Siswa | Sintesa')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <link rel="icon" href="{{ asset('images/skaduta_logo.png') }}" type="image/png"/>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <style>
        /* =========================================
           1. RESET & GLOBAL (GAYA ADMIN)
           ========================================= */
        * { box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f4f7f6;
            min-height: 100vh;
        }

        /* =========================================
           2. SIDEBAR STYLES (GAYA ADMIN)
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
            overflow-y: auto;
            z-index: 1050;
            left: 0;
            top: 0;
            transition: transform 0.3s ease;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            -ms-overflow-style: none;  /* IE/Edge */
            scrollbar-width: none;  /* Firefox */
        }
        .sidebar::-webkit-scrollbar { display: none; }

        .sidebar .header { display: flex; align-items: center; padding: 0 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px; }
        .header-logo { width: 40px; height: 40px; margin-right: 10px; }
        .header-title { font-size: 14px; font-weight: 700; color: #fff; margin: 0; white-space: nowrap; }

        .profile-admin { text-align: center; margin-bottom: 20px; }
        .profile-admin-img { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; margin-bottom: 5px; }
        .profile-admin-name { font-size: 14px; font-weight: 600; margin-bottom: 2px; color: #fff; }
        .profile-admin-role { font-size: 12px; opacity: 0.85; color: #ccc; }

        .sidebar nav ul { list-style: none; padding: 0; margin: 0; }
        .sidebar nav ul li { margin-bottom: 5px; }
        .sidebar nav ul li a { display: flex; align-items: center; padding: 12px 20px; color: #fff; text-decoration: none; font-size: 0.95rem; transition: 0.3s; }
        .sidebar nav ul li a:hover, .sidebar nav ul li a.active { background-color: #0d2a4a; border-left: 4px solid #4a90e2; }
        .sidebar nav ul li a i { margin-right: 10px; font-size: 1.1rem; width: 25px; text-align: center; }

        .dropdown-toggle { position: relative; cursor: pointer; }
        .dropdown-toggle i.fa-caret-down { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-size: 0.8rem; }
        .dropdown-menu { display: none; background-color: #0d2a4a; padding: 0; }
        .dropdown.active .dropdown-menu { display: block; }
        .dropdown-menu li a { padding-left: 55px; font-size: 0.9rem; }

        .logout { margin-top: auto; padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .logout a { display: block; text-align: center; padding: 12px; background: #e74c3c; color: #fff; border-radius: 5px; text-decoration: none; transition: background 0.3s; }
        .logout a:hover { background-color: #c0392b; }

        /* =========================================
           3. MAIN CONTENT & NAVBAR (GAYA ADMIN)
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
        .user-profile img { width: 35px; height: 35px; border-radius: 50%; margin-left: 15px; object-fit: cover; }

        .mobile-toggle {
            display: none; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #333; margin-right: 15px; padding: 0;
        }

        /* =========================================
           4. STYLES KHUSUS ALERT SISWA (DIPERTAHANKAN)
           ========================================= */
        @keyframes slideDown {
            0% { transform: translateY(-20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .alert-password {
            background: #fffbe6;
            border-left: 6px solid #facc15;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 25px;
            animation: slideDown 0.5s ease;
            display: flex; align-items: center; justify-content: space-between; position: relative; overflow: hidden;
        }
        .alert-content { display: flex; align-items: center; gap: 15px; width: 100%; }
        .alert-icon { font-size: 28px; color: #d97706; flex-shrink: 0; }
        .alert-text h4 { margin: 0; font-weight: 700; color: #92400e; font-size: 1rem; }
        .alert-text p { margin: 4px 0 8px; color: #78350f; font-size: 0.9rem; }
        .alert-button {
            display: inline-block; background: #facc15; color: #78350f; padding: 6px 12px;
            border-radius: 6px; font-weight: 600; text-decoration: none; transition: all 0.3s; font-size: 0.85rem;
        }
        .alert-button:hover { background: #fbbf24; transform: scale(1.05); }
        .alert-close {
            position: absolute; top: 10px; right: 15px; background: none; border: none;
            color: #92400e; font-size: 20px; cursor: pointer; transition: color 0.3s;
        }
        .alert-close:hover { color: #000; }

        .alert-success {
            background-color: #e6ffed; border-left: 5px solid #22c55e; color: #166534;
            padding: 15px; border-radius: 8px; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;
        }
        .alert-success i { color: #22c55e; }

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
            
            .alert-content { flex-direction: column; align-items: flex-start; }
            .alert-icon { margin-bottom: 10px; }
        }
    </style>
</head>
<body>

    <div class="sidebar">
       <div class="header">
            <img src="{{ asset('images/skaduta_logo.png') }}" alt="Logo SMK" class="header-logo">
            <h3 class="header-title">SINTESA SMKN 2 YOGYAKARTA</h3>
       </div>

       <div class="profile-admin">
            <img src="{{ Auth::guard('siswa')->user() && Auth::guard('siswa')->user()->foto ? asset('uploads/foto_siswa/' . Auth::guard('siswa')->user()->foto) : asset('images/icon pelajar.jpeg') }}" 
                 alt="Foto Siswa" class="profile-admin-img" />
            <div class="profile-admin-text">
                <h4 class="profile-admin-name">{{ Auth::guard('siswa')->user()->nama_lengkap ?? 'Siswa' }}</h4>
                <p class="profile-admin-role">{{ 
                ['admin' => 'Administrator', 'koor_kelas' => 'Koordinator Kelas']
                [Auth::guard('siswa')->user()->role ?? 'Siswa'] ?? 'Siswa' }}</p>
            </div>
       </div>

       <nav>
            <ul>
                <li>
                    <a href="{{ route('siswa.dashboard') }}" class="{{ request()->is('siswa/dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                @if(Auth::guard('siswa')->check() && strtolower(Auth::guard('siswa')->user()->role) === 'koor_kelas')
                <li>
                    <a href="{{ route('siswa.presensi.koor.create') }}" class="{{ request()->is('siswa/presensi-koor*') ? 'active' : '' }}">
                        <i class="fas fa-user-check"></i> Input Presensi Kelas
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('siswa.datasiswa') }}" class="{{ request()->is('siswa/datasiswa') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate"></i> Data Siswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.orangtua') }}" class="{{ request()->is('siswa/orangtua*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Data Orang Tua
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.kartupelajar.index') }}" class="{{ request()->is('siswa/kartu-pelajar') ? 'active' : '' }}">
                        <i class="fas fa-id-card"></i> Kartu Pelajar
                    </a>
                </li>

                <li class="dropdown {{ request()->is('siswa/konseling*') || request()->is('siswa/keterlambatan*') ? 'active' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-comments"></i> Bimbingan Konseling <i class="fas fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('siswa.presensi.index') }}">Presensi Saya</a></li>
                        <li><a href="{{ route('siswa.konseling.index') }}">Konseling</a></li>
                        <li><a href="{{ route('siswa.keterlambatan.index') }}">Keterlambatan</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('siswa.dokumensiswa') }}" class="{{ request()->is('siswa/dokumensiswa*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i> Dokumen Siswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.prestasi.index') }}" class="{{ request()->is('siswa/prestasi*') ? 'active' : '' }}">
                        <i class="fas fa-trophy"></i> Prestasi Siswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.password.edit') }}" class="{{ request()->is('siswa/password*') ? 'active' : '' }}">
                        <i class="fas fa-key"></i> Ubah Password
                    </a>
                </li>
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
            <h2>@yield('page_title', 'Dashboard Siswa')</h2>
            <div class="user-profile">
                <img src="{{ Auth::guard('siswa')->user() && Auth::guard('siswa')->user()->foto ? asset('uploads/foto_siswa/' . Auth::guard('siswa')->user()->foto) : asset('images/icon pelajar.jpeg') }}" 
                     alt="Foto Siswa" />
            </div>
        </div>

        @if(Auth::guard('siswa')->check() && Auth::guard('siswa')->user()->is_default_password)
        <div id="alert-password" class="alert-password">
            <div class="alert-content">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="alert-text">
                    <h4>Peringatan Keamanan!</h4>
                    <p>Kamu masih menggunakan password default. Segera ubah untuk keamanan akunmu.</p>
                    <a href="{{ route('siswa.password.edit') }}" class="alert-button">Ganti Password Sekarang</a>
                </div>
                <button class="alert-close" onclick="closeAlert()">×</button>
            </div>
        </div>
        @endif

        @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @yield('content')
    </div>

    <script>
    // JS untuk Dropdown Sidebar
    document.addEventListener("DOMContentLoaded", function () {
        const dropdownToggles = document.querySelectorAll(".dropdown-toggle");
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener("click", function (e) {
                e.preventDefault();
                const parentLi = this.parentElement;

                // Tutup dropdown lain
                document.querySelectorAll(".sidebar nav ul li.dropdown").forEach(drop => {
                    if (drop !== parentLi) drop.classList.remove("active");
                });
                
                // Toggle dropdown ini
                parentLi.classList.toggle("active");
            });
        });
    });

    // JS untuk Toggle Sidebar Mobile
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('show');
    }

    // Auto close sidebar di mobile saat klik luar
    document.addEventListener('click', function(e) {
        const sidebar = document.querySelector('.sidebar');
        const toggle = document.querySelector('.mobile-toggle');
        if (window.innerWidth <= 991 && sidebar.classList.contains('show')) {
            if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        }
    });

    // JS untuk Alert Password
    function closeAlert() {
        const alertBox = document.getElementById('alert-password');
        alertBox.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        alertBox.style.opacity = '0';
        alertBox.style.transform = 'translateY(-10px)';
        setTimeout(() => alertBox.remove(), 400);
    }
    
    // Auto-hide alert setelah 10 detik (Opsional)
    setTimeout(() => {
        const alertBox = document.getElementById('alert-password');
        if (alertBox) closeAlert();
    }, 10000);
    </script>
</body>
</html>
