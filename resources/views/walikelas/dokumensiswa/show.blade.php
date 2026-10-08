@extends('layouts.admin')
@section('title', 'Detail Dokumen Siswa')
@section('page_title', 'Detail Dokumen Siswa Wali Kelas')

@section('content')
<div class="card shadow-sm" style="border: none; border-radius: 10px;">
    <div class="card-header" style="background-color:#f8f9fa; border-bottom: 2px solid #e0e0e0;">
        @php
            // fallback: pastikan $dokumen selalu collection agar aman
            $dokumen = $dokumen ?? collect([]);
        @endphp

        <h5>
            {{ $siswa->nama_lengkap }} - {{ $siswa->nis }}
        </h5>
        <p style="margin:5px 0; color:#6c757d;">
            Dokumen Terpenuhi:
            <span style="font-weight:600; color:#28a745;">
                {{ $totalTerpenuhi }}/{{ $totalWajib }}
            </span>
        </p>
    </div>

    <div class="card-body" style="background-color:#ffffff; padding:25px;">
        <table class="table table-bordered" style="width:100%; border-collapse: collapse; text-align:left;">
            <thead style="background:#123B6B; color:white;">
                <tr>
                    <th style="width:45%; padding:12px;">Nama Dokumen</th>
                    <th style="width:25%; padding:12px; text-align:center;">Status</th>
                    <th style="width:30%; padding:12px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($wajib as $jenis_dokumen)
                    @php 
                        $file = $dokumen->firstWhere('jenis_dokumen', $jenis_dokumen); 
                    @endphp

                    <tr style="border-bottom:1px solid #e0e0e0;">
                        <td style="padding:10px 15px;">{{ $jenis_dokumen }}</td>

                        <td style="text-align:center;">
                            @if ($file && $file->file_path)
                                <span class="badge-status bg-success">Sudah Diunggah</span>
                            @else
                                <span class="badge-status bg-danger">Belum Diunggah</span>
                            @endif
                        </td>

                        <td style="text-align:center;">
                            @if ($file && $file->file_path)
                                <a href="{{ asset('storage/'.$file->file_path) }}" 
                                   target="_blank" 
                                   class="btn-aksi lihat">
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

        <div style="margin-top:20px;">
            <a href="{{ route('wali.dokumensiswa') }}" class="btn-kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

{{-- STYLE --}}
<style>
.card {
    background: #fff;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    border-radius: 12px;
}

/* Badge status */
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

/* Tombol aksi */
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
.btn-aksi.lihat {
    background-color: #007bff;
    color: #fff;
}
.btn-aksi.hapus {
    background-color: #e9ecef;
    color: #6c757d;
}
.btn-aksi:hover {
    opacity: 0.9;
}

/* Tombol kembali */
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
.btn-kembali:hover {
    background-color: #5a6268;
}
</style>

@endsection
