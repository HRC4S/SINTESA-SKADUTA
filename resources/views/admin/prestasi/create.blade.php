@extends('layouts.admin')

@section('title', 'Tambah Prestasi')
@section('page_title', 'Tambah Data Prestasi')

@section('content')

{{-- Load FontAwesome (Jika belum ada di layout utama) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    /* ===========================
       BASE STYLES (DESKTOP)
       =========================== */
    .wrap-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        max-width: 800px; /* Batasi lebar di desktop agar tidak terlalu panjang */
        margin: 20px auto; /* Tengah secara horizontal */
        overflow: hidden;
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
    .header-time { font-size: 0.9rem; text-align: right; font-weight: 400; opacity: 0.9; }

    /* FORM BODY */
    .form-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        color: #333;
        font-size: 0.95rem;
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
        text-decoration: none;
        font-size: 14px;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: none;
    }

    .btn-primary {
        background-color: #123B6B;
        color: white;
    }
    .btn-primary:hover {
        background-color: #0f2e52;
        box-shadow: 0 4px 12px rgba(18, 59, 107, 0.2);
    }

    .btn-secondary {
        background-color: #e5e7eb;
        color: #374151;
    }
    .btn-secondary:hover {
        background-color: #d1d5db;
        color: #111;
    }

    /* Helper Text */
    .text-muted {
        font-size: 12px;
        color: #6b7280;
        margin-top: 5px;
        display: block;
    }

    /* ===========================
       MEDIA QUERIES (MOBILE)
       =========================== */
    @media (max-width: 768px) {
        .wrap-card {
            margin: 10px; /* Margin kecil di HP */
            width: auto;
        }

        .header-box {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }

        .header-time { text-align: center; }

        .form-body { padding: 20px; }

        .btn-wrapper {
            flex-direction: column-reverse; /* Tombol Simpan di atas */
        }

        .btn {
            width: 100%; /* Tombol lebar penuh */
            padding: 14px;
        }
    }
</style>

<div class="wrap-card">

    {{-- HEADER --}}
    <div class="header-box">
        <div class="header-title">
            <i class="fas fa-plus-circle"></i> Tambah Prestasi Siswa
        </div>
        <div class="header-time" id="realtime-clock">
            {{-- Jam akan diisi oleh Javascript --}}
            Loading time...
        </div>
    </div>

    <div class="form-body">
        <form action="{{ route('admin.prestasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- PILIH SISWA --}}
            <div class="form-group">
                <label for="nis">Nama Siswa <span style="color:red">*</span></label>
                <select id="nis" name="nis" class="form-control" required>
                    <option value="">-- Cari / Pilih Siswa --</option>
                    @foreach ($siswas as $siswa)
                        <option value="{{ $siswa->nis }}">{{ $siswa->nama_lengkap }} ({{ $siswa->nis }})</option>
                    @endforeach
                </select>
            </div>

            {{-- Judul Prestasi --}}
            <div class="form-group">
                <label for="judul">Judul Prestasi / Kegiatan <span style="color:red">*</span></label>
                <input type="text" id="judul" name="judul" class="form-control" placeholder="Contoh: Juara 1 Lomba Web Design" required>
            </div>

            {{-- Jenis Prestasi --}}
            <div class="form-group">
                <label for="jenis">Jenis Prestasi <span style="color:red">*</span></label>
                <select id="jenis" name="jenis" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Lomba">Lomba</option>
                    <option value="Seminar">Seminar</option>
                    <option value="Sertifikat">Sertifikat</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            {{-- Deskripsi --}}
            <div class="form-group">
                <label for="deskripsi">Deskripsi (Opsional)</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3" placeholder="Tambahkan detail singkat tentang prestasi ini..."></textarea>
            </div>

            {{-- File Upload --}}
            <div class="form-group">
                <label for="file">Upload Bukti</label>
                <input type="file" id="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                <span class="text-muted"><i class="fas fa-info-circle"></i> Format: PDF, JPG, JPEG, PNG. Maksimal 2MB.</span>
            </div>

            {{-- Link Prestasi --}}
            <div class="form-group">
                <label for="link">Link Eksternal (Opsional)</label>
                <input type="url" id="link" name="link" class="form-control" placeholder="https://...">
                <span class="text-muted">Isi jika bukti berupa link drive atau website berita.</span>
            </div>

            {{-- Tanggal Prestasi --}}
            <div class="form-group">
                <label for="tanggal_prestasi">Tanggal Pelaksanaan <span style="color:red">*</span></label>
                <input type="date" id="tanggal_prestasi" name="tanggal_prestasi" class="form-control" required>
            </div>

            {{-- Tombol Aksi --}}
            <div class="btn-wrapper">
                <a href="{{ route('admin.prestasi.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
            </div>

        </form>
    </div>

</div>

<script>
    function updateClock() {
        const now = new Date();
        
        // Format Tanggal: Sabtu, 10 Januari 2026
        const optionsDate = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
        const tanggal = now.toLocaleDateString('id-ID', optionsDate);
        
        // Format Jam: 14:30:05
        const jam = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace('.', ':');

        document.getElementById('realtime-clock').innerHTML = `${tanggal}<br>${jam} WIB`;
    }

    // Jalankan setiap detik
    setInterval(updateClock, 1000);
    // Jalankan sekali saat load
    updateClock();
</script>

@endsection