@extends('layouts.siswa')

@section('title', 'Edit Prestasi')
@section('page_title', 'Edit Data Prestasi')

@section('content')

{{-- Import Font & Icons --}}
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    :root {
        --primary: #1e3a8a;
        --primary-hover: #172554;
        --secondary: #64748b;
        --bg-light: #f1f5f9;
        --white: #ffffff;
        --border: #cbd5e1;
    }

    body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); }

    /* Container Form */
    .edit-container {
        max-width: 800px;
        margin: 0 auto;
        padding-bottom: 50px;
    }

    .card {
        background: var(--white);
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        padding: 30px;
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .card-header {
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 20px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Form Elements */
    .form-group { margin-bottom: 20px; }
    
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        font-family: inherit;
        transition: 0.2s;
        box-sizing: border-box;
        background-color: #f8fafc;
    }

    .form-control:focus {
        border-color: var(--primary);
        background-color: var(--white);
        outline: none;
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    /* Grid System for Form */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* === BAGIAN UPLOAD FILE === */
    .upload-area {
        border: 2px dashed var(--border);
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        cursor: pointer;
        transition: 0.2s;
        background-color: #f8fafc;
        position: relative;
        display: block;
        width: 100%;
        margin-bottom: 5px; /* Jarak dengan elemen bawahnya */
    }

    .upload-area:hover {
        border-color: var(--primary);
        background-color: #eff6ff;
    }

    .upload-icon {
        font-size: 32px;
        color: var(--secondary);
        margin-bottom: 5px;
    }

    /* Style Preview Nama File Baru */
    #fileName {
        font-size: 13px; 
        color: #16a34a; /* Hijau */
        font-weight: 600; 
        margin-top: 5px;
        margin-bottom: 10px; 
        display: none; /* Default hidden */
    }

    /* Style Info File Lama */
    .current-file {
        background: #e0f2fe;
        color: #0369a1;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 20px; /* Jarak JAUH agar tidak numpuk */
        border: 1px solid #bae6fd;
        width: fit-content;
        max-width: 100%;
    }

    /* Buttons */
    .btn-wrapper {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 30px;
        border-top: 1px solid #e2e8f0;
        padding-top: 20px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        border: none;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-secondary { background-color: #f1f5f9; color: #475569; }
    .btn-secondary:hover { background-color: #e2e8f0; color: #1e293b; }

    .btn-primary { background-color: var(--primary); color: var(--white); }
    .btn-primary:hover { background-color: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(30, 58, 138, 0.2); }

    /* Responsive */
    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; gap: 0; }
        .btn-wrapper { flex-direction: column-reverse; }
        .btn { width: 100%; justify-content: center; }
        .card { padding: 20px; }
    }
</style>

<div class="edit-container">
    <div class="card">
        
        {{-- Header Form --}}
        <div class="card-header">
            <div class="card-title">
                <i class="bi bi-pencil-square"></i> Edit Data Prestasi
            </div>
        </div>

        {{-- Form Edit --}}
        <form method="POST" action="{{ route('siswa.prestasi.update', $prestasi->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Judul Prestasi --}}
            <div class="form-group">
                <label class="form-label">Judul Prestasi <span style="color:red">*</span></label>
                <input type="text" name="judul" class="form-control" value="{{ $prestasi->judul }}" required>
            </div>

            {{-- Grid (Jenis & Tanggal) --}}
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Jenis Prestasi <span style="color:red">*</span></label>
                    <select name="jenis" class="form-control" required>
                        <option value="sertifikat" {{ $prestasi->jenis == 'sertifikat' ? 'selected' : '' }}>Sertifikat</option>
                        <option value="seminar" {{ $prestasi->jenis == 'seminar' ? 'selected' : '' }}>Seminar</option>
                        <option value="lomba" {{ $prestasi->jenis == 'lomba' ? 'selected' : '' }}>Lomba</option>
                        <option value="lainnya" {{ $prestasi->jenis == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Prestasi <span style="color:red">*</span></label>
                    <input type="date" name="tanggal_prestasi" class="form-control" 
                           value="{{ $prestasi->tanggal_prestasi }}" required>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="form-group">
                <label class="form-label">Keterangan / Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4">{{ $prestasi->deskripsi }}</textarea>
            </div>

            {{-- Link --}}
            <div class="form-group">
                <label class="form-label">Link Eksternal (Opsional)</label>
                <div style="position: relative;">
                    <i class="bi bi-link-45deg" style="position: absolute; left: 12px; top: 12px; color: #64748b; font-size: 18px;"></i>
                    <input type="text" name="link" class="form-control" value="{{ $prestasi->link }}" style="padding-left: 35px;" placeholder="https://...">
                </div>
            </div>

            {{-- === BAGIAN UPLOAD FILE === --}}
            <div class="form-group">
                <label class="form-label">Bukti Dokumen (Foto/PDF)</label>
                
                {{-- 1. Area Klik Upload --}}
                <label class="upload-area" for="fileInput">
                    <div class="upload-icon">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                    </div>
                    <span style="font-weight: 500; color: #475569;">Klik untuk mengganti file</span>
                    <br>
                    <span style="font-size: 12px; color: #94a3b8;">(Biarkan kosong jika tidak ingin mengubah)</span>
                    <input type="file" id="fileInput" name="file" style="display:none" onchange="previewFile()">
                </label>

                {{-- 2. Preview Nama File BARU (Muncul setelah pilih file) --}}
                <p id="fileName"></p>

                {{-- 3. Info File LAMA (Dari Database) --}}
                @if($prestasi->file)
                    <div class="current-file">
                        <i class="bi bi-file-earmark-check-fill" style="font-size: 20px;"></i>
                        <div>
                            <span style="display:block; font-weight:700; font-size:10px; color:#0c4a6e; letter-spacing:0.5px;">FILE SAAT INI:</span>
                            <span style="color:#0369a1;">{{ Str::limit($prestasi->file, 40) }}</span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Tombol Aksi --}}
            <div class="btn-wrapper">
                <a href="{{ route('siswa.prestasi.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

{{-- Script untuk Preview Nama File --}}
<script>
    function previewFile() {
        const input = document.getElementById('fileInput');
        const fileName = document.getElementById('fileName');
        
        if(input.files.length > 0) {
            fileName.innerText = "File baru terpilih: " + input.files[0].name;
            fileName.style.display = 'block';
        } else {
            fileName.innerText = "";
            fileName.style.display = 'none';
        }
    }
</script>

@endsection