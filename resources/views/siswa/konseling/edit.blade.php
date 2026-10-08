@extends('layouts.siswa')
@section('title', 'Edit Pengajuan Konseling')
@section('page_title', 'Edit Pengajuan')

@section('content')

{{-- Load FontAwesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    /* ===========================
       WRAPPER & CARD
       =========================== */
    .edit-wrapper {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        overflow: hidden;
        margin: 0 auto;
        max-width: 950px;
    }

    .header-edit {
        background: #123B6B;
        color: white;
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 4px solid #0e2a4c;
    }
    .header-edit h4 { margin: 0; font-weight: 600; font-size: 1.2rem; }

    .form-container { padding: 30px 40px; }

    /* ===========================
       INPUT STYLES
       =========================== */
    label {
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        margin-bottom: 6px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border-radius: 6px;
        border: 1px solid #ccc;
        background: #fff;
        font-size: 0.95rem;
        box-sizing: border-box;
        transition: 0.3s;
    }

    .form-control:focus {
        border-color: #123B6B;
        outline: none;
        box-shadow: 0 0 0 3px rgba(30, 58, 103, 0.1);
    }

    .input-readonly {
        background-color: #f3f4f6;
        color: #6b7280;
        cursor: not-allowed;
        border-color: #e5e7eb;
    }

    /* ===========================
       GRID LAYOUT
       =========================== */
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    /* ===========================
       BUTTONS
       =========================== */
    .form-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px dashed #eee;
    }

    .btn {
        padding: 10px 25px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
        border: none;
        font-size: 14px;
    }

    .btn-primary { background-color: #123B6B; color: white; }
    .btn-primary:hover { background-color: #0f2e52; transform: translateY(-2px); }

    .btn-secondary { background-color: #6c757d; color: white; }
    .btn-secondary:hover { background-color: #5a6268; }

    /* ===========================
       RESPONSIVE
       =========================== */
    @media (max-width: 768px) {
        .edit-wrapper { margin: 10px; }
        .form-container { padding: 20px; }
        .grid-2, .grid-3 { grid-template-columns: 1fr; gap: 15px; }
        .form-buttons { flex-direction: column-reverse; }
        .btn { width: 100%; justify-content: center; }
    }
</style>

<div class="edit-wrapper">
    
    <div class="header-edit">
        <h4><i class="fas fa-edit"></i> Edit Pengajuan Konseling</h4>
        <span style="background:rgba(255,255,255,0.2); padding:4px 10px; border-radius:15px; font-size:0.85rem;">
            ID: #{{ $konseling->id }}
        </span>
    </div>

    <div class="form-container">
        <form action="{{ route('siswa.konseling.edit', $konseling->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Info Siswa (Readonly) --}}
            <div class="grid-2">
                <div>
                    <label>Nama Siswa</label>
                    <input type="text" value="{{ $konseling->nama_siswa }}" class="form-control input-readonly" readonly>
                </div>
                <div>
                    <label>Kelas</label>
                    <input type="text" value="{{ $konseling->kelas }}" class="form-control input-readonly" readonly>
                </div>
            </div>

            <hr style="border:0; border-top:1px dashed #eee; margin:20px 0;">

            {{-- Jadwal & Konselor (Editable) --}}
            <div class="grid-3">
                <div>
                    <label>Pilih Guru BK <span style="color:red">*</span></label>
                    <select name="guru_bk_nip" class="form-control" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($guruBk as $guru)
                            <option value="{{ $guru->nip }}" 
                                {{ $konseling->guru_bk_nip == $guru->nip ? 'selected' : '' }}>
                                {{ $guru->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Tanggal <span style="color:red">*</span></label>
                    <input type="date" name="tanggal" value="{{ $konseling->tanggal }}" class="form-control" required>
                </div>
                <div>
                    <label>Jam <span style="color:red">*</span></label>
                    <input type="time" name="jam_pengajuan" value="{{ \Carbon\Carbon::parse($konseling->jam_pengajuan)->format('H:i') }}" class="form-control" required>
                </div>
            </div>

            {{-- Detail Masalah (Editable) --}}
            <div class="grid-2">
                <div>
                    <label>Topik Masalah</label>
                    <input type="text" name="topik" value="{{ $konseling->topik }}" class="form-control" required>
                </div>
                <div>
                    <label>Jenis Layanan</label>
                    <select name="jenis_layanan" class="form-control">
                        @foreach(['Konseling Individu', 'Bimbingan Belajar', 'Bimbingan Karir', 'Mediasi', 'Lainnya'] as $jenis)
                            <option value="{{ $jenis }}" {{ $konseling->jenis_layanan == $jenis ? 'selected' : '' }}>
                                {{ $jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label>Latar Belakang Masalah</label>
                <textarea name="latar_belakang" class="form-control" rows="4" required>{{ $konseling->latar_belakang }}</textarea>
            </div>

            <div style="margin-bottom:20px;">
                <label>Harapan / Kegiatan Layanan</label>
                <textarea name="kegiatan_layanan" class="form-control" rows="3">{{ $konseling->kegiatan_layanan }}</textarea>
            </div>

            {{-- Tombol Aksi --}}
            <div class="form-buttons">
                <a href="{{ route('siswa.konseling.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary" onclick="return confirm('Simpan perubahan pengajuan ini?')">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

@endsection