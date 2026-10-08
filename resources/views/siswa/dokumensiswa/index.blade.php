@extends('layouts.siswa')

@section('title', 'Dokumen Siswa | SINTESA')
@section('page_title', 'Dokumen Siswa')

@section('content')
<style>
    /* --- Root & Reset --- */
    :root {
        --primary-gradient: linear-gradient(135deg, #1e40af 0%, #17375d 100%);
        --soft-bg: #f8fafc;
        --border-color: #e2e8f0;
    }

    body { 
        background-color: var(--soft-bg); 
        font-family: 'Inter', system-ui, -apple-system, sans-serif; 
    }

    /* ====== CARD STYLE ====== */
    .card-custom {
        border-radius: 16px !important;
        border: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.03);
        background: white;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .header-gradient {
        background: var(--primary-gradient) !important;
        padding: 24px 30px;
        border: none;
        color: #fff
    }

    /* ====== TOTAL BOX ====== */
    .total-info {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 10px 20px;
        border-radius: 12px;
        text-align: center;
        min-width: 120px;
        color: #fff
    }

    /* ====== TABLE OPTIMIZATION ====== */
    .table-responsive {
        border-radius: 0 0 16px 16px;
    }
    
    .table-custom { 
        margin-bottom: 0; 
        width: 100%;
    }
    
    .table-custom tr {
        transition: all 0.2s ease;
    }

    .table-custom tr:hover {
        background-color: #f1f5f9;
    }

    .table-custom td {
        padding: 20px 25px !important;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
    }

    .table-custom tr:last-child td {
        border-bottom: none;
    }

    /* ====== CONTENT ELEMENTS ====== */
    .doc-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 1rem;
        display: block;
    }

    .doc-meta {
        font-size: 0.8rem;
        color: #94a3b8;
    }

    .doc-icon-wrapper {
        width: 48px;
        height: 48px;
        background: #eff6ff;
        color: #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.3rem;
    }

    /* ====== BADGES ====== */
    .status-badge {
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .status-success { 
        background: #dcfce7; 
        color: #15803d; 
        border: 1px solid #bbf7d0; 
    }

    .status-warning { 
        background: #fff7ed; 
        color: #c2410c; 
        border: 1px solid #ffedd5; 
    }

    /* ====== ACTION BUTTONS ====== */
    .btn-action-group { 
        display: flex; 
        gap: 10px; 
        justify-content: flex-end; 
    }

    .btn-base {
        height: 40px;
        padding: 0 18px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        border: none;
        text-decoration: none !important;
    }

    .btn-view-soft { 
        background: #f1f5f9; 
        color: #475569; 
    }
    
    .btn-view-soft:hover { 
        background: #e2e8f0; 
        color: #0f172a; 
    }

    .btn-upload-primary { 
        background: #3b82f6; 
        color: white; 
    }
    
    .btn-upload-primary:hover { 
        background: #2563eb; 
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    }

    .btn-change-success { 
        background: #10b981; 
        color: white; 
    }
    
    .btn-change-success:hover { 
        background: #059669; 
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    }

    input[type="file"] { display: none; }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .btn-action-group { flex-direction: column; align-items: stretch; }
        .header-gradient { flex-direction: column; text-align: center; gap: 15px; }
        .doc-icon-wrapper { margin: 0 auto 10px; }
        .table-custom td { text-align: center; }
        .btn-base { justify-content: center; }
    }
</style>

<div class="container py-4">

    {{-- Alert Notifikasi --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius:12px; background:#dcfce7; color:#15803d;">
        <div class="d-flex align-items: center;">
            <i class="fas fa-check-circle me-3 fa-lg"></i> 
            <div><strong>Berhasil!</strong> {{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- 📑 Card Dokumen --}}
    <div class="card-custom">
        <div class="header-gradient text-white d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold">Total Dokumen</h4>
                <p class="mb-0 opacity-75 small">Lengkapi dokumen persyaratan di bawah ini</p>
            </div>
            <div class="total-info">
                <span class="d-block small fw-bold opacity-75">TERPENUHI</span>
                <span class="h4 mb-0 fw-bold">{{ $dokumens->whereNotNull('file_path')->count() }} / {{ $dokumens->count() }}</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-custom">
                <tbody>
                    @foreach($dokumens as $dokumen)
                    <tr>
                        <td style="width: 80px;" class="d-none d-md-table-cell">
                            <div class="doc-icon-wrapper">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                        </td>

                        <td>
                            <span class="doc-name">{{ $dokumen->jenis_dokumen }}</span>
                            <span class="doc-meta">Wajib diunggah (PDF/JPG/PNG)</span>
                        </td>

                        <td class="text-center">
                            @if($dokumen->file_path)
                                <span class="status-badge status-success">
                                    <i class="fas fa-check-circle"></i> Sudah Unggah
                                </span>
                            @else
                                <span class="status-badge status-warning">
                                    <i class="fas fa-exclamation-circle"></i> Belum Ada
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="btn-action-group">
                                @if($dokumen->file_path)
                                    <a href="{{ asset('storage/' . $dokumen->file_path) }}" target="_blank" class="btn-base btn-view-soft">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                @endif

                                <form action="{{ route('siswa.dokumensiswa.upload', $dokumen->id) }}" method="POST" enctype="multipart/form-data" class="m-0">
                                    @csrf
                                    <input type="file" name="file" id="file{{ $dokumen->id }}" onchange="this.form.submit()">
                                    
                                    @if($dokumen->file_path)
                                        <label for="file{{ $dokumen->id }}" class="btn-base btn-change-success m-0" style="cursor: pointer;">
                                            <i class="fas fa-sync-alt"></i> Ganti
                                        </label>
                                    @else
                                        <label for="file{{ $dokumen->id }}" class="btn-base btn-upload-primary m-0" style="cursor: pointer;">
                                            <i class="fas fa-upload"></i> Unggah
                                        </label>
                                    @endif
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection