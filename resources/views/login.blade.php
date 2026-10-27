<!DOCTYPE html> 
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SINTESA - Login Pengguna SMK N 2 Yogyakarta</title>
    <link rel="icon" href="{{ asset('images/skaduta_logo.png') }}" type="image/png"/>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js']) 

    <style>
        /* Global Font Poppins */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f7f9fc;
            -webkit-font-smoothing: antialiased;
        }

        .login-container {
            display: flex;
            min-height: 100vh;
        }

        .image-side {
            flex: 1;
            background-image: url('/images/smk2_yogyakarta.png');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: flex-end;
            padding: 2.5rem;
            color: white;
            position: relative;
        }

        .image-side::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0) 50%);
        }

        .image-text {
            position: relative;
            z-index: 10;
            line-height: 1.2;
        }

        .image-text h1 { font-size: 1.6rem; font-weight: 700; }
        .image-text p { font-size: 1.1rem; }

        .form-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .form-card { 
            width: 100%; 
            max-width: 420px; 
        }

        .header-text {
            color: #1e3a67;
            font-size: 1.7rem;
            font-weight: 700;
            text-align: center;
        }

        .text-sintesa {
            color: #1e3a67;
        }

        .input-wrapper { 
            position: relative; 
        }

        .input-field-custom {
            background-color: #e6f0ff;
            border: 1px solid #d4e2ff;
            border-radius: 0.375rem;
            padding: 0.75rem 1rem 0.75rem 3rem; /* padding left untuk icon */
            width: 100%;
            height: 3.2rem;
            font-size: 1rem;
            transition: all 0.2s;
            font-family: 'Poppins', sans-serif;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #1e3a67;
            font-size: 1.2rem;
            z-index: 5;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            width: 22px;
            height: 22px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .password-toggle svg {
            width: 22px;
            height: 22px;
            stroke: #1e3a67;
        }

        .login-button-custom {
            background-color: #1e3a67;
            color: white;
            padding: 0.8rem 0;
            border-radius: 0.375rem;
            font-weight: 700;
            width: 100%;
            margin-top: 1.5rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
        }

        .login-button-custom:hover { 
            background-color: #2a4c7e; 
        }

        /* --- RESPONSIVITAS --- */
        @media (max-width: 1024px) {
            .image-side { display: none; }
            .form-side { flex: 100%; padding: 1.5rem; }
        }

        @media (max-width: 480px) {
            .header-text {
                font-size: 1.4rem;
            }
            .form-card {
                max-width: 100%;
            }
            .login-button-custom {
                padding: 1rem 0;
            }
        }
    </style>
</head>
<body class="antialiased">

<div class="login-container">
    {{-- Sisi Kiri: Gambar Gedung --}}
    <div class="image-side">
        <div class="image-text">
            </div>
    </div>

    {{-- Sisi Kanan: Form Login --}}
    <div class="form-side">
        <div class="form-card">
            
            <p style="text-align: left; margin-bottom: 0.5rem; font-size: 1rem;">Selamat Datang di</p>
            
            <div class="header-text" style="margin-bottom: 1rem;">
                <span class="text-sintesa">RISET SINTESA</span> SMKN 2 YOGYAKARTA
            </div>
            
            <div style="text-align: center; margin-bottom: 2rem;">
                <img src="{{ asset('images/skaduta_logo.png') }}" alt="Logo SMK N 2" style="width: 120px; height: auto; display: inline-block;">
            </div>

            <p style="text-align: center; margin-bottom: 2rem; font-size: 1.1rem; font-weight: 500;">
                Silahkan Login Untuk Melanjutkan
            </p>

            @if (session('status'))
                <div style="margin-bottom: 1rem; font-size: 0.875rem; color: #16a34a; font-weight: 500;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.process') }}">
                @csrf

                {{-- Input NIP/NIS --}}
                <div style="margin-bottom: 1rem;">
                    <label for="username" style="display: block; margin-bottom: 0.3rem; font-weight: 500;">NIP / NIS</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span> 
                        <input id="username" class="input-field-custom" type="text" name="username" 
                            value="{{ old('username') }}" required autofocus autocomplete="username" 
                            placeholder="Masukkan NIP atau NIS">
                    </div>
                    @error('username')
                        <p style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input Password --}}
                <div style="margin-top: 1rem;">
                    <label for="password" style="display: block; margin-bottom: 0.3rem; font-weight: 500;">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>

                        <input id="password" 
                               class="input-field-custom" 
                               type="password" 
                               name="password" 
                               required autocomplete="current-password" 
                               placeholder="Masukkan Password">

                        <span class="password-toggle" id="togglePassword">
                            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>

                            <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                                 stroke-width="2" style="display:none;">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 3l18 18M10.73 5.08A9.72 9.72 0 0112 4.5c6 0 9.75 7.5 9.75 7.5a18.7 18.7 0 01-3.63 4.78M6.13 6.16A18.8 18.8 0 002.25 12s3.75 7.5 9.75 7.5c1.1 0 2.15-.23 3.13-.67" />
                            </svg>
                        </span>
                    </div>

                    @error('password')
                        <p style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="text-align: right; margin-top: 0.75rem;">
                    <a href="{{ route('password.request') }}" style="color:#1e3a67; font-size: 0.85rem; text-decoration: underline;">
                        Lupa Password?
                    </a>
                </div>

                <div style="display: flex; align-items: center; justify-content: flex-end; margin-top: 1rem;">
                    <button type="submit" class="login-button-custom">
                        Log In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const pass = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    if (pass.type === "password") {
        pass.type = "text";
        eyeOpen.style.display = "none";
        eyeClosed.style.display = "block";
    } else {
        pass.type = "password";
        eyeOpen.style.display = "block";
        eyeClosed.style.display = "none";
    }
});
</script>

</body>
</html>