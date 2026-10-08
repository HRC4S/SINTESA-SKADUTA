@extends('layouts.admin')

@section('title', 'Tambah Data Siswa')
@section('page_title', 'Tambah Data Siswa')

@section('content')
<style>
    /* Global Card Style */
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

    /* Padding adjustment for mobile */
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

    .upload-btn {
        background-color: #1e3a67;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        margin-top: 10px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: 0.3s;
    }

    .upload-btn:hover {
        background-color: #2a4c7e;
    }

    label {
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        margin-bottom: 6px;
        display: block;
    }

    .form-control, .form-select {
        border-radius: 6px;
        border: 1px solid #ccc;
        padding: 10px 12px;
        font-size: 0.95rem;
        width: 100%;
        margin-bottom: 5px;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #1e3a67;
        box-shadow: 0 0 0 2px rgba(30, 58, 103, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px 40px;
        margin-bottom: 20px;
    }

    /* Single column layout for mobile forms */
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
        min-width: 300px;
        background-color: #f9fafc;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    /* Full width detail box on mobile */
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

    .btn-primary {
        background-color: #1e3a67;
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 500;
        color: white;
        transition: 0.3s;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color: #2a4c7e;
    }

    .btn-secondary {
        background-color: #f0f2f5;
        color: #555;
        border: 1px solid #ddd;
        padding: 12px 24px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: 0.3s;
        display: inline-block;
        margin-left: 10px;
    }

    .btn-secondary:hover {
        background-color: #e0e2e5;
    }

    .scrollable-content {
        max-height: 80vh;
        overflow-y: auto;
        padding-right: 10px;
    }

    /* Better scroll height on mobile */
    @media (max-width: 768px) {
        .scrollable-content {
            max-height: 85vh;
            padding-right: 5px; 
        }
        
        .mt-4.text-center {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .btn-primary, .btn-secondary {
            width: 100%;
            margin-left: 0;
            text-align: center;
        }
    }
</style>

<div class="scrollable-content">
    <div class="card-siswa">
        <div class="section-title">BIODATA SISWA</div>

        <div class="form-body">
            <form action="{{ route('admin.datasiswa.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="foto-wrapper">
                    <img id="preview-image" src="{{ asset('images/student.png') }}" alt="Foto Siswa">
                    <input type="file" id="foto" name="foto" class="d-none" accept="image/*" style="display:none;">
                    <br>
                    <button type="button" class="upload-btn" onclick="document.getElementById('foto').click()">Upload Foto</button>
                </div>

                <div class="form-row">
                    <div>
                        <label>NIS</label>
                        <input type="text" name="nis" class="form-control" required>
                    </div>
                    <div>
                        <label>NISN</label>
                        <input type="text" name="nisn" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" required>
                    </div>
                    <div>
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div>
                        <label>No. WhatsApp</label>
                        <input type="text" name="no_whatsapp" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label>Rombel</label>
                        <select name="rombel" class="form-select">
                            <option value="">Pilih Rombel</option>
                            <option value="X DKV 1">X DKV 1</option>
                            <option value="X DKV 2">X DKV 2</option>
                            <option value="X DPIB 1">X DPIB 1</option>
                            <option value="X DPIB 2">X DPIB 2</option>
                            <option value="X DPIB 3">X DPIB 3</option>
                            <option value="X GEOMATIKA">X GEOMATIKA</option>
                            <option value="X KGS ">X KGS</option>
                            <option value="X MEKATRONIKA">X MEKATORNIKA</option>
                            <option value="X SIJA 1">X SIJA 1</option>
                            <option value="X SIJA 2">X SIJA 2</option>
                            <option value="X TAV">X TAV</option>
                            <option value="X TITL 1">X TITL 1</option>
                            <option value="X TITL 2">X TITL 2</option>
                            <option value="X TITL 3">X TITL 3</option>
                            <option value="X TITL 4">X TITL 4</option>
                            <option value="X TKR 1">X TKR 1</option>
                            <option value="X TKR 2">X TKR 2</option>
                            <option value="X TKR 3">X TKR 3</option>
                            <option value="X TKR 4">X TKR 4</option>
                            <option value="X TP 1">X TP 1</option>
                            <option value="X TP 2">X TP 2</option>
                            <option value="X TP 3">X TP 3</option>
                            <option value="X TP 4">X TP 4</option>
                        </select>
                    </div>
                    <div>
                        <label>Jurusan</label>
                        <select name="jurusan" class="form-select">
                            <option value="">Pilih Jurusan</option>
                            <option value="Desain Komunikasi Visual">Desain Komunikasi Visual</option>
                            <option value="Desain Pemodelan dan Informasi Bangunan">Desain Pemodelan dan Informasi Bangunan</option>
                            <option value="Teknik Geospasial">Teknik Geospasial</option>
                            <option value="Konstruksi Gedung dan Sanitasi">Konstruksi Gedung dan Sanitasi</option>
                            <option value="Teknik Mekatronika">Teknik Mekatronika</option>
                            <option value="Sistem Informasi Jaringan dan Aplikasi ( Pengembangan Perangkat Lunak dan Gim )">Sistem Informasi Jaringan dan Aplikasi ( Pengembangan Perangkat Lunak dan Gim )</option>
                            <option value="Teknik Audio Video">Teknik Audio Video</option>
                            <option value="Teknik Instalasi Tenaga Listrik">Teknik Instalasi Tenaga Listrik</option>
                            <option value="Teknik Kendaraan Ringan">Teknik Kendaraan Ringan</option>
                            <option value="Teknik Pemesinan">Teknik Pemesinan</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control">
                    </div>
                    <div>
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label>Agama</label>
                        <input type="text" name="agama" class="form-control">
                    </div>
                    <div>
                        <label>Nama Orang Tua (Utama)</label>
                        <input type="text" name="nama_ortu" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div style="grid-column: span 2; @media (max-width: 768px) { grid-column: span 1; }">
                        <label>Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="detail-container">
                    <div class="detail-box">
                        <div class="header">BIODATA DETAIL SISWA</div>
                        <div class="body">
                            <div class="form-group"><label>Cita-cita</label><input type="text" name="cita_cita" class="form-control"></div>
                            <div class="form-group"><label>Hobi</label><input type="text" name="hobi" class="form-control"></div>
                            <div class="form-group"><label>Berat Badan</label><input type="text" name="berat_badan" class="form-control"></div>
                            <div class="form-group"><label>Tinggi Badan</label><input type="text" name="tinggi_badan" class="form-control"></div>
                            <div class="form-group"><label>Anak ke-</label><input type="number" name="anak_ke" class="form-control"></div>
                            <div class="form-group"><label>Jumlah Saudara</label><input type="number" name="jumlah_saudara" class="form-control"></div>
                            <div class="form-group"><label>Tinggal Dengan</label>
                                <select name="tinggal_dengan" class="form-select">
                                    <option value="">Pilih</option>
                                    <option value="Bersama Orang Tua">Bersama Orang Tua</option>
                                    <option value="Wali">Wali</option>
                                    <option value="Kost">Kost</option>
                                    <option value="Panti Asuhan">Panti Asuhan</option>
                                </select>
                            </div>
                            <div class="form-group"><label>Jarak Rumah</label><input type="text" name="jarak_rumah" class="form-control"></div>
                            <div class="form-group">
                                <label>Waktu Tempuh</label>
                                <select name="waktu_tempuh" class="form-select">
                                    <option value="">Pilih</option>
                                    <option value="5 menit">5 menit</option>
                                    <option value="10 - 15 menit">10 - 15 menit</option>
                                    <option value="25 - 30 menit">25 - 30 menit</option>
                                    <option value="Lebih dari 45 Menit">Lebih dari 45 Menit</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Transportasi</label>
                                <select name="transportasi" class="form-select">
                                    <option value="">Pilih</option>
                                    <option value="Sepeda Motor ( Antar Jemput )">Sepeda Motor ( Antar Jemput )</option>
                                    <option value="Jalan Kaki">Jalan Kaki</option>
                                    <option value="Angkutan Umum">Angkutan Umum</option>
                                    <option value="Motor Pribadi">Motor Pribadi</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="detail-box">
                        <div class="header">DATA ALAMAT SISWA</div>
                        <div class="body">
                            <div class="form-group"><label>Nama Jalan</label><input type="text" name="nama_jalan" class="form-control"></div>
                            <div class="form-group"><label>RT</label><input type="text" name="rt" class="form-control"></div>
                            <div class="form-group"><label>RW</label><input type="text" name="rw" class="form-control"></div>
                            <div class="form-group"><label>Dusun</label><input type="text" name="dusun" class="form-control"></div>
                            <div class="form-group"><label>Desa/Kelurahan</label><input type="text" name="desa" class="form-control"></div>
                            <div class="form-group"><label>Kode Pos</label><input type="text" name="kode_pos" class="form-control"></div>
                        </div>
                    </div>
                </div>

                <div class="detail-container">
                    <div class="detail-box">
                        <div class="header">BIODATA AYAH</div>
                        <div class="body">
                            <div class="form-group"><label>Nama Ayah</label><input type="text" name="nama_ayah" class="form-control"></div>
                            <div class="form-group"><label>NIK</label><input type="text" name="nik_ayah" class="form-control"></div>
                            <div class="form-group"><label>Tahun Lahir</label><input type="number" name="tahun_lahir_ayah" class="form-control"></div>
                            <div class="form-group">
                                <label>Pendidikan</label>
                                <select name="pendidikan_ayah" class="form-select">
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="Tidak Sekolah">Tidak Sekolah</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA/SMK Sederajat">SMA/SMK Sederajat</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <select name="pekerjaan_ayah" class="form-select">
                                    <option value="">Pilih Pekerjaan</option>
                                    <option value="Pensiunan">Pensiunan</option>
                                    <option value="PNS / TNI / Polri">PNS / TNI / Polri</option>
                                    <option value="Karyawan Swasta">Karyawan Swasta</option>
                                    <option value="Wiraswasta">Wiraswasta</option>
                                    <option value="Pedagang Kecil">Pedagang Kecil</option>
                                    <option value="Karyawan BUMN">Karyawan BUMN</option>
                                    <option value="Tidak Bekerja">Tidak Bekerja</option>
                                    <option value="Sudah Meninggal">Sudah Meninggal</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Penghasilan</label>
                                <select name="penghasilan_ayah" class="form-select">
                                    <option value="">Pilih Penghasilan</option>
                                    <option value="Tidak Berpenghasilan">Tidak Berpenghasilan</option>
                                    <option value="Kurang Dari Rp. 500,000">Kurang Dari Rp. 500,000</option>
                                    <option value="Rp. 500,000 - Rp. 999,999">Rp. 500,000 - Rp. 999,999</option>
                                    <option value="Rp. 1,000,000 - Rp. 1,999,999">Rp. 1,000,000 - Rp. 1,999,999</option>
                                    <option value="Rp. 2,000,000 - Rp. 4,999,999">Rp. 2,000,000 - Rp. 4,999,999</option>
                                    <option value="Rp. 5,000,000 - Rp. 20,000,000">Rp. 5,000,000 - Rp. 20,000,000</option>
                                </select>
                            </div>
                            <div class="form-group"><label>Status Hidup</label>
                                <select name="status_hidup_ayah" class="form-control">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Masih Hidup">Masih Hidup</option>
                                    <option value="Sudah Meninggal">Sudah Meninggal</option>
                                </select>
                            </div>
                            <div class="form-group"><label>No. Telepon</label><input type="text" name="no_telp_ayah" class="form-control"></div>
                        </div>
                    </div>

                    <div class="detail-box">
                        <div class="header">BIODATA IBU</div>
                        <div class="body">
                            <div class="form-group"><label>Nama Ibu</label><input type="text" name="nama_ibu" class="form-control"></div>
                            <div class="form-group"><label>NIK</label><input type="text" name="nik_ibu" class="form-control"></div>
                            <div class="form-group"><label>Tahun Lahir</label><input type="number" name="tahun_lahir_ibu" class="form-control"></div>
                            <div class="form-group">
                                <label>Pendidikan</label>
                                <select name="pendidikan_ibu" class="form-select">
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="Tidak Sekolah">Tidak Sekolah</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA/SMK Sederajat">SMA/SMK Sederajat</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <select name="pekerjaan_ibu" class="form-select">
                                    <option value="">Pilih Pekerjaan</option>
                                    <option value="Pensiunan">Pensiunan</option>
                                    <option value="PNS / TNI / Polri">PNS / TNI / Polri</option>
                                    <option value="Karyawan Swasta">Karyawan Swasta</option>
                                    <option value="Wiraswasta">Wiraswasta</option>
                                    <option value="Pedagang Kecil">Pedagang Kecil</option>
                                    <option value="Karyawan BUMN">Karyawan BUMN</option>
                                    <option value="Tidak Bekerja">Tidak Bekerja</option>
                                    <option value="Sudah Meninggal">Sudah Meninggal</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Penghasilan</label>
                                <select name="penghasilan_ibu" class="form-select">
                                    <option value="">Pilih Penghasilan</option>
                                    <option value="Tidak Berpenghasilan">Tidak Berpenghasilan</option>
                                    <option value="Kurang Dari Rp. 500,000">Kurang Dari Rp. 500,000</option>
                                    <option value="Rp. 500,000 - Rp. 999,999">Rp. 500,000 - Rp. 999,999</option>
                                    <option value="Rp. 1,000,000 - Rp. 1,999,999">Rp. 1,000,000 - Rp. 1,999,999</option>
                                    <option value="Rp. 2,000,000 - Rp. 4,999,999">Rp. 2,000,000 - Rp. 4,999,999</option>
                                    <option value="Rp. 5,000,000 - Rp. 20,000,000">Rp. 5,000,000 - Rp. 20,000,000</option>
                                </select>
                            </div>
                            <div class="form-group"><label>Status Hidup</label>
                                <select name="status_hidup_ibu" class="form-control">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Masih Hidup">Masih Hidup</option>
                                    <option value="Sudah Meninggal">Sudah Meninggal</option>
                                </select>
                            </div>
                            <div class="form-group"><label>No. Telepon</label><input type="text" name="no_telp_ibu" class="form-control"></div>
                        </div>
                    </div>
                </div>

                <div class="detail-container">
                    <div class="detail-box" style="flex:1;">
                        <div class="header">BIODATA WALI</div>
                        <div class="body">
                            <div class="form-group"><label>Nama Wali</label><input type="text" name="nama_wali" class="form-control"></div>
                            <div class="form-group"><label>NIK</label><input type="text" name="nik_wali" class="form-control"></div>
                            <div class="form-group"><label>Tahun Lahir</label><input type="number" name="tahun_lahir_wali" class="form-control"></div>
                            <div class="form-group">
                                <label>Pendidikan</label>
                                <select name="pendidikan_wali" class="form-select">
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="Tidak Sekolah">Tidak Sekolah</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA/SMK Sederajat">SMA/SMK Sederajat</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <select name="pekerjaan_wali" class="form-select">
                                    <option value="">Pilih Pekerjaan</option>
                                    <option value="Pensiunan">Pensiunan</option>
                                    <option value="PNS / TNI / Polri">PNS / TNI / Polri</option>
                                    <option value="Karyawan Swasta">Karyawan Swasta</option>
                                    <option value="Wiraswasta">Wiraswasta</option>
                                    <option value="Pedagang Kecil">Pedagang Kecil</option>
                                    <option value="Karyawan BUMN">Karyawan BUMN</option>
                                    <option value="Tidak Bekerja">Tidak Bekerja</option>
                                    <option value="Sudah Meninggal">Sudah Meninggal</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Penghasilan</label>
                                <select name="penghasilan_wali" class="form-select">
                                    <option value="">Pilih Penghasilan</option>
                                    <option value="Tidak Berpenghasilan">Tidak Berpenghasilan</option>
                                    <option value="Kurang Dari Rp. 500,000">Kurang Dari Rp. 500,000</option>
                                    <option value="Rp. 500,000 - Rp. 999,999">Rp. 500,000 - Rp. 999,999</option>
                                    <option value="Rp. 1,000,000 - Rp. 1,999,999">Rp. 1,000,000 - Rp. 1,999,999</option>
                                    <option value="Rp. 2,000,000 - Rp. 4,999,999">Rp. 2,000,000 - Rp. 4,999,999</option>
                                    <option value="Rp. 5,000,000 - Rp. 20,000,000">Rp. 5,000,000 - Rp. 20,000,000</option>
                                </select>
                            </div>
                            <div class="form-group"><label>Status Hidup</label>
                                <select name="status_hidup_wali" class="form-control">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Hidup">Masih Hidup</option>
                                    <option value="Meninggal">Sudah Meninggal</option>
                                </select>
                            </div>
                            <div class="form-group"><label>No. Telepon</label><input type="text" name="no_telp_wali" class="form-control"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                    <a href="{{ route('admin.datasiswa.index') }}" class="btn btn-secondary">Batal</a>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    // preview foto sebelum upload
    document.getElementById('foto').addEventListener('change', function (e) {
        const reader = new FileReader();
        reader.onload = function(){
            document.getElementById('preview-image').src = reader.result;
        }
        reader.readAsDataURL(e.target.files[0]);
    });
</script>
@endsection