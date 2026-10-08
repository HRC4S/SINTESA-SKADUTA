@extends('layouts.auth')

@section('title', 'Reset Password')
@section('page_title', 'Reset Password')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-md rounded-2xl p-8 mt-6">
    <h2 class="text-center text-xl font-semibold text-[#1e3a8a] mb-5">Buat Password Baru</h2>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="guard" value="{{ $guard }}">
        <input type="hidden" name="identifier" value="{{ $identifier }}">

        <div>
            <label class="block text-gray-700 font-medium">Password Baru</label>
            <div class="password-toggle relative">
                <input type="password" name="password" id="password" required minlength="8"
                       class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a8a] focus:outline-none pr-10"
                       placeholder="Minimal 8 karakter">
                <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer" style="top: 4px;" onclick="togglePassword('password', 'iconPassword')">
                    <i id="iconPassword" class="fa fa-eye text-gray-500"></i>
                </span>
            </div>
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Konfirmasi Password Baru</label>
            <div class="password-toggle relative">
                <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                       class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a8a] focus:outline-none pr-10"
                       placeholder="Ulangi password baru">
                <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer" style="top: 4px;" onclick="togglePassword('password_confirmation', 'iconConfirm')">
                    <i id="iconConfirm" class="fa fa-eye text-gray-500"></i>
                </span>
            </div>
        </div>

        <button type="submit"
                class="w-full bg-[#1e3a8a] text-white py-2 rounded-lg font-semibold hover:bg-[#243b84] transition duration-300">
            Simpan Password Baru
        </button>
    </form>
</div>

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
