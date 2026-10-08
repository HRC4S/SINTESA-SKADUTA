@extends('layouts.admin')

@section('content')
@section('title', 'Edit Data Siswa')
@section('page_title', 'Edit Data Siswa')

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

    label {
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        margin-bottom: 6px;
        display: block;
    }

    input, select, textarea {
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 0.95rem;
        background-color: #f9f9f9;
        transition: 0.3s;
        margin-bottom: 5px; /* Spacing for tighter layouts */
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #1e3a67;
        background-color: #fff;
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
        min-width: 300px; /* Reduced min-width for better mobile fit */
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

    .detail-box .body > div { /* Targetting potential wrappers inside body if any */
        margin-bottom: 15px;
    }
    
    /* Ensure inputs inside detail box have bottom margin */
    .detail-box .body input, 
    .detail-box .body select {
        margin-bottom: 15px;
    }

    .btn-blue {
        display: inline-block;
        background-color: #1e3a67;
        color: white;
        padding: 12px 24px;
        border-radius: 6px;
        border: none;
        transition: 0.3s;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-blue:hover {
        background-color: #0056b3;
    }

    .btn-gray {
        display: inline-block;
        background-color: #4a4a4a;
        color: white;
        padding: 12px 24px;
        border-radius: 6px;
        text-decoration: none;
        border: none;
        transition: 0.3s;
        font-weight: 600;
        cursor: pointer;
        margin-left: 10px;
    }

    .btn-gray:hover {
        background-color: #3a3a3a;
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
        
        .btn-blue, .btn-gray {
            width: 100%;
            margin-left: 0;
            text-align: center;
        }
    }
</style>

<div class="scrollable-content">
    <div class="card-siswa">
        <div class="section-title">EDIT BIODATA SISWA: {{ $siswa->nama_lengkap }}</div>

        <form action="{{ route('admin.datasiswa.update', $siswa->nis) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-body">
                <div class="foto-wrapper">
                    <img id="preview-image" 
                         src="{{ $siswa->foto ? asset('uploads/foto_siswa/'.$siswa->foto) : asset('images/student.png') }}" 
                         alt="Foto Siswa">
                    <br><br>
                    <input type="file" name="foto" accept="image/*" onchange="previewImage(event)" style="width: auto; max-width: 100%;">
                </div>

                {{-- Baris utama data siswa --}}
                <div class="form-row">
                    <div>
                        <label>NIS</label>
                        <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}">
                    </div>
                    <div>
                        <label>NISN</label>
                        <input type="text" name="nisn" value="{{ old('nisn', $siswa->nisn) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}">
                    </div>
                    <div>
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin">
                            <option value="Laki-laki" {{ $siswa->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ $siswa->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', $siswa->email) }}">
                    </div>
                    <div>
                        <label>No. WhatsApp</label>
                        <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp', $siswa->no_whatsapp) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label>Rombel</label>
                        <select name="rombel" class="form-select">
                            <option value="">Pilih Rombel</option>
                            @foreach ([
                                'X DKV 1','X DKV 2','X DPIB 1','X DPIB 2','X DPIB 3',
                                'X GEOMATIKA','X KGS','X MEKATRONIKA','X SIJA 1','X SIJA 2',
                                'X TAV','X TITL 1','X TITL 2','X TITL 3','X TITL 4',
                                'X TKR 1','X TKR 2','X TKR 3','X TKR 4',
                                'X TP 1','X TP 2','X TP 3','X TP 4'
                            ] as $rombel)
                                <option value="{{ $rombel }}" {{ old('rombel', $siswa->rombel) == $rombel ? 'selected' : '' }}>
                                    {{ $rombel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>Jurusan</label>
                        <select name="jurusan" class="form-select">
                            <option value="">Pilih Jurusan</option>
                            @foreach ([
                                'Desain Komunikasi Visual',
                                'Desain Pemodelan dan Informasi Bangunan',
                                'Teknik Geospasial',
                                'Konstruksi Gedung dan Sanitasi',
                                'Teknik Mekatronika',
                                'Sistem Informasi Jaringan dan Aplikasi ( Pengembangan Perangkat Lunak dan Gim )',
                                'Teknik Audio Video',
                                'Teknik Instalasi Tenaga Listrik',
                                'Teknik Kendaraan Ringan',
                                'Teknik Pemesinan'
                            ] as $jurusan)
                                <option value="{{ $jurusan }}" {{ old('jurusan', $siswa->jurusan) == $jurusan ? 'selected' : '' }}>
                                    {{ $jurusan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
                    </div>
                    <div>
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label>Agama</label>
                        <input type="text" name="agama" value="{{ old('agama', $siswa->agama) }}">
                    </div>
                    <div>
                        <label>Nama Orang Tua (Utama)</label>
                        <input type="text" name="nama_ortu" value="{{ old('nama_ortu', $siswa->nama_ortu) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div style="grid-column: span 2; @media (max-width: 768px) { grid-column: span 1; }">
                        <label>Alamat Lengkap</label>
                        <textarea name="alamat" rows="3">{{ old('alamat', $siswa->alamat) }}</textarea>
                    </div>
                </div>

                {{-- Detail Box --}}
                @php 
                    $detail = $siswa->detailSiswa;
                    $ortu = $siswa->orangTua;
                @endphp

                <div class="detail-container">
                    <div class="detail-box">
                        <div class="header">BIODATA DETAIL SISWA</div>
                        <div class="body">
                            <label>Cita-cita</label><input type="text" name="cita_cita" value="{{ old('cita_cita', $detail->cita_cita ?? '') }}">
                            <label>Hobi</label><input type="text" name="hobi" value="{{ old('hobi', $detail->hobi ?? '') }}">
                            <label>Berat Badan</label><input type="number" name="berat_badan" value="{{ old('berat_badan', $detail->berat_badan ?? '') }}">
                            <label>Tinggi Badan</label><input type="number" name="tinggi_badan" value="{{ old('tinggi_badan', $detail->tinggi_badan ?? '') }}">
                            <label>Anak ke-</label><input type="number" name="anak_ke" value="{{ old('anak_ke', $detail->anak_ke ?? '') }}">
                            <label>Jumlah Saudara</label><input type="number" name="jumlah_saudara" value="{{ old('jumlah_saudara', $detail->jumlah_saudara ?? '') }}">
                            <label for="tinggal_dengan">Tinggal Dengan</label>
                                <select name="tinggal_dengan" id="tinggal_dengan" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach ([
                                        'Bersama Orang Tua',
                                        'Wali',
                                        'Kost',
                                        'Panti Asuhan',
                                    ] as $option)
                                        <option value="{{ $option }}" {{ old('tinggal_dengan', $detail->tinggal_dengan ?? '') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            <label>Jarak Rumah</label><input type="text" name="jarak_rumah" value="{{ old('jarak_rumah', $detail->jarak_rumah ?? '') }}">
                            <label for="waktu_tempuh">Waktu Tempuh ke Sekolah</label>
                                <select name="waktu_tempuh" id="waktu_tempuh" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach ([
                                        '5 menit',
                                        '10 - 15 menit',
                                        '25 - 30 menit',
                                        'Lebih dari 45 Menit',
                                    ] as $option)
                                        <option value="{{ $option }}" {{ old('waktu_tempuh', $detail->waktu_tempuh ?? '') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            <label for="transportasi">Transportasi ke Sekolah</label>
                                <select name="transportasi" id="transportasi" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach ([
                                        'Sepeda Motor ( Antar Jemput )',
                                        'Jalan Kaki',
                                        'Angkutan Umum',
                                        'Mobil Pribadi',
                                        'Motor Pribadi',
                                    ] as $option)
                                        <option value="{{ $option }}" {{ old('transportasi', $detail->transportasi ?? '') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                        </div>
                    </div>

                    <div class="detail-box">
                        <div class="header">DATA ALAMAT SISWA</div>
                        <div class="body">
                            <label>Nama Jalan</label><input type="text" name="nama_jalan" value="{{ old('nama_jalan', $detail->nama_jalan ?? '') }}">
                            <label>RT</label><input type="text" name="rt" value="{{ old('rt', $detail->rt ?? '') }}">
                            <label>RW</label><input type="text" name="rw" value="{{ old('rw', $detail->rw ?? '') }}">
                            <label>Dusun</label><input type="text" name="dusun" value="{{ old('dusun', $detail->dusun ?? '') }}">
                            <label>Desa/Kelurahan</label><input type="text" name="desa" value="{{ old('desa', $detail->desa ?? '') }}">
                            <label>Kode Pos</label><input type="text" name="kode_pos" value="{{ old('kode_pos', $detail->kode_pos ?? '') }}">
                        </div>
                    </div>
                </div>

                {{-- AYAH, IBU, WALI --}}
                <div class="detail-container">
                    <div class="detail-box">
                        <div class="header">BIODATA AYAH</div>
                        <div class="body">
                            <label>Nama Ayah</label><input type="text" name="nama_ayah" value="{{ old('nama_ayah', $ortu->nama_ayah ?? '') }}">
                            <label>NIK</label><input type="text" name="nik_ayah" value="{{ old('nik_ayah', $ortu->nik_ayah ?? '') }}">
                            <label>Tahun Lahir</label><input type="text" name="tahun_lahir_ayah" value="{{ old('tahun_lahir_ayah', $ortu->tahun_lahir_ayah ?? '') }}">
                            <label>Pendidikan</label>
                                <select name="pendidikan_ayah" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach ([
                                        'Tidak Sekolah', 'SD', 'SMP', 'SMA/SMK Sederajat',
                                         'D3', 'S1', 'S2', 'S3'
                                    ] as $option)
                                        <option value="{{ $option }}" {{ old('pendidikan_ayah', $ortu->pendidikan_ayah ?? '') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            <label>Pekerjaan</label>
                                <select name="pekerjaan_ayah" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach ([
                                        'Pensiunan', 'PNS / TNI / Polri', 'Karyawan Swasta', 'Wiraswasta', 'Pedagang Kecil', 'Karyawan BUMN', 'Tidak Bekerja', 'Sudah Meninggal', 'Lainnya'
                                    ] as $option)
                                        <option value="{{ $option }}" {{ old('pekerjaan_ayah', $ortu->pekerjaan_ayah ?? '') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            <label>Penghasilan</label>
                                <select name="penghasilan_ayah" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach ([
                                        'Tidak Berpenghasilan',
                                        'Kurang Dari Rp. 500,000',
                                        'Rp. 500,000 - Rp. 999,999',
                                        'Rp. 1,000,000 - Rp. 1,999,999',
                                        'Rp. 2,000,000 - Rp. 4,999,999',
                                        'Rp. 5,000,000 - Rp. 20,000,000',
                                    ] as $option)
                                        <option value="{{ $option }}" {{ old('penghasilan_ayah', $ortu->penghasilan_ayah ?? '') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            <label>Status Hidup</label>
                                <select name="status_hidup_ayah" class="form-control">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Masih Hidup" {{ old('status_hidup_ayah', $ortu->status_hidup_ayah ?? '') == 'Masih Hidup' ? 'selected' : '' }}>Masih Hidup</option>
                                    <option value="Sudah Meninggal" {{ old('status_hidup_ayah', $ortu->status_hidup_ayah ?? '') == 'Sudah Meninggal' ? 'selected' : '' }}>Sudah Meninggal</option>
                                </select>
                            <label>No. Telepon</label><input type="text" name="no_telp_ayah" value="{{ old('no_telp_ayah', $ortu->no_telp_ayah ?? '') }}">
                        </div>
                    </div>

                    <div class="detail-box">
                        <div class="header">BIODATA IBU</div>
                        <div class="body">
                            <label>Nama Ibu</label><input type="text" name="nama_ibu" value="{{ old('nama_ibu', $ortu->nama_ibu ?? '') }}">
                            <label>NIK</label><input type="text" name="nik_ibu" value="{{ old('nik_ibu', $ortu->nik_ibu ?? '') }}">
                            <label>Tahun Lahir</label><input type="text" name="tahun_lahir_ibu" value="{{ old('tahun_lahir_ibu', $ortu->tahun_lahir_ibu ?? '') }}">
                            <label>Pendidikan</label>
                                <select name="pendidikan_ibu" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach ([
                                        'Tidak Sekolah', 'SD', 'SMP', 'SMA/SMK Sederajat',
                                         'D3', 'S1', 'S2', 'S3'
                                    ] as $option)
                                        <option value="{{ $option }}" {{ old('pendidikan_ibu', $ortu->pendidikan_ibu ?? '') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            <label>Pekerjaan</label>
                                <select name="pekerjaan_ibu" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach ([
                                        'Pensiunan', 'PNS / TNI / Polri', 'Karyawan Swasta', 'Wiraswasta', 'Pedagang Kecil', 'Karyawan BUMN', 'Tidak Bekerja', 'Sudah Meninggal', 'Lainnya'
                                    ] as $option)
                                        <option value="{{ $option }}" {{ old('pekerjaan_ibu', $ortu->pekerjaan_ibu ?? '') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            <label>Penghasilan</label>
                                <select name="penghasilan_ibu" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach ([
                                        'Tidak Berpenghasilan',
                                        'Kurang Dari Rp. 500,000',
                                        'Rp. 500,000 - Rp. 999,999',
                                        'Rp. 1,000,000 - Rp. 1,999,999',
                                        'Rp. 2,000,000 - Rp. 4,999,999',
                                        'Rp. 5,000,000 - Rp. 20,000,000',
                                    ] as $option)
                                        <option value="{{ $option }}" {{ old('penghasilan_ibu', $ortu->penghasilan_ibu ?? '') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            <label>Status Hidup</label>
                                <select name="status_hidup_ibu" class="form-control">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Masih Hidup" {{ old('status_hidup_ibu', $ortu->status_hidup_ibu ?? '') == 'Masih Hidup' ? 'selected' : '' }}>Masih Hidup</option>
                                    <option value="Sudah Meninggal" {{ old('status_hidup_ibu', $ortu->status_hidup_ibu ?? '') == 'Sudah Meninggal' ? 'selected' : '' }}>Sudah Meninggal</option>
                                </select>
                            <label>No. Telepon</label><input type="text" name="no_telp_ibu" value="{{ old('no_telp_ibu', $ortu->no_telp_ibu ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="detail-container">
                    <div class="detail-box" style="flex:1;">
                        <div class="header">BIODATA WALI</div>
                        <div class="body">
                            <label>Nama Wali</label><input type="text" name="nama_wali" value="{{ old('nama_wali', $ortu->nama_wali ?? '') }}">
                            <label>NIK</label><input type="text" name="nik_wali" value="{{ old('nik_wali', $ortu->nik_wali ?? '') }}">
                            <label>Tahun Lahir</label><input type="text" name="tahun_lahir_wali" value="{{ old('tahun_lahir_wali', $ortu->tahun_lahir_wali ?? '') }}">
                            <label>Pendidikan</label>
                                <select name="pendidikan_wali" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach ([
                                        'Tidak Sekolah', 'SD', 'SMP', 'SMA/SMK Sederajat',
                                         'D3', 'S1', 'S2', 'S3'
                                    ] as $option)
                                        <option value="{{ $option }}" {{ old('pendidikan_wali', $ortu->pendidikan_wali ?? '') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            <label>Pekerjaan</label>
                                <select name="pekerjaan_wali" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach ([
                                        'Pensiunan', 'PNS / TNI / Polri', 'Karyawan Swasta', 'Wiraswasta', 'Pedagang Kecil', 'Karyawan BUMN', 'Tidak Bekerja', 'Sudah Meninggal', 'Lainnya'
                                    ] as $option)
                                        <option value="{{ $option }}" {{ old('pekerjaan_wali', $ortu->pekerjaan_wali ?? '') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            <label>Penghasilan</label>
                                <select name="penghasilan_wali" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach ([
                                        'Tidak Berpenghasilan',
                                        'Kurang Dari Rp. 500,000',
                                        'Rp. 500,000 - Rp. 999,999',
                                        'Rp. 1,000,000 - Rp. 1,999,999',
                                        'Rp. 2,000,000 - Rp. 4,999,999',
                                        'Rp. 5,000,000 - Rp. 20,000,000',
                                    ] as $option)
                                        <option value="{{ $option }}" {{ old('penghasilan_wali', $ortu->penghasilan_wali ?? '') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            <label>Status Hidup</label>
                                <select name="status_hidup_wali" class="form-control">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Masih Hidup" {{ old('status_hidup_wali', $ortu->status_hidup_wali ?? '') == 'Masih Hidup' ? 'selected' : '' }}>Masih Hidup</option>
                                    <option value="Sudah Meninggal" {{ old('status_hidup_wali', $ortu->status_hidup_wali ?? '') == 'Sudah Meninggal' ? 'selected' : '' }}>Sudah Meninggal</option>
                                </select>
                            <label>No. Telepon</label><input type="text" name="no_telp_wali" value="{{ old('no_telp_wali', $ortu->no_telp_wali ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn-blue">Simpan Perubahan</button>
                    <a href="{{ route('admin.datasiswa.index') }}" class="btn-gray">Batal</a>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const image = document.getElementById('preview-image');
    image.src = URL.createObjectURL(event.target.files[0]);
}
</script>
@endsection