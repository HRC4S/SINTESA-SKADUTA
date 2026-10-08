@extends('layouts.siswa')

@section('title', 'Data Orang Tua | SINTESA')
@section('page_title', 'Data Orang Tua')

@section('content')

<style>
.card-siswa {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.detail-box {
    background-color: #f9fafc;
    border-radius: 10px;
    margin-bottom: 25px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}

.detail-box .header {
    background-color: #1e3a67;
    color: white;
    padding: 10px 15px;
    font-weight: 600;
    border-radius: 10px 10px 0 0;
}

.detail-box .body {
    padding: 20px 25px;
}

.detail-value {
    padding: 1px 5px;
    border-radius: 10px;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    font-size: 0.95rem;
    color: #333;
    font-weight: 500;
    min-height: 40px;
    display: flex;
    align-items: center;
}

.detail-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.btn-blue {
    background-color: #1e3a67;
    color: white;
    padding: 8px 18px;
    border-radius: 6px;
    border: none;
    text-decoration: none;
}

.btn-blue:hover { background-color: #183158; }

.btn-gray {
    background-color: #4a4a4a;
    color: white;
    padding: 8px 18px;
    border-radius: 6px;
    border: none;
    text-decoration: none;
}

.btn-gray:hover { background-color: #3a3a3a; }

.grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px 40px;
}
</style>

@php
    $ortu = $siswa->orangTua ?? null;
@endphp

<div class="card-siswa">

    {{-- ===================== AYAH ===================== --}}
    <div class="detail-box">
        <div class="header">BIODATA AYAH</div>
        <div class="body grid-2">
            <div><label>Nama Ayah</label><div class="detail-value">{{ $ortu->nama_ayah ?? '-' }}</div></div>
            <div><label>NIK</label><div class="detail-value">{{ $ortu->nik_ayah ?? '-' }}</div></div>
            <div><label>Tahun Lahir</label><div class="detail-value">{{ $ortu->tahun_lahir_ayah ?? '-' }}</div></div>
            <div><label>Pendidikan</label><div class="detail-value">{{ $ortu->pendidikan_ayah ?? '-' }}</div></div>
            <div><label>Pekerjaan</label><div class="detail-value">{{ $ortu->pekerjaan_ayah ?? '-' }}</div></div>
            <div><label>Penghasilan</label><div class="detail-value">{{ $ortu->penghasilan_ayah ?? '-' }}</div></div>
            <div><label>Status Hidup</label><div class="detail-value">{{ $ortu->status_hidup_ayah ?? '-' }}</div></div>
            <div><label>No. Telepon</label><div class="detail-value">{{ $ortu->no_telp_ayah ?? '-' }}</div></div>
        </div>
    </div>

    {{-- ===================== IBU ===================== --}}
    <div class="detail-box">
        <div class="header">BIODATA IBU</div>
        <div class="body grid-2">
            <div><label>Nama Ibu</label><div class="detail-value">{{ $ortu->nama_ibu ?? '-' }}</div></div>
            <div><label>NIK</label><div class="detail-value">{{ $ortu->nik_ibu ?? '-' }}</div></div>
            <div><label>Tahun Lahir</label><div class="detail-value">{{ $ortu->tahun_lahir_ibu ?? '-' }}</div></div>
            <div><label>Pendidikan</label><div class="detail-value">{{ $ortu->pendidikan_ibu ?? '-' }}</div></div>
            <div><label>Pekerjaan</label><div class="detail-value">{{ $ortu->pekerjaan_ibu ?? '-' }}</div></div>
            <div><label>Penghasilan</label><div class="detail-value">{{ $ortu->penghasilan_ibu ?? '-' }}</div></div>
            <div><label>Status Hidup</label><div class="detail-value">{{ $ortu->status_hidup_ibu ?? '-' }}</div></div>
            <div><label>No. Telepon</label><div class="detail-value">{{ $ortu->no_telp_ibu ?? '-' }}</div></div>
        </div>
    </div>

    {{-- ===================== WALI ===================== --}}
    <div class="detail-box">
        <div class="header">BIODATA WALI</div>
        <div class="body grid-2">
            <div><label>Nama Wali</label><div class="detail-value">{{ $ortu->nama_wali ?? '-' }}</div></div>
            <div><label>NIK</label><div class="detail-value">{{ $ortu->nik_wali ?? '-' }}</div></div>
            <div><label>Tahun Lahir</label><div class="detail-value">{{ $ortu->tahun_lahir_wali ?? '-' }}</div></div>
            <div><label>Pendidikan</label><div class="detail-value">{{ $ortu->pendidikan_wali ?? '-' }}</div></div>
            <div><label>Pekerjaan</label><div class="detail-value">{{ $ortu->pekerjaan_wali ?? '-' }}</div></div>
            <div><label>Penghasilan</label><div class="detail-value">{{ $ortu->penghasilan_wali ?? '-' }}</div></div>
            <div><label>Status Hidup</label><div class="detail-value">{{ $ortu->status_hidup_wali ?? '-' }}</div></div>
            <div><label>No. Telepon</label><div class="detail-value">{{ $ortu->no_telp_wali ?? '-' }}</div></div>
        </div>
    </div>

</div>

@endsection
