@extends('layouts.admin')
@section('title', 'Detail Dokumen Siswa')
@section('page_title', 'Detail Dokumen Siswa')

@section('content')

<style>
    /* =============================================
       1. GAYA ASLI (DIPINDAHKAN DARI INLINE STYLE)
       ============================================= */
    .card-custom {
        border: none; 
        border-radius: 10px;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05); /* Sedikit shadow agar tidak flat */
    }

    .card-header-custom {
        background-color: #f8f9fa; 
        border-bottom: 2px solid #e0e0e0;
        padding: 20px 25px; /* Padding asli */
        border-radius: 10px 10px 0 0;
    }

    .card-body-custom {
        background-color: #ffffff; 
        padding: 25px; /* Padding asli */
        border-radius: 0 0 10px 10px;
    }

    /* Tabel Style Asli */
    .table-custom {
        width: 100%; 
        border-collapse: collapse; 
        text-align: left;
    }

    .thead-custom {
        background: #123B6B; 
        color: white;
    }

    .thead-custom th {
        padding: 12px;
    }

    .table-custom td {
        padding: 10px 15px;
        border-bottom: 1px solid #e0e0e0;
        vertical-align: middle;
    }

    /* Badge & Button Style Asli */
    .badge-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: white;
    }
    .bg-success { background-color: #28a745; }
    .bg-danger { background-color: #dc3545; }

    .btn-aksi {
        border: none;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-aksi.lihat { background-color: #007bff; color: #fff; }
    .btn-aksi.hapus { background-color: #e9ecef; color: #6c757d; }
    .btn-aksi:hover { opacity: 0.9; }

    .btn-kembali {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: #6c757d;
        color: white;
        padding: 8px 14px;
        border-radius: 8px;
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-kembali:hover { background-color: #5a6268; }

    /* =============================================
       2. TAMBAHAN RESPONSIF (MOBILE)
       ============================================= */
    
    /* Wrapper agar tabel bisa di-scroll di HP */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Di HP, tabel diberi min-width agar tidak gepeng */
    @media (max-width: 768px) {
        .table-custom {
            min-width: 600px; /* Paksa lebar minimal agar kolom rapi */
        }
        
        .card-header-custom {
            padding: 15px; /* Padding dikecilkan sedikit di HP */
        }
        
        .card-body-custom {
            padding: 15px;
        }

        .btn-kembali {
            width: 100%; /* Tombol kembali jadi lebar penuh */
            justify-content: center;
            margin-top: 10px;
        }
    }
</style>

<div class="card shadow-sm card-custom">
    
    {{-- HEADER --}}
    <div class="card-header-custom">
        @php
            $dokumen = $dokumen ?? collect([]);
        @endphp

        <h5 style="margin:0; font-weight:600;">
            {{ $siswa->nama_lengkap }} - {{ $siswa->nis }}
        </h5>
        <p style="margin:5px 0; color:#6c757d;">
            Dokumen Terpenuhi:
            <span style="font-weight:600; color:#28a745;">
                {{ $totalTerpenuhi }}/{{ $totalWajib }}
            </span>
        </p>
    </div>

    {{-- BODY --}}
    <div class="card-body-custom">
        
        {{-- WRAPPER RESPONSIF (Agar bisa scroll samping di HP) --}}
        <div class="table-responsive">
            <table class="table-custom">
                <thead class="thead-custom">
                    <tr>
                        <th style="width:45%;">Nama Dokumen</th>
                        <th style="width:25%; text-align:center;">Status</th>
                        <th style="width:30%; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($wajib as $jenis_dokumen)
                        @php $file = $dokumen->firstWhere('jenis_dokumen', $jenis_dokumen); @endphp
                        <tr>
                            <td>{{ $jenis_dokumen }}</td>
                            <td style="text-align:center;">
                                @if ($file && $file->file_path)
                                    <span class="badge-status bg-success">Sudah Diunggah</span>
                                @else
                                    <span class="badge-status bg-danger">Belum Diunggah</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                @if ($file && $file->file_path)
                                    <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank" class="btn-aksi lihat">
                                        <i class="fas fa-eye"></i> Lihat File
                                    </a>
                                @else
                                    <button class="btn-aksi hapus" disabled>
                                        <i class="fas fa-times-circle"></i> Belum Ada
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px;">
            <a href="{{ route('admin.dokumensiswa.index') }}" class="btn-kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

@endsection