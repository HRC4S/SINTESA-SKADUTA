@extends('layouts.admin')

@section('title', 'Detail Siswa')
@section('page_title', 'Data Lengkap Siswa')

@section('content')

<style>
    /* ===========================
       CONTAINER & HEADER
       =========================== */
    .detail-wrapper {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05); /* Sedikit shadow halus */
    }

    .header-bio {
        background: #4a7eb0;
        color: white;
        padding: 10px 15px;
        border-radius: 6px 6px 0 0;
    }

    .header-bio h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .content-bio {
        padding: 30px;
        background: #f5f6fa;
        border-radius: 0 0 6px 6px;
    }

    /* ===========================
       FOTO PROFIL
       =========================== */
    .foto-section {
        text-align: center;
        margin-bottom: 30px;
    }

    .foto-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 2px solid #ccc;
        object-fit: cover;
    }

    .btn-ubah-foto {
        background: #4a7eb0;
        color: white;
        padding: 6px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 13px;
        margin-top: 15px;
    }

    /* ===========================
       FORM GRID (RESPONSIF)
       =========================== */
    .bio-grid {
        display: grid;
        grid-template-columns: 1fr; /* Default Mobile: 1 Kolom */
        gap: 20px;
    }

    /* Media Query: Tablet & Desktop jadi 2 Kolom */
    @media (min-width: 768px) {
        .bio-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    /* ===========================
       INPUT STYLES
       =========================== */
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #e9ecef; /* Warna input readonly */
        box-sizing: border-box; /* Agar padding tidak melebar keluar */
    }

    /* ===========================
       BUTTON BACK
       =========================== */
    .btn-back {
        background: #2c3e50;
        color: white;
        padding: 8px 15px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
    }

    /* Mobile Adjustment */
    @media (max-width: 768px) {
        .detail-wrapper { padding: 10px; }
        .content-bio { padding: 20px 15px; }
        .btn-back { width: 100%; text-align: center; display: block; }
    }
</style>

<div class="detail-wrapper">

    <div class="header-bio">
        <h4>BIODATA SISWA</h4>
    </div>

    <div class="content-bio">

        <div class="foto-section">
            <img src="{{ asset('images/icon pelajar.jpeg') }}" alt="Foto Siswa" class="foto-img">
            <br>
            <button class="btn-ubah-foto">Ubah Foto</button>
        </div>

        <div class="bio-grid">
            
            <div class="form-group">
                <label>Nomor Induk Siswa</label>
                <input type="text" class="form-control" value="{{ $siswa->nis ?? '25101757' }}" readonly>
            </div>
            
            <div class="form-group">
                <label>Nomor Induk Siswa Nasional</label>
                <input type="text" class="form-control" value="{{ $siswa->nisn ?? '0088576978' }}" readonly>
            </div>

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" value="{{ $siswa->nama_lengkap ?? 'ADINATA ROYYAN ALFAROBBY' }}" readonly>
            </div>

            <div class="form-group">
                <label>Tempat Lahir</label>
                <input type="text" class="form-control" value="{{ $siswa->tempat_lahir ?? 'Sleman' }}" readonly>
            </div>

            <div class="form-group">
                <label>Jenis Kelamin</label>
                <input type="text" class="form-control" value="{{ $siswa->jenis_kelamin ?? 'Laki-laki' }}" readonly>
            </div>

            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="text" class="form-control" value="{{ $siswa->tanggal_lahir ?? '31 Agustus 2009' }}" readonly>
            </div>

            <div class="form-group">
                <label>ROMBEL</label>
                <input type="text" class="form-control" value="{{ $siswa->rombel ?? 'X DKV 1' }}" readonly>
            </div>

            <div class="form-group">
                <label>Kompetensi Keahlian</label>
                <input type="text" class="form-control" value="{{ $siswa->jurusan ?? 'Desain Komunikasi Visual' }}" readonly>
            </div>

            <div class="form-group">
                <label>Agama</label>
                <input type="text" class="form-control" value="{{ $siswa->agama ?? 'Islam' }}" readonly>
            </div>

            <div class="form-group">
                <label>Email Aktif</label>
                <input type="text" class="form-control" value="{{ $siswa->email ?? 'alfarobby1@gmail.com' }}" readonly>
            </div>

            {{-- Full Width untuk Alamat / No HP jika ganjil --}}
            <div class="form-group">
                <label>No HP/WA</label>
                <input type="text" class="form-control" value="{{ $siswa->no_hp ?? '085727114070' }}" readonly>
            </div>

        </div>

        <div style="margin-top:30px;">
            <a href="{{ route('admin.datasiswa') }}" class="btn-back">Kembali</a>
        </div>

    </div>
</div>

@endsection