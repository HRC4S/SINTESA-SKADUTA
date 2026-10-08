@extends('layouts.admin')

@section('title', 'Detail Konseling')
@section('page_title', 'Detail Pengajuan Konseling')

@section('content')

<style>
    /* === GLOBAL STYLES === */
    .form-container {
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); /* Shadow lebih halus */
        width: 100%;
        max-width: 100%; /* Pastikan tidak melebar */
        box-sizing: border-box; /* Penting untuk padding */
    }

    /* GRID SYSTEM (Responsive) */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr); /* Default 2 kolom */
        gap: 25px; /* Jarak antar kolom */
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        color: #1e3a8a; /* Biru gelap */
        font-size: 0.95rem;
        margin-bottom: 8px;
    }

    /* INPUT & TEXTAREA STYLES */
    .form-control-static {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background-color: #f8fafc;
        color: #334155;
        font-size: 0.95rem;
        line-height: 1.5;
        resize: none; /* Mencegah resize textarea */
    }

    /* Tombol Aksi */
    .form-actions {
        margin-top: 30px;
        display: flex;
        justify-content: flex-end;
    }

    .btn-back {
        background-color: #64748b;
        color: white;
        padding: 10px 24px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: background-color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-back:hover {
        background-color: #475569;
        color: white;
    }

    /* ===========================
       MEDIA QUERIES (MOBILE)
       =========================== */
    @media (max-width: 768px) {
        .form-container {
            padding: 20px; /* Padding lebih kecil di HP */
        }

        .form-grid {
            grid-template-columns: 1fr; /* Jadi 1 kolom di HP */
            gap: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-actions {
            justify-content: center; /* Tombol tengah di HP */
        }

        .btn-back {
            width: 100%; /* Tombol full width di HP */
            justify-content: center;
            padding: 12px;
        }
    }
</style>

<div class="form-container">

    {{-- Grid untuk Biodata (2 Kolom di Desktop, 1 di Mobile) --}}
    <div class="form-grid">
        <div class="form-group">
            <label>Nama Siswa</label>
            <div class="form-control-static">{{ $konseling->nama_siswa ?? '-' }}</div>
        </div>

        <div class="form-group">
            <label>NIS</label>
            <div class="form-control-static">{{ $konseling->nis ?? '-' }}</div>
        </div>

        <div class="form-group">
            <label>Kelas</label>
            <div class="form-control-static">{{ $konseling->kelas ?? '-' }}</div>
        </div>

        <div class="form-group">
            <label>Nama Orang Tua</label>
            <div class="form-control-static">{{ $konseling->nama_ortu ?? '-' }}</div>
        </div>

        <div class="form-group">
            <label>Alamat Orang Tua</label>
            <div class="form-control-static">{{ $konseling->alamat_ortu ?? '-' }}</div>
        </div>

        <div class="form-group">
            <label>No Telp Orang Tua</label>
            <div class="form-control-static">{{ $konseling->no_telp_ortu ?? '-' }}</div>
        </div>
    </div>

    {{-- Bagian Detail Konseling (Full Width) --}}
    <div class="form-group">
        <label>Alasan Konseling</label>
        <div class="form-control-static" style="min-height: 80px;">
            {{ $konseling->alasan ?? '-' }}
        </div>
    </div>

    <div class="form-group">
        <label>Topik / Peristiwa</label>
        <div class="form-control-static">
            {{ $konseling->topik ?? '-' }}
        </div>
    </div>

    <div class="form-group">
        <label>Latar Belakang</label>
        <div class="form-control-static" style="min-height: 80px;">
            {{ $konseling->latar_belakang ?? '-' }}
        </div>
    </div>

    <div class="form-group">
        <label>Kegiatan Layanan yang Diinginkan</label>
        <div class="form-control-static" style="min-height: 80px;">
            {{ $konseling->kegiatan_layanan ?? '-' }}
        </div>
    </div>

    {{-- Tombol Kembali --}}
    <div class="form-actions">
        <a href="{{ route('admin.konseling.index') }}" class="btn-back">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>

</div>

@endsection