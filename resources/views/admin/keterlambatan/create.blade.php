@extends('layouts.admin')

@section('title', 'Tambah Data Keterlambatan')
@section('page_title', 'Keterlambatan dan Perizinan')

{{-- 1. LOAD FONT AWESOME VERSI 6 --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

@section('content')
<style>
    /* ============================
       BASE STYLES (DESKTOP)
       ============================ */
    .header-keterlambatan {
        background-color: #123B6B;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 25px;
        border-radius: 8px 8px 0 0;
    }

    .header-keterlambatan h4 { margin: 0; font-weight: 600; font-size: 1.1rem; }
    .tanggal-jam { font-size: 14px; text-align: right; line-height: 1.4; }

    .form-container {
        background: #ffffff;
        padding: 25px;
        border: 1px solid #ddd;
        border-top: none;
        border-radius: 0 0 8px 8px;
    }

    .form-group { margin-bottom: 20px; }

    .form-group label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        color: #333;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
        font-size: 14px;
        transition: 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #123B6B;
        box-shadow: 0 0 5px rgba(18, 59, 107, 0.2);
    }

    /* Grid Layout untuk Form */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* Upload Box Style */
    .upload-box {
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: 0.2s;
        color: #64748b;
        font-size: 13px;
        background: #f8fafc;
    }
    .upload-box:hover {
        border-color: #123B6B;
        background: #eff6ff;
        color: #123B6B;
    }

    /* Wrapper Tombol */
    .btn-wrapper {
        display: flex;
        justify-content: space-between;
        margin-top: 25px;
        gap: 10px;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        border: none;
        transition: 0.3s;
    }

    .btn-primary { background-color: #123B6B; color: white; }
    .btn-primary:hover { background-color: #0f2e52; }

    .btn-secondary { background-color: #6c757d; color: white; }
    .btn-secondary:hover { background-color: #5a6268; }

    .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

    /* ============================
       RESPONSIVE (MOBILE)
       ============================ */
    @media (max-width: 768px) {
        .header-keterlambatan { flex-direction: column; text-align: center; gap: 10px; padding: 15px; }
        .form-container { padding: 20px 15px; }
        .form-row { grid-template-columns: 1fr; gap: 0; } /* Stack ke bawah di HP */
        .btn-wrapper { flex-direction: column-reverse; }
        .btn { width: 100%; }
    }
</style>

<div class="card shadow-sm" style="border-radius: 8px; overflow: hidden;">
    {{-- Header --}}
    <div class="header-keterlambatan">
        <h4>Input Data Keterlambatan (Admin)</h4>
        <div class="tanggal-jam" id="tanggal-jam"></div>
    </div>

    <div class="form-container">
        {{-- Alert Error --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0; padding-left:15px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Tambah (Wajib enctype="multipart/form-data" untuk upload) --}}
        <form method="POST" action="{{ route('admin.keterlambatan.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Baris 1: Identitas Siswa --}}
            <div class="form-row">
                <div class="form-group">
                    <label for="nis">NIS Siswa <span style="color:red">*</span></label>
                    <input type="text" name="nis" id="nis" value="{{ old('nis') }}" class="form-control" placeholder="Contoh: 12345" required>
                </div>
                <div class="form-group">
                    <label for="nama_siswa">Nama Siswa <span style="color:red">*</span></label>
                    <input type="text" name="nama_siswa" id="nama_siswa" value="{{ old('nama_siswa') }}" class="form-control" placeholder="Nama Lengkap Siswa" required>
                </div>
            </div>

            {{-- Baris 2: Waktu --}}
            <div class="form-row">
                <div class="form-group">
                    <label for="tanggal">Tanggal <span style="color:red">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="jam_datang">Jam Datang <span style="color:red">*</span></label>
                    <input type="time" name="jam_datang" id="jam_datang" value="{{ old('jam_datang') }}" class="form-control" required>
                </div>
            </div>

            {{-- Baris 3: Menit & Status --}}
            <div class="form-row">
                <div class="form-group">
                    <label for="menit_terlambat">Estimasi Terlambat (Menit)</label>
                    <input type="number" name="menit_terlambat" id="menit_terlambat" value="{{ old('menit_terlambat', 0) }}" class="form-control" placeholder="Contoh: 15">
                </div>
                <div class="form-group">
                    <label for="status">Status Awal</label>
                    <select name="status" class="form-control">
                        <option value="terima">Langsung Terima (Disetujui)</option>
                        <option value="pending">Menunggu (Pending)</option>
                        <option value="tolak">Ditolak</option>
                    </select>
                </div>
            </div>

            {{-- Keterangan --}}
            <div class="form-group">
                <label for="keterangan">Keterangan / Alasan <span style="color:red">*</span></label>
                <textarea name="keterangan" id="keterangan" rows="3" class="form-control" placeholder="Jelaskan alasan keterlambatan..." required>{{ old('keterangan') }}</textarea>
            </div>

            {{-- Upload Dokumen --}}
            <div class="form-group">
                <label>Dokumen Pendukung (Opsional)</label>
                <label class="upload-box">
                    <i class="fa-solid fa-cloud-arrow-up" style="font-size: 24px; margin-bottom: 8px;"></i><br>
                    <span>Klik untuk unggah Bukti/Surat</span>
                    <input type="file" name="dokumen" hidden onchange="previewFile(this)">
                </label>
                <small id="fileName" style="display:block; margin-top:5px; color:#123B6B; font-weight:600; font-size:12px;"></small>
            </div>

            {{-- Tombol Aksi --}}
            <div class="btn-wrapper">
                <a href="{{ route('admin.keterlambatan.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Preview Nama File
    function previewFile(input) {
        if (input.files && input.files[0]) {
            document.getElementById('fileName').innerText = 'File terpilih: ' + input.files[0].name;
        }
    }

    // Jam Digital
    function updateDateTime() {
        const now = new Date();
        const optionsDate = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
        const optionsTime = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        
        const tanggal = now.toLocaleDateString('id-ID', optionsDate);
        const jam = now.toLocaleTimeString('id-ID', optionsTime).replace('.', ':');
        
        document.getElementById('tanggal-jam').innerHTML = `${tanggal}<br>${jam} WIB`;
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>

@endsection