@extends('layouts.auth')

@section('title', 'Lupa Password')
@section('page_title', 'Lupa Password')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-md rounded-2xl p-8 mt-6">
    <h2 class="text-center text-xl font-semibold text-[#1e3a8a] mb-5">Reset Password Siswa</h2>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('siswa.password.reset') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="block text-gray-700 font-medium">NIS</label>
            <input type="text" name="nis" placeholder="Masukkan NIS kamu"
                   class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a8a] focus:outline-none">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Password Baru</label>
            <div class="relative">
                <input type="password" name="password_baru" id="password_baru"
                       class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a8a] focus:outline-none pr-10"
                       placeholder="Masukkan password baru">
                <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer" onclick="togglePassword('password_baru', 'icon1')">
                    <i id="icon1" class="fa fa-eye text-gray-500"></i>
                </span>
            </div>
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Konfirmasi Password Baru</label>
            <div class="relative">
                <input type="password" name="password_baru_confirmation" id="password_baru_confirmation"
                       class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a8a] focus:outline-none pr-10"
                       placeholder="Ulangi password baru">
                <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer" onclick="togglePassword('password_baru_confirmation', 'icon2')">
                    <i id="icon2" class="fa fa-eye text-gray-500"></i>
                </span>
            </div>
        </div>

        <button type="submit"
                class="w-full bg-[#1e3a8a] text-white py-2 rounded-lg font-semibold hover:bg-[#243b84] transition duration-300">
            Simpan Perubahan
        </button>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-[#1e3a8a] hover:underline text-sm">Kembali ke Login</a>
        </div>
        
    </form>
</div>

<script>
function togglePassword(id, iconId) {
    const input = document.getElementById(id);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endsection
