@extends('layouts.siswa')
@section('title', 'Riwayat Konseling')
@section('page_title', 'Riwayat Konseling')

@section('content')

{{-- Load FontAwesome & Google Fonts --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    /* ===========================
       WRAPPER & CARD STYLES
       =========================== */
    .konseling-container {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        overflow: hidden;
        margin-bottom: 30px;
    }

    /* HEADER */
    .header-konseling {
        background: #123B6B;
        color: white;
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header-konseling h4 { margin: 0; font-weight: 600; font-size: 1.2rem; }
    .tanggal-jam { text-align: right; font-size: 0.9rem; opacity: 0.9; line-height: 1.4; }

    /* TOOLBAR (FILTER & ADD) */
    .toolbar-wrapper {
        background: #f8f9fa;
        padding: 15px 30px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: flex-end; /* Tombol di kanan */
        align-items: center;
    }

    .btn-tambah {
        background-color: #123B6B;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
        box-shadow: 0 3px 10px rgba(18, 59, 107, 0.2);
    }
    .btn-tambah:hover { background-color: #0f2e52; transform: translateY(-2px); }

    /* TABLE STYLES */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px; /* Agar tidak gepeng di HP */
    }

    thead { background-color: #2c3e50; color: white; }
    
    th, td {
        padding: 15px 20px;
        text-align: left;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
        vertical-align: middle;
    }

    th { font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; }
    tbody tr:hover { background-color: #f9fbfd; }

    /* STATUS BADGE */
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 90px;
    }
    .badge-menunggu { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .badge-disetujui { background: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
    .badge-ditolak { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .badge-selesai { background: #cce5ff; color: #004085; border: 1px solid #b8daff; }

    /* ACTION BUTTONS */
    .action-group {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        text-decoration: none;
        transition: 0.2s;
        font-size: 13px;
    }

    /* Detail Button Style (Updated to match previous design but icon-based for compactness or text based) */
    .btn-detail {
        background: #eef2ff; 
        color: #123B6B;
    }
    .btn-detail:hover { background: #dbe4ff; color: #0a2240; }

    /* Edit Button Style */
    .btn-edit { 
        background: #fef9c3; 
        color: #ca8a04; 
    }
    .btn-edit:hover { background: #fde047; color: #a16207; }

    /* Disabled Button Style */
    .btn-disabled { 
        background: #f3f4f6; 
        color: #9ca3af; 
        cursor: not-allowed; 
        pointer-events: none;
    }

    /* EMPTY STATE */
    .empty-state { text-align: center; padding: 50px 20px; color: #999; }
    .empty-state i { font-size: 40px; margin-bottom: 15px; display: block; color: #ccc; }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .header-konseling { flex-direction: column; align-items: flex-start; gap: 10px; padding: 20px; }
        .toolbar-wrapper { padding: 15px; }
        .btn-tambah { width: 100%; justify-content: center; }
        .tanggal-jam { text-align: left; }
    }
</style>

{{-- ALERT SUCCESS --}}
@if(session('success'))
    <div style="background:#d1fae5; color:#065f46; padding:15px 25px; border-radius:8px; margin-bottom:20px; border:1px solid #a7f3d0; display:flex; align-items:center; gap:10px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="konseling-container">
    
    {{-- Header --}}
    <div class="header-konseling">
        <div>
            <h4>📋 Daftar Pengajuan Konseling</h4>
            <small style="opacity:0.8;">Kelola jadwal dan riwayat konsultasi Anda</small>
        </div>
        <div class="tanggal-jam" id="tanggalJamSiswa"></div>
    </div>

    {{-- Toolbar --}}
    <div class="toolbar-wrapper">
        <a href="{{ route('siswa.konseling.create') }}" class="btn-tambah">
            <i class="fas fa-plus-circle"></i> Buat Pengajuan Baru
        </a>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Jadwal Request</th>
                    <th width="20%">Guru BK & Layanan</th>
                    <th width="25%">Topik Masalah</th>
                    <th width="15%">Status</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($konselings as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        
                        <td>
                            <div style="font-weight:600; color:#333;">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                            </div>
                            <div style="font-size:12px; color:#666;">
                                <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($item->jam_pengajuan)->format('H:i') }} WIB
                            </div>
                        </td>

                        <td>
                            <div style="font-weight:600; color:#123B6B;">
                                {{ $item->guru->nama ?? 'Guru Tidak Ditemukan' }}
                            </div>
                            <div style="font-size:12px; color:#555; background:#f0f0f0; display:inline-block; padding:2px 8px; border-radius:4px; margin-top:4px;">
                                {{ $item->jenis_layanan ?? '-' }}
                            </div>
                        </td>

                        <td>
                            <strong>{{ Str::limit($item->topik, 40) }}</strong>
                            <div style="font-size:12px; color:#888; margin-top:4px;">
                                Harapan: {{ Str::limit($item->kegiatan_layanan, 30) }}
                            </div>
                        </td>

                        <td>
                            @php
                                $statusClass = 'badge-menunggu';
                                if($item->status == 'Disetujui') $statusClass = 'badge-disetujui';
                                elseif($item->status == 'Ditolak') $statusClass = 'badge-ditolak';
                                elseif($item->status == 'Selesai') $statusClass = 'badge-selesai';
                            @endphp
                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>

                        <td>
                            <div class="action-group">
                                {{-- Tombol Detail --}}
                                <a href="{{ route('siswa.konseling.show', $item->id) }}" class="btn-icon btn-detail" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- Tombol Edit (Hanya jika Menunggu) --}}
                                @if($item->status == 'Menunggu')
                                    <a href="{{ route('siswa.konseling.edit', $item->id) }}" class="btn-icon btn-edit" title="Edit Pengajuan">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                @else
                                    {{-- Jika sudah diproses, tombol edit mati --}}
                                    <a href="#" class="btn-icon btn-disabled" title="Tidak dapat diedit">
                                        <i class="fas fa-lock"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <i class="far fa-folder-open"></i>
                            <p>Belum ada riwayat pengajuan konseling.</p>
                            <a href="{{ route('siswa.konseling.create') }}" style="color:#123B6B; font-weight:600; text-decoration:none;">
                                Ajukan Sekarang
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const tanggal = now.toLocaleDateString('id-ID', options);
        const jam = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second:'2-digit' });
        document.getElementById('tanggalJamSiswa').innerHTML = `${tanggal}<br>${jam} WIB`;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

@endsection