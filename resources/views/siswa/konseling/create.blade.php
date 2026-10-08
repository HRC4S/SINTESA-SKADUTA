@extends('layouts.siswa')
@section('title', 'Buat Janji Konseling')
@section('page_title', 'Pengajuan Konseling')

@section('content')

{{-- Load FontAwesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    /* ---------------------------------
       MAIN CARD STYLE
    ------------------------------------*/
    .card-konseling {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        max-width: 100%;
        margin: 0 auto;
    }

    .section-title {
        background-color: #1e3a67; /* Warna Biru SINTESA */
        color: white;
        padding: 15px 25px;
        font-weight: 600;
        font-size: 1.1rem;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-body {
        padding: 30px 50px;
    }

    /* ---------------------------------
       INPUT STYLE
    ------------------------------------*/
    label {
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        margin-bottom: 6px;
        display: block;
    }

    input, select, textarea {
        width: 100%;
        padding: 10px 12px;
        border-radius: 6px;
        border: 1px solid #ccc;
        background: #fff;
        font-size: 0.95rem;
        box-sizing: border-box;
        transition: 0.3s;
    }

    input:focus, select:focus, textarea:focus {
        border-color: #1e3a67;
        outline: none;
        box-shadow: 0 0 0 3px rgba(30, 58, 103, 0.1);
    }

    .input-readonly {
        background-color: #f0f2f5;
        color: #6b7280;
        cursor: not-allowed;
        border-color: #e5e7eb;
    }

    /* ---------------------------------
       GRID LAYOUT (RESPONSIF)
    ------------------------------------*/
    .form-row {
        display: grid;
        grid-template-columns: 1fr; /* Default HP: 1 Kolom */
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-row-3 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    /* Media Query: Tablet & Desktop */
    @media (min-width: 768px) {
        .form-row { grid-template-columns: repeat(2, 1fr); } /* 2 Kolom */
        .form-row-3 { grid-template-columns: repeat(3, 1fr); } /* 3 Kolom */
    }

    /* ---------------------------------
       SECTION DIVIDER
    ------------------------------------*/
    .form-divider {
        border-bottom: 2px dashed #e5e7eb;
        margin: 30px 0;
        position: relative;
    }
    .divider-label {
        position: absolute;
        top: -12px;
        left: 0;
        background: #fff;
        padding-right: 15px;
        font-weight: 700;
        color: #1e3a67;
        font-size: 0.95rem;
        text-transform: uppercase;
    }

    /* ---------------------------------
       BUTTONS
    ------------------------------------*/
    .btn-blue {
        background-color: #1e3a67;
        color: white;
        padding: 12px 25px;
        border-radius: 6px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
    }
    .btn-blue:hover { background-color: #0056b3; transform: translateY(-2px); }

    .btn-gray {
        background-color: #6c757d;
        color: white;
        padding: 12px 25px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
    }
    .btn-gray:hover { background-color: #5a6268; }

    /* Mobile Padding Fix */
    @media (max-width: 768px) {
        .form-body { padding: 20px; }
        .btn-blue, .btn-gray { width: 100%; justify-content: center; margin-bottom: 10px; }
        .form-row-3 { grid-template-columns: 1fr; }
    }
</style>

<div class="card-konseling">
    <div class="section-title">
        <span><i class="fas fa-edit"></i> FORM PENGAJUAN KONSELING</span>
        <span style="font-size: 0.9rem; background: rgba(255,255,255,0.2); padding: 3px 10px; border-radius: 15px;">
            {{ now()->format('d M Y') }}
        </span>
    </div>

    <div class="form-body">
        <form action="{{ route('siswa.konseling.store') }}" method="POST">
            @csrf

            {{-- 1. DATA SISWA (Otomatis Terisi) --}}
            <div class="form-divider">
                <span class="divider-label">Data Siswa</span>
            </div>

            <div class="form-row">
                <div>
                    <label>Nama Siswa</label>
                    <input type="text" name="nama_siswa" value="{{ $siswa->nama_lengkap }}" class="input-readonly" readonly>
                </div>
                <div>
                    <label>NIS</label>
                    <input type="text" name="nis" value="{{ $siswa->nis }}" class="input-readonly" readonly>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label>Kelas</label>
                    <input type="text" name="kelas" value="{{ $siswa->rombel }}" class="input-readonly" readonly>
                </div>
                <div>
                    {{-- Input Hidden untuk Data Ortu agar tetap tersimpan ke database --}}
                    <input type="hidden" name="nama_ortu" value="{{ $siswa->nama_ortu ?? '-' }}">
                    <input type="hidden" name="alamat_ortu" value="{{ $siswa->alamat ?? '-' }}">
                    <input type="hidden" name="no_telp_ortu" value="{{ $siswa->orangTua->no_telp_ayah ?? '-' }}">
                    
                    <label>Wali Kelas</label>
                    <input type="text" value="{{ $waliKelas ?? '-' }}" class="input-readonly" readonly>
                </div>
            </div>

            {{-- 2. JADWAL & KONSELOR (Fitur Tambahan) --}}
            <div class="form-divider">
                <span class="divider-label">Rencana Jadwal</span>
            </div>

            <div class="form-row-3">
                <div>
                    <label>Pilih Guru BK <span style="color:red">*</span></label>
                    <select name="guru_bk_nip" required>
                        <option value="">-- Pilih Konselor --</option>
                        {{-- Loop Data Guru yang Role-nya 'guru_bk' dari tabel gurus --}}
                        @foreach($guruBk as $guru)
                            <option value="{{ $guru->nip }}">{{ $guru->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    {{-- Kolom 'tanggal' di database --}}
                    <label>Tanggal Diinginkan <span style="color:red">*</span></label>
                    <input type="date" name="tanggal" min="{{ date('Y-m-d') }}" required>
                </div>
                <div>
                    {{-- Kolom Tambahan (Jam) --}}
                    <label>Jam <span style="color:red">*</span></label>
                    <input type="time" name="jam_pengajuan" required>
                </div>
            </div>

            {{-- 3. DETAIL PERMASALAHAN --}}
            <div class="form-divider">
                <span class="divider-label">Detail Masalah</span>
            </div>

            <div class="form-row">
                <div>
                    <label>Topik / Judul Masalah <span style="color:red">*</span></label>
                    <input type="text" name="topik" placeholder="Contoh: Kesulitan Belajar / Masalah Teman" required>
                </div>
                <div>
                    <label>Jenis Layanan</label>
                    <select name="jenis_layanan">
                        <option value="Konseling Individu">Konseling Individu</option>
                        <option value="Bimbingan Belajar">Bimbingan Belajar</option>
                        <option value="Bimbingan Karir">Bimbingan Karir</option>
                        <option value="Mediasi">Mediasi (Konflik)</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label>Latar Belakang Masalah <span style="color:red">*</span></label>
                <textarea name="latar_belakang" rows="4" placeholder="Ceritakan singkat apa yang sedang kamu alami..." required></textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label>Kegiatan Layanan yang Diinginkan</label>
                <textarea name="kegiatan_layanan" rows="3" placeholder="Apa harapanmu setelah konseling ini? (Misal: Ingin solusi, ingin didengarkan, dll)"></textarea>
            </div>

            {{-- TOMBOL AKSI --}}
            <div style="text-align: right; margin-top: 30px;">
                <a href="{{ route('siswa.konseling.index') }}" class="btn-gray">Batal</a>
                <button type="submit" class="btn-blue">
                    <i class="fas fa-paper-plane"></i> Kirim Pengajuan
                </button>
            </div>

        </form>
    </div>
</div>

{{-- Script untuk validasi tanggal --}}
<script>
    // Set minimal tanggal hari ini
    document.querySelector('input[name="tanggal"]').min = new Date().toISOString().split("T")[0];
</script>

@endsection