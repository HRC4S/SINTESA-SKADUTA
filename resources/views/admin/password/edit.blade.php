@extends('layouts.admin')

@section('title','Ubah Password')
@section('page_title','Ubah Password')

@section('content')

{{-- Load Font Awesome (CDN Standar) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* ============================
       BASE STYLES (DESKTOP)
       ============================ */
    .password-container {
        width: 90%; /* Agar responsif di layar < 500px */
        max-width: 500px; /* Batas lebar di desktop */
        margin: 40px auto;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 30px;
        font-family: 'Poppins', sans-serif;
        box-sizing: border-box;
    }

    .password-container h3 {
        color: #1e3a8a;
        text-align: center;
        font-weight: 600;
        margin-bottom: 25px;
        font-size: 1.5rem;
    }

    .password-container label {
        font-weight: 600;
        color: #374151;
        font-size: 0.95rem;
        display: block;
        margin-bottom: 5px;
    }

    .password-container input {
        width: 100%;
        padding: 12px 45px 12px 15px; /* Padding kanan lebih besar untuk ikon */
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: 0.3s;
        box-sizing: border-box; /* Penting agar padding tidak melebarkan input */
        background: #f8fafc;
    }

    .password-container input:focus {
        border-color: #1e3a8a;
        box-shadow: 0 0 0 3px rgba(30,58,138,0.1);
        background: #fff;
    }

    /* Wrapper Input Password */
    .password-toggle {
        position: relative;
        margin-bottom: 20px;
    }

    .password-toggle i {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #94a3b8;
        transition: 0.2s;
        font-size: 1.1rem;
    }

    .password-toggle i:hover {
        color: #1e3a8a;
    }

    /* Tombol Simpan */
    .btn-primary {
        background-color: #1e3a8a;
        border: none;
        padding: 12px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        width: 100%;
        transition: 0.3s;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 10px;
    }

    .btn-primary:hover {
        background-color: #1e40af;
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2);
    }

    /* Alert Error */
    .alert {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #b91c1c;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 0.9rem;
    }

    /* TOAST SUCCESS */
    .toast-success {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #22c55e;
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        font-weight: 600;
        z-index: 9999;
        opacity: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: fadeInOut 4s ease forwards;
    }

    @keyframes fadeInOut {
        0% { opacity: 0; transform: translateY(-20px); }
        10% { opacity: 1; transform: translateY(0); }
        90% { opacity: 1; transform: translateY(0); }
        100% { opacity: 0; transform: translateY(-20px); }
    }

    /* ============================
       RESPONSIVE (MOBILE)
       ============================ */
    @media (max-width: 600px) {
        .password-container {
            margin: 20px auto; /* Margin atas bawah lebih kecil */
            padding: 20px; /* Padding dalam lebih kecil */
            width: 95%; /* Hampir full width di HP */
        }

        .password-container h3 {
            font-size: 1.25rem; /* Ukuran font judul dikecilkan */
            margin-bottom: 20px;
        }

        .toast-success {
            top: 20px;
            right: 5%; /* Tengah di mobile */
            left: 5%;
            width: 90%;
            text-align: center;
            justify-content: center;
        }
    }
</style>

<div class="password-container">
    <h3><i class="fas fa-lock" style="margin-right: 8px;"></i> Ubah Password</h3>
    
    <p style="text-align: center; color: #64748b; font-size: 0.9rem; margin-bottom: 20px; margin-top: -15px;">
        {{ $user->nama ?? $user->nama_lengkap }}
    </p>

    {{-- Error --}}
    @if ($errors->any())
        <div class="alert">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    @endif

    {{-- SUCCESS (Toast) --}}
    @if (session('success'))
        <div id="toastSuccess" class="toast-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.password.update', [$type, $user->getKey()]) }}">
        @csrf

        <label for="password">Password Baru</label>
        <div class="password-toggle">
            <input type="password" name="password" id="password" placeholder="Masukkan password baru" required>
            <i class="fa fa-eye" onclick="togglePassword('password', this)"></i>
        </div>

        <label for="password_confirmation">Konfirmasi Password</label>
        <div class="password-toggle">
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi password baru" required>
            <i class="fa fa-eye" onclick="togglePassword('password_confirmation', this)"></i>
        </div>

        <button type="submit" class="btn-primary">Simpan Perubahan</button>
    </form>
</div>

<script>
function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

@endsection