@extends('layouts.admin')

@section('content')
@section('title', 'Detail Data Siswa')
@section('page_title', 'Detail Data Siswa')
<style>
    /* ---------------------------------
       Gaya CSS Detail Siswa Responsif
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

    /* Penyesuaian padding untuk mobile */
    @media (max-width: 768px) {
        .form-body {
            padding: 20px 15px;
        }
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
        padding: 8px 12px; /* Sedikit diperbesar paddingnya agar lebih enak dilihat */
        border-radius: 8px; /* Radius sedikit dikurangi agar lebih modern */
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        font-size: 0.95rem;
        color: #333;
        font-weight: 500;
        min-height: 40px; 
        display: flex;
        align-items: center;
        word-break: break-word; /* Mencegah teks panjang keluar dari kotak */
    }
    
    label {
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        margin-bottom: 5px; /* Memberikan jarak antara label dan value */
        display: block;
    }

    .btn-blue {
        display: inline-block;
        background-color: #1e3a67; 
        color: white;
        padding: 8px 16px;
        border-radius: 5px;
        text-decoration: none;
        border: none;
        transition: background-color 0.3s ease;
        margin: 0 6px; 
    }

    .btn-blue:hover {
        background-color: #0056b3; 
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
        margin: 0 6px; 
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

    /* Media query untuk form row menjadi 1 kolom di mobile */
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }
    }

    .detail-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 30px;
    }

    .detail-box {
        flex: 1;
        min-width: 300px; /* Ukuran min-width sedikit dikurangi agar pas di layar kecil */
        background-color: #f9fafc;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    /* Agar detail box memenuhi lebar layar di mobile */
    @media (max-width: 768px) {
        .detail-box {
            min-width: 100%;
        }
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
    
    /* Penyesuaian scrollable content di mobile agar tidak terlalu pendek */
    @media (max-width: 768px) {
        .scrollable-content {
            max-height: 90vh;
        }
    }
</style>

<div class="scrollable-content">
    <div class="card-siswa">
        <div class="section-title">DETAIL BIODATA SISWA: {{ $siswa->nama_lengkap }}</div>

        <div class="form-body">
            
            <div class="foto-wrapper">
                {{-- Tampilkan foto siswa jika ada, jika tidak, tampilkan placeholder --}}
                <img id="preview-image" 
                     src="{{ $siswa->foto ? asset('uploads/foto_siswa/' . $siswa->foto) : asset('images/student.png') }}" 
                     alt="Foto Siswa">
                <br>
                {{-- Hapus tombol upload, ini hanya tampilan --}}
            </div>

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

            <div class="form-row">
                <div>
                    <label>Tempat Lahir</label>
                    <div class="detail-value">{{ $siswa->tempat_lahir ?? '-' }}</div>
                </div>
                <div>
                    <label>Tanggal Lahir</label>
                    <div class="detail-value">{{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d M Y') : '-' }}</div>
                </div>
            </div>

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

            <div class="form-row">
                <div style="grid-column: span 2; @media (max-width: 768px) { grid-column: span 1; }">
                    <label>Alamat Lengkap</label>
                    <div class="detail-value" style="min-height: 80px;">{{ $siswa->alamat ?? '-' }}</div>
                </div>
            </div>

            <div class="detail-container">
                <div class="detail-box">
                    <div class="header">BIODATA DETAIL SISWA</div>
                    <div class="body">
                        @php $detail = $siswa->detailSiswa; @endphp
                        <div class="form-group"><label>Cita-cita</label><div class="detail-value">{{ $detail->cita_cita ?? '-' }}</div></div>
                        <div class="form-group"><label>Hobi</label><div class="detail-value">{{ $detail->hobi ?? '-' }}</div></div>
                        <div class="form-group"><label>Berat Badan</label><div class="detail-value">{{ $detail->berat_badan ?? '-' }}</div></div>
                        <div class="form-group"><label>Tinggi Badan</label><div class="detail-value">{{ $detail->tinggi_badan ?? '-' }}</div></div>
                        <div class="form-group"><label>Anak ke-</label><div class="detail-value">{{ $detail->anak_ke ?? '-' }}</div></div>
                        <div class="form-group"><label>Jumlah Saudara</label><div class="detail-value">{{ $detail->jumlah_saudara ?? '-' }}</div></div>
                        <div class="form-group"><label>Tinggal Dengan</label><div class="detail-value">{{ $detail->tinggal_dengan ?? '-' }}</div></div>
                        <div class="form-group"><label>Jarak Rumah</label><div class="detail-value">{{ $detail->jarak_rumah ?? '-' }}</div></div>
                        <div class="form-group"><label>Waktu Tempuh</label><div class="detail-value">{{ $detail->waktu_tempuh ?? '-' }}</div></div>
                        <div class="form-group"><label>Transportasi</label><div class="detail-value">{{ $detail->transportasi ?? '-' }}</div></div>
                    </div>
                </div>

                <div class="detail-box">
                    <div class="header">DATA ALAMAT SISWA</div>
                    <div class="body">
                        {{-- Ambil data dari kolom Siswa Model --}}
                        @php $alamat = $siswa->detailSiswa; @endphp
                        <div class="form-group"><label>Nama Jalan</label><div class="detail-value">{{ $alamat->nama_jalan ?? '-' }}</div></div>
                        <div class="form-group"><label>RT</label><div class="detail-value">{{ $alamat->rt ?? '-' }}</div></div>
                        <div class="form-group"><label>RW</label><div class="detail-value">{{ $alamat->rw ?? '-' }}</div></div>
                        <div class="form-group"><label>Dusun</label><div class="detail-value">{{ $alamat->dusun ?? '-' }}</div></div>
                        <div class="form-group"><label>Desa/Kelurahan</label><div class="detail-value">{{ $alamat->desa ?? '-' }}</div></div>
                        <div class="form-group"><label>Kode Pos</label><div class="detail-value">{{ $alamat->kode_pos ?? '-' }}</div></div>
                    </div>
                </div>
            </div>

            {{-- Menggunakan $siswa->orangTua->kolom_data (Asumsi relasi orangTua sudah dibuat) --}}
            @php
                $ortu = $siswa->orangTua ?? null;
            @endphp
            
            <div class="detail-container">
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

            <div class="mt-4 text-center">
                <a href="{{ route('admin.datasiswa.index') }}" class="btn btn-gray">Kembali ke Daftar</a>
            </div>


        </div>
    </div>
</div>

{{-- Hapus script JS yang terkait dengan upload/preview foto, karena ini mode view --}}
@endsection