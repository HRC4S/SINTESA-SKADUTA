@extends('layouts.siswa')

@section('title', 'Data Orang Tua | SINTESA')
@section('page_title', 'Data Orang Tua')

@section('content')

<style>
    /* ---------------------------------
       MAIN CARD STYLE (Original)
    ------------------------------------*/
    .card-siswa {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        max-width: 100%;
        margin: 0 auto;
    }

    .section-title {
        background-color: #1e3a67; /* Warna Asli */
        color: white;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 1.1rem;
        width: 100%;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        box-sizing: border-box;
    }

    .form-body {
        padding: 30px 50px;
    }

    /* ---------------------------------
       DETAIL VALUE & LABEL (Original)
    ------------------------------------*/
    .detail-value {
        padding: 6px 10px;
        border-radius: 10px;
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        font-size: 0.95rem;
        color: #333;
        font-weight: 500;
        min-height: 40px;
        display: flex;
        align-items: center;
        width: 100%;
        box-sizing: border-box; /* Agar padding tidak merusak lebar */
    }

    label {
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        display: block;
        margin-bottom: 5px;
    }

    /* ---------------------------------
       BUTTONS (Original Colors)
    ------------------------------------*/
    .btn-blue { 
        display:inline-block; background-color:#1e3a67; color:white;
        padding:10px 20px; border-radius:5px; text-decoration:none; border:none;
        transition:0.3s; margin:0 6px; font-weight: 500;
    }
    .btn-blue:hover { background-color:#0056b3; }

    .btn-gray { 
        display:inline-block; background-color:#4a4a4a; color:white;
        padding:10px 20px; border-radius:5px; text-decoration:none; border:none;
        margin:0 6px; font-weight: 500;
    }
    .btn-gray:hover { background-color:#3a3a3a; }

    /* ---------------------------------
       DETAIL CONTAINER (RESPONSIF)
    ------------------------------------*/
    .detail-container {
        display: flex;
        flex-wrap: wrap; /* Agar turun ke bawah di layar kecil */
        gap: 20px;
        margin-top: 30px;
    }

    .detail-box {
        flex: 1;
        min-width: 300px; /* Diubah sedikit agar muat di HP standar (360px) */
        background-color: #f9fafc;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    .detail-box .header { 
        background-color: #1e3a67;
        color: white;
        font-weight: 600;
        padding: 10px 15px;
    }

    .detail-box .body {
        padding: 20px;
    }

    .body .form-group {
        margin-bottom: 15px;
    }

    /* ---------------------------------
       SCROLL & MOBILE FIX
    ------------------------------------*/
    .scrollable-content {
        max-height: 80vh;
        overflow-y: auto;
        padding-bottom: 20px;
        scrollbar-width: thin;
    }

    /* Media Query Khusus Mobile */
    @media (max-width: 768px) {
        .form-body {
            padding: 20px; /* Padding lebih kecil di HP */
        }
        .detail-box {
            width: 100%;
            min-width: 100%; /* Kotak memenuhi lebar layar HP */
        }
        .detail-container {
            gap: 25px; /* Jarak antar kotak vertikal */
        }
    }
</style>

<div class="scrollable-content">
    <div class="card-siswa">
        <div class="section-title">
            <i class="fas fa-users" style="margin-right:8px;"></i> DATA ORANG TUA / WALI
        </div>

        <div class="form-body">
            @php $ortu = $siswa->orangTua ?? null; @endphp

            <div class="detail-container">

                {{-- AYAH --}}
                <div class="detail-box">
                    <div class="header">BIODATA AYAH</div>
                    <div class="body">
                        <div class="form-group"><label>Nama Ayah</label><div class="detail-value">{{ $ortu->nama_ayah ?? '-' }}</div></div>
                        <div class="form-group"><label>NIK</label><div class="detail-value">{{ $ortu->nik_ayah ?? '-' }}</div></div>
                        <div class="form-group"><label>Tahun Lahir</label><div class="detail-value">{{ $ortu->tahun_lahir_ayah ?? '-' }}</div></div>
                        <div class="form-group"><label>Pendidikan</label><div class="detail-value">{{ $ortu->pendidikan_ayah ?? '-' }}</div></div>
                        <div class="form-group"><label>Pekerjaan</label><div class="detail-value">{{ $ortu->pekerjaan_ayah ?? '-' }}</div></div>
                        <div class="form-group"><label>Penghasilan</label><div class="detail-value">{{ $ortu->penghasilan_ayah ?? '-' }}</div></div>
                        <div class="form-group"><label>Status Hidup</label><div class="detail-value">{{ $ortu->status_hidup_ayah ?? '-' }}</div></div>
                        <div class="form-group"><label>No. Telepon</label><div class="detail-value">{{ $ortu->no_telp_ayah ?? '-' }}</div></div>
                    </div>
                </div>

                {{-- IBU --}}
                <div class="detail-box">
                    <div class="header">BIODATA IBU</div>
                    <div class="body">
                        <div class="form-group"><label>Nama Ibu</label><div class="detail-value">{{ $ortu->nama_ibu ?? '-' }}</div></div>
                        <div class="form-group"><label>NIK</label><div class="detail-value">{{ $ortu->nik_ibu ?? '-' }}</div></div>
                        <div class="form-group"><label>Tahun Lahir</label><div class="detail-value">{{ $ortu->tahun_lahir_ibu ?? '-' }}</div></div>
                        <div class="form-group"><label>Pendidikan</label><div class="detail-value">{{ $ortu->pendidikan_ibu ?? '-' }}</div></div>
                        <div class="form-group"><label>Pekerjaan</label><div class="detail-value">{{ $ortu->pekerjaan_ibu ?? '-' }}</div></div>
                        <div class="form-group"><label>Penghasilan</label><div class="detail-value">{{ $ortu->penghasilan_ibu ?? '-' }}</div></div>
                        <div class="form-group"><label>Status Hidup</label><div class="detail-value">{{ $ortu->status_hidup_ibu ?? '-' }}</div></div>
                        <div class="form-group"><label>No. Telepon</label><div class="detail-value">{{ $ortu->no_telp_ibu ?? '-' }}</div></div>
                    </div>
                </div>

                {{-- WALI --}}
                <div class="detail-box">
                    <div class="header">BIODATA WALI</div>
                    <div class="body">
                        <div class="form-group"><label>Nama Wali</label><div class="detail-value">{{ $ortu->nama_wali ?? '-' }}</div></div>
                        <div class="form-group"><label>NIK</label><div class="detail-value">{{ $ortu->nik_wali ?? '-' }}</div></div>
                        <div class="form-group"><label>Tahun Lahir</label><div class="detail-value">{{ $ortu->tahun_lahir_wali ?? '-' }}</div></div>
                        <div class="form-group"><label>Pendidikan</label><div class="detail-value">{{ $ortu->pendidikan_wali ?? '-' }}</div></div>
                        <div class="form-group"><label>Pekerjaan</label><div class="detail-value">{{ $ortu->pekerjaan_wali ?? '-' }}</div></div>
                        <div class="form-group"><label>Penghasilan</label><div class="detail-value">{{ $ortu->penghasilan_wali ?? '-' }}</div></div>
                        <div class="form-group"><label>Status Hidup</label><div class="detail-value">{{ $ortu->status_hidup_wali ?? '-' }}</div></div>
                        <div class="form-group"><label>No. Telepon</label><div class="detail-value">{{ $ortu->no_telp_wali ?? '-' }}</div></div>
                    </div>
                </div>

            </div>

            {{-- Tombol Edit (Hanya Muncul Jika Akses Diaktifkan Admin) --}}
            @if($siswa->akses_edit == 1)
                <div class="text-center mt-5" style="margin-bottom: 20px;">
                    <a href="{{ route('siswa.ortu.edit', $siswa->nis) }}" class="btn-blue">
                        <i class="fas fa-edit" style="margin-right:5px;"></i> Edit Data Orang Tua
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection