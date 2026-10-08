@extends('layouts.admin')

@section('title', 'Tambah Role')
@section('page_title', 'Tambah Role')

@section('content')

{{-- Load FontAwesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

    body { font-family: 'Poppins', sans-serif; }

    .form-wrapper {
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin: 20px auto;
        max-width: 850px; /* Lebar maksimal agar tidak terlalu lebar di desktop */
    }

    /* HEADER */
    .form-header {
        background: #123B6B; /* Biru Konsisten */
        color: white;
        font-weight: 600;
        padding: 18px 25px;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 3px solid #0a2240;
    }

    /* BODY */
    .form-body { padding: 30px 40px; }

    .form-group { margin-bottom: 20px; }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        display: block;
        font-size: 0.95rem;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 12px 15px;
        font-size: 14px;
        width: 100%;
        box-sizing: border-box; /* Penting agar padding tidak melebarkan input */
        transition: 0.3s;
        background-color: #f9fafb;
    }

    .form-control:focus {
        border-color: #123B6B;
        background-color: #fff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(18, 59, 107, 0.1);
    }

    /* GRID SYSTEM (Responsive) */
    .row-grid {
        display: grid;
        grid-template-columns: 1fr 1fr; /* 2 Kolom di Desktop */
        gap: 25px;
    }

    /* BUTTONS */
    .btn-container {
        display: flex;
        justify-content: flex-end; /* Rata kanan di desktop */
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: 0.2s;
    }

    .btn-submit { background: #123B6B; color: white; }
    .btn-submit:hover { background: #0f2e52; box-shadow: 0 4px 10px rgba(18, 59, 107, 0.2); }

    .btn-back { background: #e5e7eb; color: #374151; }
    .btn-back:hover { background: #d1d5db; color: #111; }

    /* ===========================
       MEDIA QUERIES (MOBILE)
       =========================== */
    @media (max-width: 768px) {
        .form-wrapper { margin: 10px; width: auto; }
        
        .form-body { padding: 25px; }

        .row-grid {
            grid-template-columns: 1fr; /* Stack jadi 1 kolom di HP */
            gap: 15px;
        }

        .btn-container {
            flex-direction: column-reverse; /* Tombol Simpan di atas */
        }

        .btn {
            width: 100%; /* Tombol lebar penuh */
        }
    }
</style>

<div class="form-wrapper">
    
    {{-- HEADER --}}
    <div class="form-header">
        <i class="fas fa-user-plus"></i> TAMBAH ROLE PENGGUNA
    </div>

    {{-- BODY --}}
    <div class="form-body">
        <form action="{{ route('admin.role.store') }}" method="POST">
            @csrf
            
            <div class="row-grid">
                
                {{-- Nama Pengguna --}}
                <div class="form-group">
                    <label class="form-label">Nama Pengguna <span style="color:red">*</span></label>
                    <input type="text" name="nama_pengguna" class="form-control" placeholder="Masukkan nama lengkap" required>
                </div>

                {{-- NIP/NIS --}}
                <div class="form-group">
                    <label class="form-label">NIP / NIS <span style="color:red">*</span></label>
                    <input type="text" name="nip_nis" class="form-control" placeholder="Nomor Induk Pegawai / Siswa" required>
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label class="form-label">Email <span style="color:red">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="contoh@sekolah.sch.id" required>
                </div>

                {{-- Role Selection --}}
                <div class="form-group">
                    <label class="form-label">Role Pengguna <span style="color:red">*</span></label>
                    <select name="role" class="form-control" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin">Admin</option>
                        <option value="guru_bk">Guru BK</option>
                        <option value="guru">Guru</option>
                        <option value="kesiswaan">Kesiswaan</option>
                        <option value="Siswa">Siswa</option>
                    </select>
                </div>

            </div>

            {{-- Tombol Aksi --}}
            <div class="btn-container">
                <a href="{{ route('admin.role.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
            </div>

        </form>
    </div>
</div>

@endsection