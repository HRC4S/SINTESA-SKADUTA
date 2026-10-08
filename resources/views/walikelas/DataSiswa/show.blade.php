@extends('layouts.admin')

@section('title','Detail Siswa')
@section('page_title','Detail Biodata Siswa')

@section('content')
<style>
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
    padding: 15px 20px;
    font-weight: 600;
    font-size: 1.1rem;
    width: 100%;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}
.form-body {
    padding: 20px; /* Padding dikurangi untuk mobile */
}

/* Responsif untuk layar lebih besar dari mobile */
@media (min-width: 768px) {
    .form-body { padding: 30px 50px; }
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
.detail-value {
    padding: 10px 15px; /* Lebih proporsional */
    border-radius: 8px;
    background-color: #f9f9f9;
    border: 1px solid #eee;
    font-size: 0.95rem;
    color: #333;
    font-weight: 500;
    min-height: 40px;
    display: flex;
    align-items: center;
    word-break: break-word; /* Agar teks panjang tidak keluar box */
}
label {
    font-weight: 600;
    color: #666; /* Warna abu gelap agar kontras dengan value */
    font-size: 0.85rem;
    display: block;
    margin-bottom: 5px;
}

/* Tombol */
.btn-blue, .btn-gray {
    display: inline-block;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    border: none;
    transition: 0.3s;
    margin: 5px;
    font-weight: 600;
}
.btn-blue { background-color: #1e3a67; }
.btn-blue:hover { background-color: #2c5282; }
.btn-gray { background-color: #4a4a4a; }
.btn-gray:hover { background-color: #333; }

/* Grid System Responsif */
.form-row {
    display: grid;
    grid-template-columns: 1fr; /* Default 1 kolom untuk mobile */
    gap: 15px;
    margin-bottom: 15px;
}

@media (min-width: 768px) {
    .form-row {
        grid-template-columns: repeat(2, 1fr); /* 2 kolom untuk laptop/tablet */
        gap: 20px 40px;
    }
}

.detail-container {
    display: flex;
    flex-direction: column; /* Stack vertikal di mobile */
    gap: 20px;
    margin-top: 30px;
}

@media (min-width: 768px) {
    .detail-container {
        flex-direction: row; /* Berdampingan di laptop */
    }
}

.detail-box {
    flex: 1;
    background-color: #f9fafc;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    border: 1px solid #eee;
}
.detail-box .header {
    background-color: #1e3a67;
    color: white;
    font-weight: 600;
    padding: 10px 15px;
    font-size: 0.9rem;
}
.detail-box .body {
    padding: 20px;
}
.form-group { margin-bottom: 15px; }

.scrollable-content {
    max-height: 85vh;
    overflow-y: auto;
    padding-right: 5px;
}
/* Kustom Scrollbar */
.scrollable-content::-webkit-scrollbar { width: 6px; }
.scrollable-content::-webkit-scrollbar-track { background: #f1f1f1; }
.scrollable-content::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
</style>

<div class="scrollable-content">
    <div class="card-siswa">
        <div class="section-title">DETAIL BIODATA SISWA: {{ $siswa->nama_lengkap }}</div>

        <div class="form-body">

            <div class="foto-wrapper">
                <img id="preview-image" src="{{ $siswa->foto ? asset('uploads/foto_siswa/' . $siswa->foto) : asset('images/student.png') }}" alt="Foto Siswa">
            </div>

            <!-- BIODATA UTAMA -->
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
                    <label>Nama Orang Tua</label>
                    <div class="detail-value">{{ $siswa->nama_ortu ?? '-' }}</div>
                </div>
            </div>

            <div class="form-row">
                <div class="full-width-column"> <label>Alamat Lengkap</label>
                    <div class="detail-value" style="min-height:80px; align-items: flex-start; padding-top:10px;">
                        {{ $siswa->alamat ?? '-' }}
                    </div>
                </div>
            </div>

            <style>
            @media (min-width: 768px) {
                .full-width-column { grid-column: span 2; }
            }
            </style>

            <!-- DETAIL SISWA -->
            @php $detail = $siswa->detailSiswa; @endphp
            <div class="detail-container">
                <div class="detail-box">
                    <div class="header">BIODATA DETAIL SISWA</div>
                    <div class="body">
                        <div class="form-group"><label>Cita-cita</label><div class="detail-value">{{ $detail->cita_cita ?? '-' }}</div></div>
                        <div class="form-group"><label>Hobi</label><div class="detail-value">{{ $detail->hobi ?? '-' }}</div></div>
                        <div class="form-group"><label>Berat Badan</label><div class="detail-value">{{ $detail->berat_badan ?? '-' }}</div></div>
                        <div class="form-group"><label>Tinggi Badan</label><div class="detail-value">{{ $detail->tinggi_badan ?? '-' }}</div></div>
                        <div class="form-group"><label>Anak ke-</label><div class="detail-value">{{ $detail->anak_ke ?? '-' }}</div></div>
                        <div class="form-group"><label>Jumlah Saudara</label><div class="detail-value">{{ $detail->jumlah_saudara ?? '-' }}</div></div>
                    </div>
                </div>

                <div class="detail-box">
                    <div class="header">DATA ALAMAT SISWA</div>
                    <div class="body">
                        <div class="form-group"><label>Nama Jalan</label><div class="detail-value">{{ $detail->nama_jalan ?? '-' }}</div></div>
                        <div class="form-group"><label>RT</label><div class="detail-value">{{ $detail->rt ?? '-' }}</div></div>
                        <div class="form-group"><label>RW</label><div class="detail-value">{{ $detail->rw ?? '-' }}</div></div>
                        <div class="form-group"><label>Dusun</label><div class="detail-value">{{ $detail->dusun ?? '-' }}</div></div>
                        <div class="form-group"><label>Desa</label><div class="detail-value">{{ $detail->desa ?? '-' }}</div></div>
                        <div class="form-group"><label>Kode Pos</label><div class="detail-value">{{ $detail->kode_pos ?? '-' }}</div></div>
                    </div>
                </div>
            </div>

            <!-- DATA ORANG TUA -->
            @php $ortu = $siswa->orangTua; @endphp

            <div class="detail-container">
                <div class="detail-box">
                    <div class="header">BIODATA AYAH</div>
                    <div class="body">
                        <div class="form-group"><label>Nama Ayah</label><div class="detail-value">{{ $ortu->nama_ayah ?? '-' }}</div></div>
                        <div class="form-group"><label>NIK</label><div class="detail-value">{{ $ortu->nik_ayah ?? '-' }}</div></div>
                        <div class="form-group"><label>Pekerjaan</label><div class="detail-value">{{ $ortu->pekerjaan_ayah ?? '-' }}</div></div>
                        <div class="form-group"><label>No Telp</label><div class="detail-value">{{ $ortu->no_telp_ayah ?? '-' }}</div></div>
                    </div>
                </div>

                <div class="detail-box">
                    <div class="header">BIODATA IBU</div>
                    <div class="body">
                        <div class="form-group"><label>Nama Ibu</label><div class="detail-value">{{ $ortu->nama_ibu ?? '-' }}</div></div>
                        <div class="form-group"><label>NIK</label><div class="detail-value">{{ $ortu->nik_ibu ?? '-' }}</div></div>
                        <div class="form-group"><label>Pekerjaan</label><div class="detail-value">{{ $ortu->pekerjaan_ibu ?? '-' }}</div></div>
                        <div class="form-group"><label>No Telp</label><div class="detail-value">{{ $ortu->no_telp_ibu ?? '-' }}</div></div>
                    </div>
                </div>
            </div>

            <!-- DATA WALI -->
            <div class="detail-container">
                <div class="detail-box">
                    <div class="header">BIODATA WALI</div>
                    <div class="body">
                        <div class="form-group"><label>Nama Wali</label><div class="detail-value">{{ $ortu->nama_wali ?? '-' }}</div></div>
                        <div class="form-group"><label>NIK</label><div class="detail-value">{{ $ortu->nik_wali ?? '-' }}</div></div>
                        <div class="form-group"><label>Pekerjaan</label><div class="detail-value">{{ $ortu->pekerjaan_wali ?? '-' }}</div></div>
                        <div class="form-group"><label>No Telp</label><div class="detail-value">{{ $ortu->no_telp_wali ?? '-' }}</div></div>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('wali.datasiswa.edit', $siswa->nis) }}" class="btn-blue">Edit Data</a>
                <a href="{{ route('wali.datasiswa') }}" class="btn-gray">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
