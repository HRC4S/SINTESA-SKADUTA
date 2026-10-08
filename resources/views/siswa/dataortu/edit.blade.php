@extends('layouts.siswa')

@section('title', 'Edit Data Orang Tua | SINTESA')
@section('page_title', 'Edit Data Orang Tua')

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
        padding: 12px 20px;
        font-weight: 600;
        font-size: 1.1rem;
        width: 100%;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .form-body { padding: 30px 50px; }

    label { font-weight: 600; color: #333; font-size: 0.9rem; }

    input, select, textarea {
        width: 100%;
        padding: 8px 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        background: #f9f9f9;
        font-size: 0.95rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px 40px;
        margin-bottom: 20px;
    }

    .btn-blue { 
        display:inline-block; background-color:#1e3a67; color:white;
        padding:8px 16px; border-radius:5px; text-decoration:none; border:none;
        transition:0.3s; margin:0 6px;
    }
    .btn-blue:hover { background-color:#0056b3; }

    .btn-gray { 
        display:inline-block; background-color:#4a4a4a; color:white;
        padding:8px 16px; border-radius:5px; text-decoration:none; border:none;
        margin:0 6px;
    }
    .btn-gray:hover { background-color:#3a3a3a; }

    .detail-container { display:flex; flex-wrap:wrap; gap:20px; margin-top:30px; }

    .detail-box {
        flex:1; min-width:350px; background-color:#f9fafc; border-radius:10px;
        overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.06);
    }

    .detail-box .header { 
        background-color:#1e3a67; color:white; font-weight:600;
        padding:10px 15px; border-top-left-radius:10px; border-top-right-radius:10px;
    }

    .detail-box .body { padding:20px; }

    .body .form-group { margin-bottom:15px; }

    .scrollable-content { max-height:80vh; overflow-y:auto; padding-right:10px; }
</style>

<div class="scrollable-content">
    <div class="card-siswa">
        <div class="section-title">EDIT DATA ORANG TUA SISWA: {{ $siswa->nama_lengkap }}</div>

        <div class="form-body">
            <form action="{{ route('siswa.ortu.update', $siswa->nis) }}" method="POST">
                @csrf
                @method('PUT')

                @php $ortu = $siswa->orangTua ?? null; @endphp

                <div class="detail-container">

                    {{-- AYAH --}}
                    <div class="detail-box">
                        <div class="header">BIODATA AYAH</div>
                        <div class="body">
                            <div class="form-group"><label>Nama Ayah</label><input type="text" name="nama_ayah" value="{{ $ortu->nama_ayah ?? '' }}"></div>
                            <div class="form-group"><label>NIK</label><input type="text" name="nik_ayah" value="{{ $ortu->nik_ayah ?? '' }}"></div>
                            <div class="form-group"><label>Tahun Lahir</label><input type="number" name="tahun_lahir_ayah" value="{{ $ortu->tahun_lahir_ayah ?? '' }}"></div>
                            <div class="form-group"><label>Pendidikan</label><input type="text" name="pendidikan_ayah" value="{{ $ortu->pendidikan_ayah ?? '' }}"></div>
                            <div class="form-group"><label>Pekerjaan</label><input type="text" name="pekerjaan_ayah" value="{{ $ortu->pekerjaan_ayah ?? '' }}"></div>
                            <div class="form-group"><label>Penghasilan</label><input type="text" name="penghasilan_ayah" value="{{ $ortu->penghasilan_ayah ?? '' }}"></div>
                            <div class="form-group"><label>Status Hidup</label><input type="text" name="status_hidup_ayah" value="{{ $ortu->status_hidup_ayah ?? '' }}"></div>
                            <div class="form-group"><label>No. Telepon</label><input type="text" name="no_telp_ayah" value="{{ $ortu->no_telp_ayah ?? '' }}"></div>
                        </div>
                    </div>

                    {{-- IBU --}}
                    <div class="detail-box">
                        <div class="header">BIODATA IBU</div>
                        <div class="body">
                            <div class="form-group"><label>Nama Ibu</label><input type="text" name="nama_ibu" value="{{ $ortu->nama_ibu ?? '' }}"></div>
                            <div class="form-group"><label>NIK</label><input type="text" name="nik_ibu" value="{{ $ortu->nik_ibu ?? '' }}"></div>
                            <div class="form-group"><label>Tahun Lahir</label><input type="number" name="tahun_lahir_ibu" value="{{ $ortu->tahun_lahir_ibu ?? '' }}"></div>
                            <div class="form-group"><label>Pendidikan</label><input type="text" name="pendidikan_ibu" value="{{ $ortu->pendidikan_ibu ?? '' }}"></div>
                            <div class="form-group"><label>Pekerjaan</label><input type="text" name="pekerjaan_ibu" value="{{ $ortu->pekerjaan_ibu ?? '' }}"></div>
                            <div class="form-group"><label>Penghasilan</label><input type="text" name="penghasilan_ibu" value="{{ $ortu->penghasilan_ibu ?? '' }}"></div>
                            <div class="form-group"><label>Status Hidup</label><input type="text" name="status_hidup_ibu" value="{{ $ortu->status_hidup_ibu ?? '' }}"></div>
                            <div class="form-group"><label>No. Telepon</label><input type="text" name="no_telp_ibu" value="{{ $ortu->no_telp_ibu ?? '' }}"></div>
                        </div>
                    </div>

                    {{-- WALI --}}
                    <div class="detail-box">
                        <div class="header">BIODATA WALI</div>
                        <div class="body">
                            <div class="form-group"><label>Nama Wali</label><input type="text" name="nama_wali" value="{{ $ortu->nama_wali ?? '' }}"></div>
                            <div class="form-group"><label>NIK</label><input type="text" name="nik_wali" value="{{ $ortu->nik_wali ?? '' }}"></div>
                            <div class="form-group"><label>Tahun Lahir</label><input type="number" name="tahun_lahir_wali" value="{{ $ortu->tahun_lahir_wali ?? '' }}"></div>
                            <div class="form-group"><label>Pendidikan</label><input type="text" name="pendidikan_wali" value="{{ $ortu->pendidikan_wali ?? '' }}"></div>
                            <div class="form-group"><label>Pekerjaan</label><input type="text" name="pekerjaan_wali" value="{{ $ortu->pekerjaan_wali ?? '' }}"></div>
                            <div class="form-group"><label>Penghasilan</label><input type="text" name="penghasilan_wali" value="{{ $ortu->penghasilan_wali ?? '' }}"></div>
                            <div class="form-group"><label>Status Hidup</label><input type="text" name="status_hidup_wali" value="{{ $ortu->status_hidup_wali ?? '' }}"></div>
                            <div class="form-group"><label>No. Telepon</label><input type="text" name="no_telp_wali" value="{{ $ortu->no_telp_wali ?? '' }}"></div>
                        </div>
                    </div>

                </div>

                <div class="text-center mt-5">
                    <button type="submit" class="btn-blue">Simpan Perubahan</button>
                    <a href="{{ route('siswa.ortu.show', $siswa->nis) }}" class="btn-gray">Batal</a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
