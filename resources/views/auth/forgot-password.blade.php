@extends('layouts.auth')

@section('title', 'Lupa Password')
@section('page_title', 'Lupa Password')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-md rounded-2xl p-8 mt-6">
    <h2 class="text-center text-xl font-semibold text-[#1e3a8a] mb-2">Lupa Password</h2>
    <p class="text-center text-gray-500 text-sm mb-5">
        Masukkan NIP (Guru) atau NIS (Siswa) kamu. Kalau akunnya terdaftar dan punya email aktif,
        kami akan kirim link reset password ke email tersebut.
    </p>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="block text-gray-700 font-medium">NIP / NIS</label>
            <input type="text" name="identifier" value="{{ old('identifier') }}"
                   placeholder="Masukkan NIP atau NIS kamu"
                   class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a8a] focus:outline-none">
        </div>

        <button type="submit"
                class="w-full bg-[#1e3a8a] text-white py-2 rounded-lg font-semibold hover:bg-[#243b84] transition duration-300">
            Kirim Link Reset Password
        </button>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-[#1e3a8a] hover:underline text-sm">Kembali ke Login</a>
        </div>
    </form>
</div>
@endsection
