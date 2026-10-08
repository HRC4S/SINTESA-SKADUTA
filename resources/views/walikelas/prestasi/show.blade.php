@extends('layouts.admin')

@section('title', 'Detail Prestasi')
@section('page_title', 'Detail Prestasi Siswa — Wali Kelas')

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
        font-family: 'Poppins', sans-serif;
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

    /* GRID SYSTEM */
    .detail-body {
        padding: 30px;
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 40px;
    }

    /* LEFT COLUMN (INFO TEXT) */
    .info-group {
        margin-bottom: 20px;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 15px;
    }
    .info-group:last-child { border-bottom: none; }

    .info-label {
        font-size: 12px;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
        display: block;
    }

    .info-value {
        font-size: 15px;
        color: #333;
        font-weight: 500;
        line-height: 1.5;
    }

    .badge-jenis {
        display: inline-block;
        padding: 6px 14px;
        background-color: #eef2ff;
        color: #3730a3;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    /* RIGHT COLUMN (FILE PREVIEW) */
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
        max-height: 350px;
        object-fit: contain;
    }

    .file-icon-placeholder {
        font-size: 60px;
        color: #123B6B;
        margin-bottom: 15px;
    }

    .btn-action {
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
    .btn-action:hover { background: #0f2e52; color: #fff; }

    /* FOOTER ACTIONS */
    .card-footer-custom {
        background-color: #f9fafb;
        padding: 15px 25px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
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
    .btn-back:hover { background: #dde1e6; text-decoration: none; color: #333; }

    @media (max-width: 768px) {
        .detail-body { grid-template-columns: 1fr; padding: 20px; }
        .preview-section { order: -1; margin-bottom: 10px; }
        .card-header-custom { flex-direction: column; text-align: center; gap: 5px; }
    }
</style>

<div class="detail-card">
    
    {{-- Header --}}
    <div class="card-header-custom">
        <h4><i class="fa-solid fa-medal" style="margin-right: 8px; color:#FFD700;"></i> Detail Prestasi Siswa</h4>
        <span style="font-size: 13px; opacity: 0.8;">Data Terverifikasi Wali Kelas</span>
    </div>

    <div class="detail-body">
        
        {{-- KOLOM KIRI: Informasi Text --}}
        <div class="left-column">
            
            <div class="info-group">
                <span class="info-label">Nama Siswa</span>
                <div class="info-value">
                    <strong style="font-size: 17px;">{{ $siswa->nama_lengkap ?? 'Data Tidak Ditemukan' }}</strong><br>
                    <span class="text-muted" style="font-size:13px;">NIS: {{ $siswa->nis ?? '-' }} | Kelas: {{ $siswa->rombel ?? '-' }}</span>
                </div>
            </div>

            {{-- Kita loop prestasi yang spesifik untuk NIS ini --}}
            @forelse($prestasi as $p)
                <div style="background: #fcfcfc; border: 1px solid #f0f0f0; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                    <div class="info-group">
                        <span class="info-label">Judul Prestasi / Kegiatan</span>
                        <div class="info-value" style="font-size: 1.1rem; font-weight: 600; color: #123B6B;">
                            {{ $p->judul }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <span class="info-label">Kategori</span>
                                <div class="info-value">
                                    <span class="badge-jenis">{{ ucfirst($p->jenis) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <span class="info-label">Tanggal</span>
                                <div class="info-value">
                                    <i class="far fa-calendar-alt" style="color: #123B6B;"></i> 
                                    {{ $p->tanggal_prestasi ? \Carbon\Carbon::parse($p->tanggal_prestasi)->translatedFormat('d F Y') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-group">
                        <span class="info-label">Keterangan Tambahan</span>
                        <div class="info-value">
                            {{ $p->deskripsi ?? 'Tidak ada deskripsi tambahan.' }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Belum ada data prestasi untuk siswa ini.</div>
            @endforelse
        </div>

        {{-- KOLOM KANAN: Preview File Terakhir --}}
        <div class="right-column">
            <span class="info-label" style="margin-bottom: 10px;">Lampiran Terakhir</span>
            
            <div class="preview-section">
                @php
                    $lastPrestasi = $prestasi->first();
                    $fileUrl = null;
                    $isImage = false;

                    if ($lastPrestasi && $lastPrestasi->file) {
                        $path = 'storage/prestasi/' . $lastPrestasi->file;
                        if (Storage::disk('public')->exists('prestasi/' . $lastPrestasi->file)) {
                            $fileUrl = asset($path);
                            $ext = pathinfo($lastPrestasi->file, PATHINFO_EXTENSION);
                            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                $isImage = true;
                            }
                        }
                    }
                @endphp

                @if ($fileUrl)
                    @if ($isImage)
                        <img src="{{ $fileUrl }}" alt="Bukti Prestasi" class="preview-image">
                        <a href="{{ $fileUrl }}" target="_blank" class="btn-action">
                            <i class="fas fa-search-plus"></i> Lihat Ukuran Penuh
                        </a>
                    @else
                        <div class="file-icon-placeholder">
                            <i class="fas fa-file-pdf text-danger"></i>
                        </div>
                        <p style="margin-bottom: 15px; font-weight: 500;">Dokumen Digital</p>
                        <a href="{{ $fileUrl }}" target="_blank" class="btn-action">
                            <i class="fas fa-external-link-alt"></i> Buka Dokumen
                        </a>
                    @endif
                @elseif ($lastPrestasi && $lastPrestasi->link)
                    <div class="file-icon-placeholder">
                        <i class="fas fa-globe text-info"></i>
                    </div>
                    <p style="margin-bottom: 15px;">Bukti Tautan Luar</p>
                    <a href="{{ $lastPrestasi->link }}" target="_blank" class="btn-action">
                        <i class="fas fa-link"></i> Kunjungi Link
                    </a>
                @else
                    <div class="file-icon-placeholder" style="color: #ccc;">
                        <i class="fas fa-image"></i>
                    </div>
                    <p style="color: #999; font-size: 13px;">Siswa belum melampirkan berkas bukti.</p>
                @endif
            </div>
        </div>

    </div>

    {{-- Footer Actions --}}
    <div class="card-footer-custom">
        <a href="{{ route('wali.prestasi.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>

        <div style="font-size: 12px; color: #999;">
            <i class="fas fa-info-circle"></i> Hanya menampilkan prestasi siswa di kelas Anda.
        </div>
    </div>

</div>

@endsection