@extends('layouts.admin')

@section('title', 'Tambah Konseling')
@section('page_title', 'Tambah Data Konseling')

@section('content')
<style>
    /* ===========================
       BASE STYLES (DESKTOP)
       =========================== */
    .wrap-card {
        background: #ffffff;
        padding: 0; /* Padding dipindah ke inner elements */
        border-radius: 10px;
        margin-top: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05); /* Tambah shadow halus */
        overflow: hidden;
    }

    /* HEADER */
    .header-box {
        background: #0e325f;
        color: white;
        padding: 18px 25px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .header-box span {
        font-size: 1.1rem;
    }

    .tanggal-jam {
        text-align: right;
        font-size: 13px;
        color: #fff;
        font-weight: 600;
        line-height: 1.4;
    }

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

    .form-control, select, textarea {
        width: 100%;
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 12px 15px;
        font-size: 14px;
        box-sizing: border-box;
        transition: 0.3s;
        background-color: #fcfcfc;
    }

    .form-control:focus {
        outline: none;
        border-color: #0e325f;
        box-shadow: 0 0 0 3px rgba(14, 50, 95, 0.1);
        background-color: #fff;
    }

    /* TOMBOL WRAPPER */
    .btn-wrapper {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        gap: 15px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 600;
        text-align: center;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
        border: none;
        transition: 0.3s;
    }

    .btn-primary {
        background-color: #123B6B;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0f2e52;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    /* ===========================
       MEDIA QUERIES (MOBILE)
       =========================== */
    @media (max-width: 768px) {
        /* Header Stack */
        .header-box {
            flex-direction: column;
            text-align: center;
            gap: 10px;
            padding: 20px;
        }

        .header-box span {
            font-size: 1.2rem;
        }

        .tanggal-jam {
            text-align: center;
        }

        /* Form Body Padding Smaller */
        .form-body {
            padding: 20px;
        }

        /* Tombol Stack Vertikal */
        .btn-wrapper {
            flex-direction: column-reverse; /* Tombol Simpan di atas, Kembali di bawah */
        }

        .btn {
            width: 100%;
            padding: 14px; /* Area sentuh lebih besar */
        }
    }
</style>

<div class="wrap-card">

    {{-- HEADER --}}
    <div class="header-box">
        <span>Tambah Data Konseling</span>
        <div class="tanggal-jam" id="tanggalJamSiswa"></div>
    </div>

    <div class="form-body">
        <form action="{{ route('admin.konseling.store') }}" method="POST">
            @csrf

            {{-- Nama & NIS Siswa --}}
            <div class="form-group">
                <label for="siswa_nis">Nama Siswa</label>
                <select id="siswa_nis" name="siswa_nis" class="form-control" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach ($siswas as $siswa)
                        <option value="{{ $siswa->nis }}">{{ $siswa->nama_lengkap }} ({{ $siswa->nis }})</option>
                    @endforeach
                </select>
            </div>

            {{-- Rombel --}}
            <div class="form-group">
                <label for="rombel">Rombel</label>
                <input type="text" id="rombel" name="rombel" class="form-control" placeholder="Contoh: XII RPL 1" required>
            </div>

            {{-- Catatan --}}
            <div class="form-group">
                <label for="catatan">Catatan Konseling</label>
                <textarea id="catatan" name="catatan" class="form-control" rows="4" placeholder="Tuliskan catatan konseling..."></textarea>
            </div>

            {{-- Tanggal --}}
            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control" required>
            </div>

            {{-- Tombol Aksi --}}
            <div class="btn-wrapper">
                <a href="{{ route('admin.konseling.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Data</button>
            </div>
        </form>
    </div>

</div>

<script>
function updateClock() {
    const now = new Date();

    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };

    const tanggal = now.toLocaleDateString('id-ID', options);
    const jam = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second:'2-digit' }).replace('.', ':');

    document.getElementById('tanggalJamSiswa').innerHTML = `
        ${tanggal}<br>${jam} WIB
    `;
}

// update setiap detik
setInterval(updateClock, 1000);
// panggil sekali saat awal load
updateClock();
</script>
@endsection