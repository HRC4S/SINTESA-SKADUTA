@extends('layouts.siswa')

@section('title', 'Data Orang Tua | SINTESA')
@section('page_title', 'Data Orang Tua')
@section('content')

<style>
    /* ---------------------------------
       Gaya CSS dari Form Create Anda
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
        background-color: #1e3a67;
        color: white;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 1.1rem;
        width: 100%;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .form-body {
        padding: 30px 50px;
    }

    .foto-wrapper {
        text-align: center;
        margin-bottom: 30px;
    }

    .foto-wrapper img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e5e5e5;
    }
    
    /* Gaya untuk tampilan detail */
    .detail-value {
        padding: 1px 5px;
        border-radius: 10px;
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        font-size: 0.95rem;
        color: #333;
        font-weight: 500;
        min-height: 40px; /* Menjaga konsistensi tinggi */
        display: flex;
        align-items: center;
    }
    
    label {
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
    }

    .btn-blue {
    display: inline-block;
    background-color: #1e3a67; /* biru Bootstrap */
    color: white;
    padding: 8px 16px;
    border-radius: 5px;
    text-decoration: none;
    border: none;
    transition: background-color 0.3s ease;
    margin: 0 6px; /* jarak antar tombol */
}

.btn-blue:hover {
    background-color: #0056b3; /* biru lebih gelap saat hover */
    color: white;
    text-decoration: none;
}

.btn-gray {
    display: inline-block;
    background-color: #4a4a4a;
    color: white;
    padding: 8px 16px;
    border-radius: 5px;
    text-decoration: none;
    border: none;
    transition: background-color 0.3s ease;
    margin: 0 6px; /* jarak antar tombol */
}

.btn-gray:hover {
    background-color: #3a3a3a;
    color: white;
    text-decoration: none;
}




    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px 40px;
        margin-bottom: 20px;
    }

    .detail-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 30px;
    }

    .detail-box {
        flex: 1;
        min-width: 350px;
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
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .detail-box .body {
        padding: 20px;
    }

    .detail-box .body .form-group {
        margin-bottom: 15px;
    }

    .scrollable-content {
        max-height: 80vh;
        overflow-y: auto;
        padding-right: 10px;
    }
</style>

<div class="scrollable-content">
    <div class="card-siswa">
            @php
                $ortu = $siswa->orangTua ?? null;
            @endphp
    
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
            </div>

            <div class="detail-container">
                <div class="detail-box" style="flex:1;">
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

            {{-- TOMBOL EDIT HANYA MUNCUL JIKA ADMIN MENGAKTIFKAN AKSES --}}
@if($siswa->akses_edit == 1)
    <a href="{{ route('siswa.ortu.edit', $siswa->nis) }}" class="btn-edit">
        Edit Profil
    </a>
@endif

<style>
    .btn-edit {
        display: inline-block;
        background-color: #f4b400; /* kuning modern */
        color: #fff;
        padding: 8px 18px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s ease;
    }

    .btn-edit:hover {
        background-color: #d89e00;
    }
</style>

        </div>
    </div>
</div>

@endsection
