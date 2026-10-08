@extends('layouts.admin')

@section('title', 'Detail Prestasi')
@section('page_title', 'Detail Prestasi Siswa')

@section('content')

{{-- LOAD FONT & ICON --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    /* ===========================
       LAYOUT & CARD STYLES
       =========================== */
    .detail-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header-custom {
        background-color: #123B6B;
        color: white;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header-custom h4 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
    }

    /* GRID SYSTEM (Responsive) */
    .detail-body {
        padding: 30px;
        display: grid;
        grid-template-columns: 1.2fr 0.8fr; /* Desktop: Kiri lebar, Kanan sempit */
        gap: 40px;
    }

    /* ===========================
       LEFT COLUMN (INFO TEXT)
       =========================== */
    .info-group {
        margin-bottom: 20px;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 15px;
    }
    .info-group:last-child { border-bottom: none; }

    .info-label {
        font-size: 13px;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
        display: block;
    }

    .info-value {
        font-size: 16px;
        color: #333;
        font-weight: 500;
        line-height: 1.5;
    }

    .badge-jenis {
        display: inline-block;
        padding: 6px 12px;
        background-color: #eef2ff;
        color: #3730a3;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
    }

    /* ===========================
       RIGHT COLUMN (FILE PREVIEW)
       =========================== */
    .preview-section {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        border: 2px dashed #e0e0e0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .preview-image {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 15px;
        max-height: 300px;
        object-fit: contain;
    }

    .file-icon-placeholder {
        font-size: 60px;
        color: #123B6B;
        margin-bottom: 15px;
    }

    .btn-download {
        display: inline-block;
        background: #123B6B;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: 0.3s;
    }
    .btn-download:hover { background: #0f2e52; color: #fff; }

    /* ===========================
       FOOTER ACTIONS
       =========================== */
    .card-footer-custom {
        background-color: #f9fafb;
        padding: 15px 25px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: space-between;
    }

    .btn-back {
        color: #555;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 6px;
        background: #e9ecef;
        transition: 0.2s;
    }
    .btn-back:hover { background: #dde1e6; }

    /* ===========================
       MEDIA QUERIES (MOBILE)
       =========================== */
    @media (max-width: 768px) {
        .detail-body {
            grid-template-columns: 1fr; /* Stack jadi 1 kolom */
            gap: 20px;
            padding: 20px;
        }

        .preview-section {
            order: -1; /* Pindah preview ke atas di HP (Opsional, hapus baris ini jika ingin di bawah) */
            margin-bottom: 10px;
        }

        .card-header-custom {
            flex-direction: column;
            text-align: center;
            gap: 5px;
        }
    }
</style>

<div class="detail-card">
    
    {{-- Header --}}
    <div class="card-header-custom">
        <h4><i class="fa-solid fa-trophy" style="margin-right: 8px; color:#FFD700;"></i> Detail Prestasi</h4>
        <span style="font-size: 13px; opacity: 0.8;">ID: #{{ $prestasi->id }}</span>
    </div>

    <div class="detail-body">
        
        {{-- KOLOM KIRI: Informasi Text --}}
        <div class="left-column">
            
            <div class="info-group">
                <span class="info-label">Siswa</span>
                <div class="info-value">
                    <strong>{{ $prestasi->siswa->nama_lengkap ?? 'Siswa Terhapus' }}</strong><br>
                    <span class="text-muted" style="font-size:13px;">{{ $prestasi->siswa->nis ?? '-' }} | {{ $prestasi->siswa->rombel ?? '-' }}</span>
                </div>
            </div>

            <div class="info-group">
                <span class="info-label">Judul Prestasi / Kegiatan</span>
                <div class="info-value" style="font-size: 1.1rem; font-weight: 600; color: #123B6B;">
                    {{ $prestasi->judul }}
                </div>
            </div>

            <div class="info-group">
                <span class="info-label">Jenis & Kategori</span>
                <div class="info-value">
                    <span class="badge-jenis">{{ ucfirst($prestasi->jenis) }}</span>
                </div>
            </div>

            <div class="info-group">
                <span class="info-label">Tanggal Pelaksanaan</span>
                <div class="info-value">
                    <i class="far fa-calendar-alt"></i> 
                    {{ $prestasi->tanggal_prestasi ? \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->translatedFormat('l, d F Y') : '-' }}
                </div>
            </div>

            <div class="info-group">
                <span class="info-label">Keterangan / Detail</span>
                <div class="info-value">
                    {{ $prestasi->keterangan ?? 'Tidak ada keterangan tambahan.' }}
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Preview File --}}
        <div class="right-column">
            <span class="info-label" style="margin-bottom: 10px;">Bukti / Dokumentasi</span>
            
            <div class="preview-section">
                @php
                    // Logika Cek File (Support folder 'uploads' guru & 'storage' siswa)
                    $fileUrl = null;
                    $isImage = false;

                    if ($prestasi->file) {
                        $pathGuru = 'uploads/prestasi/' . $prestasi->file;
                        $pathSiswa = 'storage/prestasi/' . $prestasi->file; // Link simbolik public/storage

                        // Cek file fisik
                        if (file_exists(public_path($pathGuru))) {
                            $fileUrl = asset($pathGuru);
                        } elseif (file_exists(storage_path('app/public/prestasi/' . $prestasi->file))) {
                            $fileUrl = asset($pathSiswa);
                        }

                        // Cek ekstensi untuk preview gambar
                        $ext = pathinfo($prestasi->file, PATHINFO_EXTENSION);
                        if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                            $isImage = true;
                        }
                    }
                @endphp

                @if ($fileUrl)
                    @if ($isImage)
                        <img src="{{ $fileUrl }}" alt="Bukti Prestasi" class="preview-image">
                        <a href="{{ $fileUrl }}" target="_blank" class="btn-download">
                            <i class="fas fa-expand"></i> Lihat Full Size
                        </a>
                    @else
                        {{-- Jika PDF atau file lain --}}
                        <div class="file-icon-placeholder">
                            <i class="fas fa-file-pdf text-danger"></i>
                        </div>
                        <p style="margin-bottom: 15px; font-weight: 500;">{{ $prestasi->file }}</p>
                        <a href="{{ $fileUrl }}" target="_blank" class="btn-download">
                            <i class="fas fa-download"></i> Download / Buka File
                        </a>
                    @endif

                @elseif ($prestasi->link)
                    {{-- Jika Cuma Link --}}
                    <div class="file-icon-placeholder">
                        <i class="fas fa-link text-info"></i>
                    </div>
                    <p style="margin-bottom: 15px;">Bukti berupa Tautan Eksternal</p>
                    <a href="{{ $prestasi->link }}" target="_blank" class="btn-download">
                        <i class="fas fa-external-link-alt"></i> Buka Tautan
                    </a>

                @else
                    {{-- Kosong --}}
                    <div class="file-icon-placeholder" style="color: #ccc;">
                        <i class="fas fa-image"></i>
                    </div>
                    <p style="color: #999;">Tidak ada file bukti atau link yang dilampirkan.</p>
                @endif
            </div>
        </div>

    </div>

    {{-- Footer Actions --}}
    <div class="card-footer-custom">
        <a href="{{ route('admin.prestasi.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <form action="{{ route('admin.prestasi.destroy', $prestasi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data prestasi ini secara permanen?');">
            @csrf
            @method('DELETE')
            <button type="submit" style="background:none; border:none; color:#dc3545; font-weight:600; cursor:pointer;">
                <i class="fas fa-trash"></i> Hapus Data
            </button>
        </form>
    </div>

</div>

@endsection