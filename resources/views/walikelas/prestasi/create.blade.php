@extends('layouts.admin')

@section('title', 'Tambah Prestasi')
@section('page_title', 'Tambah Data Prestasi — Wali Kelas')

@section('content')

{{-- Load FontAwesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    /* ===========================
       BASE STYLES (Modern Admin Look)
       =========================== */
    .wrap-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        max-width: 800px;
        margin: 20px auto;
        overflow: hidden;
        font-family: 'Poppins', sans-serif;
    }

    /* HEADER */
    .header-box {
        background: #123B6B;
        color: white;
        padding: 20px 25px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px solid #0a2240;
    }

    .header-title { font-size: 1.1rem; }
    .header-time { font-size: 0.85rem; text-align: right; font-weight: 400; opacity: 0.9; line-height: 1.4; }

    /* FORM BODY */
    .form-body { padding: 30px; }
    .form-group { margin-bottom: 22px; }

    label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        color: #333;
        font-size: 0.9rem;
    }

    .form-control {
        width: 100% !important;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 14px;
        transition: 0.3s;
        box-sizing: border-box;
        background-color: #f9fafb;
    }

    .form-control:focus {
        border-color: #123B6B;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(18, 59, 107, 0.1);
        outline: none;
    }

    /* BUTTONS */
    .btn-wrapper {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-align: center;
        text-decoration: none !important;
        font-size: 14px;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: none;
    }

    .btn-primary { background-color: #123B6B; color: white; }
    .btn-primary:hover { background-color: #0f2e52; box-shadow: 0 4px 12px rgba(18, 59, 107, 0.2); }

    .btn-secondary { background-color: #f3f4f6; color: #4b5563; }
    .btn-secondary:hover { background-color: #e5e7eb; color: #111; }

    .text-muted { font-size: 12px; color: #6b7280; margin-top: 6px; display: block; }

    /* MOBILE RESPONSIVE */
    @media (max-width: 768px) {
        .wrap-card { margin: 10px; }
        .header-box { flex-direction: column; text-align: center; gap: 8px; }
        .btn-wrapper { flex-direction: column-reverse; }
        .btn { width: 100%; padding: 14px; }
    }
</style>

<div class="wrap-card">

    {{-- HEADER --}}
    <div class="header-box">
        <div class="header-title">
            <i class="fas fa-plus-circle mr-1"></i> Input Prestasi Siswa
        </div>
        <div class="header-time" id="realtime-clock">Loading time...</div>
    </div>

    <div class="form-body">
        {{-- Routing diarahkan ke 'wali.prestasi.store' --}}
        <form action="{{ route('wali.prestasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- PILIH SISWA (Terfilter di Controller Wali Kelas) --}}
            <div class="form-group">
                <label for="nis">Nama Siswa di Kelas Anda <span style="color:red">*</span></label>
                <select id="nis" name="nis" class="form-control" required>
                    <option value="">-- Cari / Pilih Siswa --</option>
                    @foreach ($siswas as $siswa)
                        <option value="{{ $siswa->nis }}" {{ old('nis') == $siswa->nis ? 'selected' : '' }}>
                            {{ $siswa->nama_lengkap }} ({{ $siswa->nis }})
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Hanya siswa yang terdaftar di kelas Anda yang muncul di sini.</small>
            </div>

            {{-- Judul Prestasi --}}
            <div class="form-group">
                <label for="judul">Judul Prestasi / Kegiatan <span style="color:red">*</span></label>
                <input type="text" id="judul" name="judul" class="form-control" placeholder="Misal: Juara 2 Basket Tingkat Provinsi" value="{{ old('judul') }}" required>
            </div>

            <div class="row">
                <div class="col-md-6">
                    {{-- Jenis Prestasi --}}
                    <div class="form-group">
                        <label for="jenis">Jenis <span style="color:red">*</span></label>
                        <select id="jenis" name="jenis" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="lomba" {{ old('jenis') == 'lomba' ? 'selected' : '' }}>Lomba</option>
                            <option value="seminar" {{ old('jenis') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                            <option value="sertifikat" {{ old('jenis') == 'sertifikat' ? 'selected' : '' }}>Sertifikat</option>
                            <option value="lainnya" {{ old('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    {{-- Tanggal Prestasi --}}
                    <div class="form-group">
                        <label for="tanggal_prestasi">Tanggal Pelaksanaan <span style="color:red">*</span></label>
                        <input type="date" id="tanggal_prestasi" name="tanggal_prestasi" class="form-control" value="{{ old('tanggal_prestasi') }}" required>
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="form-group">
                <label for="deskripsi">Keterangan Tambahan (Opsional)</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3" placeholder="Tambahkan rincian jika ada...">{{ old('deskripsi') }}</textarea>
            </div>

            {{-- File Upload --}}
            <div class="form-group">
                <label for="file">Upload Sertifikat/Foto Bukti</label>
                <input type="file" id="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                <span class="text-muted"><i class="fas fa-info-circle"></i> PDF/JPG/PNG. Maksimal 2MB.</span>
            </div>

            {{-- Link Prestasi --}}
            <div class="form-group">
                <label for="link">Link Dokumentasi (Jika Ada)</label>
                <input type="url" id="link" name="link" class="form-control" placeholder="https://drive.google.com/..." value="{{ old('link') }}">
            </div>

            {{-- Tombol Aksi --}}
            <div class="btn-wrapper">
                <a href="{{ route('wali.prestasi.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Prestasi
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const optionsDate = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
        const tanggal = now.toLocaleDateString('id-ID', optionsDate);
        const jam = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace('.', ':');
        document.getElementById('realtime-clock').innerHTML = `${tanggal}<br>${jam} WIB`;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

@endsection