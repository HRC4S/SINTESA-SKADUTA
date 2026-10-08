@extends('layouts.admin') {{-- sesuaikan dengan layout yang kamu pakai --}}

@section('title', 'Dashboard Guru')

@section('content')
<div class="container mt-4">

    <h3 class="mb-3">Dashboard Guru</h3>

    <div class="alert alert-primary">
        Selamat datang, <b>{{ auth('guru')->user()->nama }}</b>!  
    </div>

    <div class="row">

        {{-- Menu Data Siswa --}}
        <div class="col-md-4 mb-3">
            <a href="#" class="text-decoration-none">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5>Data Siswa</h5>
                        <p class="text-muted">Lihat data siswa</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Menu Wali Kelas (Jika guru menjadi wali kelas) --}}
        @if(auth('guru')->user()->walikelas)
        <div class="col-md-4 mb-3">
            <a href="{{ route('wali.dashboard') }}" class="text-decoration-none">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5>Wali Kelas</h5>
                        <p class="text-muted">Lihat siswa kelas {{ auth('guru')->user()->walikelas }}</p>
                    </div>
                </div>
            </a>
        </div>
        @endif

        {{-- Menu lainnya --}}
        <div class="col-md-4 mb-3">
            <a href="#" class="text-decoration-none">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5>Profil</h5>
                        <p class="text-muted">Lihat & edit data Anda</p>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>
@endsection
