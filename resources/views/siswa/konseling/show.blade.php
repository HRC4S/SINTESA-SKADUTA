@extends('layouts.siswa')

@section('title', 'Detail Konseling')
@section('page_title', 'Detail Pengajuan')

@section('content')

{{-- Load FontAwesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    /* ===========================
       WRAPPER & CARD
       =========================== */
    .detail-wrapper {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        overflow: hidden;
        margin: 0 auto;
        max-width: 900px;
    }

    .header-detail {
        background: #123B6B;
        color: white;
        padding: 20px 30px;
        border-bottom: 4px solid #0e2a4c;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header-detail h4 { margin: 0; font-weight: 600; font-size: 1.2rem; }
    
    .body-detail { padding: 30px 40px; }

    /* ===========================
       STATUS BADGE (BESAR)
       =========================== */
    .status-banner {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        margin-bottom: 25px;
    }
    .st-menunggu { background: #fff8e1; color: #b58900; border: 1px solid #ffeeba; }
    .st-disetujui { background: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
    .st-ditolak { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .st-selesai { background: #cce5ff; color: #004085; border: 1px solid #b8daff; }

    /* ===========================
       GRID INFO SYSTEM
       =========================== */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px dashed #ddd;
    }

    .info-item label {
        font-size: 0.85rem;
        color: #6b7280;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 5px;
    }

    .info-item p {
        font-size: 1rem;
        color: #1f2937;
        font-weight: 500;
        margin: 0;
    }

    .guru-box {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .guru-icon {
        width: 35px; height: 35px; background: #eef2ff; color: #123B6B;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
    }

    /* ===========================
       CONTENT SECTION
       =========================== */
    .content-section { margin-bottom: 25px; }
    
    .content-label {
        font-size: 1rem;
        font-weight: 700;
        color: #123B6B;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .content-box {
        background: #f9fafb;
        padding: 15px 20px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        color: #374151;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    /* ===========================
       ADMIN RESPONSE
       =========================== */
    .response-box {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        padding: 20px;
        border-radius: 8px;
        margin-top: 30px;
    }
    .response-header {
        font-weight: 700;
        color: #0369a1;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ===========================
       BUTTONS
       =========================== */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #6c757d;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
        margin-top: 20px;
    }
    .btn-back:hover { background: #5a6268; }

    /* ===========================
       RESPONSIVE
       =========================== */
    @media (max-width: 768px) {
        .header-detail { flex-direction: column; align-items: flex-start; gap: 5px; }
        .info-grid { grid-template-columns: 1fr; gap: 20px; } /* Stack 1 kolom */
        .body-detail { padding: 20px; }
    }
</style>

<div class="detail-wrapper">
    
    {{-- Header --}}
    <div class="header-detail">
        <h4><i class="fas fa-file-alt"></i> Detail Pengajuan #{{ $konseling->id }}</h4>
        <span style="font-size: 0.85rem; opacity: 0.8;">Dibuat: {{ $konseling->created_at->format('d M Y') }}</span>
    </div>

    <div class="body-detail">

        {{-- Status Badge --}}
        @php
            $statusClass = 'st-menunggu';
            if($konseling->status == 'Disetujui') $statusClass = 'st-disetujui';
            elseif($konseling->status == 'Ditolak') $statusClass = 'st-ditolak';
            elseif($konseling->status == 'Selesai') $statusClass = 'st-selesai';
        @endphp
        <div class="status-banner {{ $statusClass }}">
            Status: {{ ucfirst($konseling->status) }}
        </div>

        {{-- Informasi Utama (Grid) --}}
        <div class="info-grid">
            <div class="info-item">
                <label><i class="far fa-calendar-alt"></i> Jadwal Diajukan</label>
                <p>
                    {{ \Carbon\Carbon::parse($konseling->tanggal)->translatedFormat('l, d F Y') }} <br>
                    <span style="font-size:0.9rem; color:#666;">
                        Pukul {{ \Carbon\Carbon::parse($konseling->jam_pengajuan)->format('H:i') }} WIB
                    </span>
                </p>
            </div>

            <div class="info-item">
                <label><i class="fas fa-user-tie"></i> Konselor (Guru BK)</label>
                <div class="guru-box">
                    <div class="guru-icon"><i class="fas fa-user"></i></div>
                    <div>
                        <p>{{ $konseling->guru->nama ?? 'Tidak Ditentukan' }}</p>
                        <span style="font-size:0.8rem; color:#666;">{{ $konseling->jenis_layanan }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Masalah --}}
        <div class="content-section">
            <div class="content-label"><i class="fas fa-heading"></i> Topik / Judul</div>
            <div class="content-box" style="font-weight: 600;">
                {{ $konseling->topik }}
            </div>
        </div>

        <div class="content-section">
            <div class="content-label"><i class="fas fa-align-left"></i> Latar Belakang Masalah</div>
            <div class="content-box">
                {{ $konseling->latar_belakang }}
            </div>
        </div>

        <div class="content-section">
            <div class="content-label"><i class="fas fa-bullseye"></i> Harapan / Kegiatan Layanan</div>
            <div class="content-box">
                {{ $konseling->kegiatan_layanan }}
            </div>
        </div>

        {{-- Tanggapan Admin (Hanya muncul jika ada) --}}
        @if($konseling->tanggapan_admin)
            <div class="response-box">
                <div class="response-header">
                    <i class="fas fa-comment-dots"></i> Tanggapan / Catatan Guru BK
                </div>
                <p style="margin:0; color:#0c4a6e;">
                    {{ $konseling->tanggapan_admin }}
                </p>
            </div>
        @elseif($konseling->status == 'Menunggu')
            <div style="margin-top: 30px; padding: 15px; background: #fff8e1; border-radius: 8px; color: #856404; font-size: 0.9rem;">
                <i class="fas fa-info-circle"></i> Pengajuan ini belum direspon oleh Guru BK. Mohon menunggu.
            </div>
        @endif

        {{-- Tombol Kembali --}}
        <a href="{{ route('siswa.konseling.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

    </div>
</div>

@endsection