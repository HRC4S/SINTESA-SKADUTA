@extends('layouts.admin')

@section('title','Ubah Password')
@section('page_title','Ubah Password')

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
.password-container input {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    margin-bottom: 16px;
}
.btn-primary {
    background-color: #1e3a8a;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    color: white;
    font-weight: 600;
    width: 100%;
}
.alert {
    background: #fee2e2;
    color: #b91c1c;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
}
</style>

<div class="password-container">
    <h3>Ubah Password - {{ $siswa->nama_lengkap }}</h3>

    {{-- Error --}}
    @if ($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
    @endif

    {{-- SUCCESS --}}
    @if (session('success'))
        <div class="alert" style="background:#d4edda; color:#155724;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('wali.password.update', $siswa->nis) }}">
        @csrf

        <label>Password Baru</label>
        <input type="password" name="password">

        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation">

        <button class="btn-primary">Simpan</button>
    </form>
</div>

@endsection
