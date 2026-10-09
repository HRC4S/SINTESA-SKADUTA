@extends('layouts.siswa')

@section('title', 'Ubah Password')
@section('page_title', 'Ubah Password')

@section('content')
<style>
.password-container {
    max-width: 500px;
    margin: 40px auto;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    padding: 25px 30px;
    font-family: 'Poppins', sans-serif;
}
.password-container h3 {
    color: #1e3a8a;
    text-align: center;
    font-weight: 600;
    margin-bottom: 25px;
}
.password-container label {
    font-weight: 600;
    color: #374151;
}
.password-container input {
    width: 100%;
    padding: 10px 40px 10px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 15px;
    margin-top: 6px;
    margin-bottom: 16px;
    outline: none;
    transition: 0.3s;
    box-sizing: border-box;
}
.password-container input:focus {
    border-color: #1e3a8a;
    box-shadow: 0 0 5px rgba(30,58,138,0.3);
}
.password-toggle {
    position: relative;
}
.password-toggle i {
    position: absolute;
    top: 50%;
    right: 12px;
    transform: translateY(-50%);
    cursor: pointer;
    color: #6b7280;
}
.btn-primary {
    background-color: #1e3a8a;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    color: white;
    font-weight: 600;
    width: 100%;
    transition: 0.3s;
}
.btn-primary:hover {
    background-color: #3b82f6;
}
.alert {
    background: #fee2e2;
    border: 1px solid #f87171;
    color: #b91c1c;
    padding: 8px;
    border-radius: 6px;
    margin-bottom: 15px;
}
.success {
    background: #dcfce7;
    border: 1px solid #22c55e;
    color: #166534;
    padding: 8px;
    border-radius: 6px;
    margin-bottom: 15px;
}

.forgot-password {
    width: 100%;
    display: flex;
    justify-content: center;
}

</style>

<div class="password-container">
    <h3>Ubah Password</h3>

    {{-- Pesan error / sukses --}}
    @if ($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
    @endif

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    {{-- Form Update Password --}}
    <form action="{{ route('siswa.password.update') }}" method="POST">
        @csrf
        @method('POST') {{-- pastikan route update pakai POST, bukan PUT --}}

        {{-- Password Lama --}}
        <label for="current_password">Password Lama</label>
        <div class="password-toggle">
            <input type="password" name="current_password" id="current_password" placeholder="Masukkan password lama" required>
            <i class="fa fa-eye" id="eyeOld" onclick="togglePassword('current_password', 'eyeOld')"></i>
        </div>

        {{-- Password Baru --}}
        <label for="new_password">Password Baru</label>
        <div class="password-toggle">
            <input type="password" name="new_password" id="new_password" placeholder="Masukkan password baru" required>
            <i class="fa fa-eye" id="eyeNew" onclick="togglePassword('new_password', 'eyeNew')"></i>
        </div>

        {{-- Konfirmasi Password Baru --}}
        <label for="new_password_confirmation">Konfirmasi Password Baru</label>
        <div class="password-toggle">
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" placeholder="Konfirmasi password baru" required>
            <i class="fa fa-eye" id="eyeConfirm" onclick="togglePassword('new_password_confirmation', 'eyeConfirm')"></i>
        </div>

        <button type="submit" class="btn-primary">Simpan Perubahan</button>
        <div class="forgot-password">
            <a href="{{ route('password.request') }}">Lupa Password?</a>
        </div>
    </form>
</div>

{{-- Font Awesome & Script --}}
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId); 
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
