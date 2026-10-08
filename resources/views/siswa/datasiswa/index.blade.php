@extends('layouts.siswa')
@section('title', 'Data Siswa')
@section('page_title', 'Data Siswa')
@section('content')

<style>
    /* ---------------------------------
       MAIN CARD STYLE (Original Design)
    ------------------------------------*/
    .card-siswa {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        width: 100%;
        margin: 0 auto;
    }

    .section-title {
        background-color: #1e3a67; /* Warna Asli */
        color: white;
        padding: 15px 25px; /* Sedikit padding adjustment agar rapi */
        font-weight: 600;
        font-size: 1.1rem;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .form-body {
        padding: 30px 50px;
    }

    /* ---------------------------------
       FOTO PROFIL
    ------------------------------------*/
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

    /* ---------------------------------
       INPUT / LABEL STYLE (Original)
    ------------------------------------*/
    label {
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        margin-bottom: 5px;
        display: block;
    }

    .detail-value {
        padding: 6px 10px; /* Padding dirapikan */
        border-radius: 10px;
        background-color: #f9f9f9; /* Warna Asli */
        border: 1px solid #ccc;
        font-size: 0.95rem;
        color: #333;
        font-weight: 500;
        min-height: 40px;
        display: flex;
        align-items: center;
        width: 100%; /* Pastikan memenuhi wadah */
        box-sizing: border-box; /* Agar padding tidak melebar */
    }

    /* ---------------------------------
       GRID LAYOUT (RESPONSIF)
    ------------------------------------*/
    .form-row {
        display: grid;
        grid-template-columns: 1fr; /* Default HP: 1 Kolom */
        gap: 15px;
        margin-bottom: 20px;
    }

    /* Media Query: Tablet & Desktop jadi 2 Kolom */
    @media (min-width: 768px) {
        .form-row {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px 40px;
        }
    }

    /* ---------------------------------
       DETAIL BOXES (BAWAH)
    ------------------------------------*/
    .detail-container {
        display: flex;
        flex-wrap: wrap; /* Agar turun ke bawah di HP */
        gap: 20px;
        margin-top: 30px;
    }

    .detail-box {
        flex: 1;
        min-width: 300px; /* Agar tidak gepeng di layar kecil */
        background-color: #f9fafc; /* Warna Asli */
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    .detail-box .header {
        background-color: #1e3a67; /* Warna Asli */
        color: white;
        font-weight: 600;
        padding: 10px 15px;
    }

    .detail-box .body {
        padding: 20px;
    }

    .detail-box .body .form-group {
        margin-bottom: 15px;
    }

    /* ---------------------------------
       BUTTONS (Original Colors)
    ------------------------------------*/
    .btn-blue {
        display: inline-block;
        background-color: #1e3a67;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        border: none;
        transition: 0.3s;
        font-weight: 500;
    }
    .btn-blue:hover {
        background-color: #0056b3;
        color: white;
    }

    /* ---------------------------------
       SCROLL CONTAINER & MOBILE FIX
    ------------------------------------*/
    .scrollable-content {
        max-height: 85vh;
        overflow-y: auto;
        padding-bottom: 20px;
    }

    @media (max-width: 768px) {
        .form-body {
            padding: 20px; /* Padding lebih kecil di HP */
        }
        .detail-box {
            width: 100%;
            min-width: 100%;
        }
    }
</style>

<div class="scrollable-content">
    <div class="card-siswa">
        <div class="section-title">DETAIL BIODATA SISWA: {{ $siswa->nama_lengkap }}</div>

        <div class="form-body">
            
            {{-- Foto Profil --}}
            <div class="foto-wrapper">
                <img 
                    src="{{ $siswa->foto ? asset('uploads/foto_siswa/' . $siswa->foto) : asset('images/icon pelajar.jpeg') }}" 
                    alt="Foto Siswa" 
                />
            </div>

            {{-- Row 1: NIS & NISN --}}
            <div class="form-row">
                <div>
                    <label>NIS</label>
                    <div class="detail-value">{{ $siswa->nis ?? '-' }}</div>
                </div>
                <div>
                    <label>NISN</label>
                    <div class="detail-value">{{ $siswa->nisn ?? '-' }}</div>
                </div>
            </div>

            {{-- Row 2: Nama & JK --}}
            <div class="form-row">
                <div>
                    <label>Nama Lengkap</label>
                    <div class="detail-value">{{ $siswa->nama_lengkap ?? '-' }}</div>
                </div>
                <div>
                    <label>Jenis Kelamin</label>
                    <div class="detail-value">{{ $siswa->jenis_kelamin ?? '-' }}</div>
                </div>
            </div>

            {{-- Row 3: Email & WA --}}
            <div class="form-row">
                <div>
                    <label>Email</label>
                    <div class="detail-value">{{ $siswa->email ?? '-' }}</div>
                </div>
                <div>
                    <label>No. WhatsApp</label>
                    <div class="detail-value">{{ $siswa->no_whatsapp ?? '-' }}</div>
                </div>
            </div>

            {{-- Row 4: Rombel & Jurusan --}}
            <div class="form-row">
                <div>
                    <label>Rombel</label>
                    <div class="detail-value">{{ $siswa->rombel ?? '-' }}</div>
                </div>
                <div>
                    <label>Jurusan</label>
                    <div class="detail-value">{{ $siswa->jurusan ?? '-' }}</div>
                </div>
            </div>

            {{-- Row 5: TTL --}}
            <div class="form-row">
                <div>
                    <label>Tempat Lahir</label>
                    <div class="detail-value">{{ $siswa->tempat_lahir ?? '-' }}</div>
                </div>
                <div>
                    <label>Tanggal Lahir</label>
                    <div class="detail-value">
                        {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d M Y') : '-' }}
                    </div>
                </div>
            </div>

            {{-- Row 6: Agama & Ortu --}}
            <div class="form-row">
                <div>
                    <label>Agama</label>
                    <div class="detail-value">{{ $siswa->agama ?? '-' }}</div>
                </div>
                <div>
                    <label>Nama Orang Tua (Utama)</label>
                    <div class="detail-value">{{ $siswa->nama_ortu ?? '-' }}</div>
                </div>
            </div>

            {{-- Row 7: Alamat (Full Width) --}}
            <div class="form-row">
                <div style="grid-column: 1 / -1;">
                    <label>Alamat Lengkap</label>
                    <div class="detail-value" style="height: auto; min-height: 60px; align-items: flex-start; padding-top: 10px;">
                        {{ $siswa->alamat ?? '-' }}
                    </div>
                </div>
            </div>

            {{-- DETAIL CONTAINER (BAWAH) --}}
            <div class="detail-container">
                
                {{-- Detail 1 --}}
                <div class="detail-box">
                    <div class="header">BIODATA DETAIL SISWA</div>
                    <div class="body">
                        @php $detail = $siswa->detailSiswa; @endphp
                        <div class="form-group"><label>Cita-cita</label><div class="detail-value">{{ $detail->cita_cita ?? '-' }}</div></div>
                        <div class="form-group"><label>Hobi</label><div class="detail-value">{{ $detail->hobi ?? '-' }}</div></div>
                        
                        {{-- Berat & Tinggi dalam 1 baris di dalam detail box --}}
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                            <div><label>Berat (kg)</label><div class="detail-value">{{ $detail->berat_badan ?? '-' }}</div></div>
                            <div><label>Tinggi (cm)</label><div class="detail-value">{{ $detail->tinggi_badan ?? '-' }}</div></div>
                        </div>

                        <div class="form-group"><label>Anak ke-</label><div class="detail-value">{{ $detail->anak_ke ?? '-' }} dari {{ $detail->jumlah_saudara ?? '-' }} saudara</div></div>
                        <div class="form-group"><label>Tinggal Dengan</label><div class="detail-value">{{ $detail->tinggal_dengan ?? '-' }}</div></div>
                        <div class="form-group"><label>Jarak Rumah</label><div class="detail-value">{{ $detail->jarak_rumah ?? '-' }}</div></div>
                        <div class="form-group"><label>Waktu Tempuh</label><div class="detail-value">{{ $detail->waktu_tempuh ?? '-' }}</div></div>
                        <div class="form-group"><label>Transportasi</label><div class="detail-value">{{ $detail->transportasi ?? '-' }}</div></div>
                    </div>
                </div>

                {{-- Detail 2: Alamat --}}
                <div class="detail-box">
                    <div class="header">DATA ALAMAT SISWA</div>
                    <div class="body">
                        @php $alamat = $siswa->detailSiswa; @endphp
                        <div class="form-group"><label>Nama Jalan</label><div class="detail-value">{{ $alamat->nama_jalan ?? '-' }}</div></div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                            <div><label>RT</label><div class="detail-value">{{ $alamat->rt ?? '-' }}</div></div>
                            <div><label>RW</label><div class="detail-value">{{ $alamat->rw ?? '-' }}</div></div>
                        </div>

                        <div class="form-group"><label>Dusun</label><div class="detail-value">{{ $alamat->dusun ?? '-' }}</div></div>
                        <div class="form-group"><label>Desa/Kelurahan</label><div class="detail-value">{{ $alamat->desa ?? '-' }}</div></div>
                        <div class="form-group"><label>Kode Pos</label><div class="detail-value">{{ $alamat->kode_pos ?? '-' }}</div></div>
                    </div>
                </div>
            </div>

            {{-- TOMBOL EDIT (Hanya jika akses diizinkan) --}}
            @if($siswa->akses_edit == 1)
                <div style="margin-top: 30px; text-align: right;">
                    <a href="{{ route('siswa.profil.edit', $siswa->nis) }}" class="btn-blue">
                        <i class="fas fa-edit"></i> Edit Profil
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection