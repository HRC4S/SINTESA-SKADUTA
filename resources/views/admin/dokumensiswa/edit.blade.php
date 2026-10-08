@extends('layouts.admin')

@section('title', 'Edit Dokumen Siswa')
@section('page_title', 'Edit Dokumen Siswa')

@section('content')

{{-- ALERT SUCCESS --}}
@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

{{-- ALERT ERROR --}}
@if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin:0; padding-left:20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-wrapper">
    <div class="card-custom">
        <div class="card-header">
            <h5><i class="fas fa-edit"></i> Form Edit Dokumen Siswa</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.dokumensiswa.update', $siswa->nis) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- NIS (Readonly) --}}
                <div class="form-group">
                    <label for="nis">NIS Siswa</label>
                    <input type="text" name="nis" id="nis" value="{{ $siswa->nis }}" class="form-control readonly-input" readonly>
                </div>

                {{-- Nama Lengkap (Readonly) --}}
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ $siswa->nama_lengkap }}" class="form-control readonly-input" readonly>
                </div>

                {{-- Pilih Jenis Dokumen --}}
                <div class="form-group">
                    <label for="dokumen">Jenis Dokumen <span class="text-danger">*</span></label>
                    <select name="dokumen" id="dokumen" class="form-control" required>
                        <option value="">-- Pilih Jenis Dokumen --</option>
                        @foreach ($dokumenWajib as $jenis)
                            <option value="{{ $jenis }}" {{ old('dokumen') == $jenis ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $jenis)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Upload File --}}
                <div class="form-group">
                    <label for="file">Upload Dokumen Baru (Opsional)</label>
                    <div class="file-input-wrapper">
                        <input type="file" name="file" id="file" class="form-control file-input">
                    </div>
                    
                    {{-- Info File Existing --}}
                    @php
                        $dokumenExisting = $siswa->dokumen->first();
                    @endphp
                    @if ($dokumenExisting && $dokumenExisting->file_path)
                        <div class="existing-file-info">
                            <i class="fas fa-file-alt"></i> 
                            <span>Dokumen terakhir yang diunggah:</span>
                            <a href="{{ asset('storage/'.$dokumenExisting->file_path) }}" target="_blank" class="link-file">
                                Lihat File
                            </a>
                        </div>
                    @endif
                    <small class="text-muted">Format: JPG, PNG, atau PDF. Maks 2MB.</small>
                </div>

                {{-- Tombol Aksi --}}
                <div class="form-actions">
                    <a href="{{ route('admin.dokumensiswa.index') }}" class="btn btn-batal">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-simpan">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* ===========================
       BASE STYLES (DESKTOP)
       =========================== */
    .form-wrapper {
        display: flex;
        justify-content: center;
        padding-top: 20px;
    }

    .card-custom {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        width: 100%;
        max-width: 600px; /* Batasi lebar di desktop */
        overflow: hidden;
    }

    .card-header {
        background-color: #123B6B;
        color: white;
        padding: 15px 25px;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }
    .card-header h5 { margin: 0; font-weight: 600; font-size: 1.1rem; }

    .card-body { padding: 30px; }

    /* Form Elements */
    .form-group { margin-bottom: 20px; }
    
    .form-group label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
        display: block;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        transition: 0.3s;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #123B6B;
        box-shadow: 0 0 0 3px rgba(18, 59, 107, 0.1);
    }

    .readonly-input {
        background-color: #f2f4f6;
        color: #6c757d;
        cursor: not-allowed;
    }

    /* File Info Box */
    .existing-file-info {
        background: #e3f2fd;
        padding: 10px 15px;
        border-radius: 6px;
        margin-top: 8px;
        margin-bottom: 5px;
        font-size: 13px;
        color: #0d47a1;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .link-file {
        font-weight: 700;
        text-decoration: underline;
        color: #0d47a1;
    }
    
    .text-danger { color: #dc3545; }
    .text-muted { font-size: 12px; color: #888; margin-top: 5px; display: block; }

    /* Alerts */
    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    /* Buttons */
    .form-actions {
        display: flex;
        justify-content: flex-end; /* Desktop: Rata kanan */
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: 0.2s;
        border: none;
    }

    .btn-simpan { background: #1abc9c; color: white; }
    .btn-simpan:hover { background: #16a085; }

    .btn-batal { background: #e0e0e0; color: #333; }
    .btn-batal:hover { background: #ccc; }

    /* ===========================
       MEDIA QUERIES (MOBILE)
       =========================== */
    @media (max-width: 768px) {
        .card-body { padding: 20px; }

        .form-actions {
            flex-direction: column-reverse; /* Tombol Simpan di atas */
            gap: 10px;
        }

        .btn {
            width: 100%; /* Tombol lebar penuh */
            padding: 14px;
        }

        .existing-file-info {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

@endsection