@extends('layouts.siswa')
@section('title', 'Dashboard Siswa')
@section('page_title', 'Dashboard Siswa')

@section('content')

@php
    use App\Models\DokumenSiswa;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;

    // === LOGIC DATA (TIDAK BERUBAH) ===
    $nis = Auth::guard('siswa')->user()->nis;
    $siswa = Auth::guard('siswa')->user();

    // 1. Ambil Notifikasi
    $notifikasis = [];
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('notifikasis')) {
            $notifikasis = \App\Models\Notifikasi::where('nis', $nis)
                            ->where('is_read', 0)
                            ->orderBy('created_at', 'desc')
                            ->get();
        }
    } catch (\Throwable $e) { $notifikasis = []; }

    // 2. Data Dokumen & Progress
    $dokumens = DokumenSiswa::where('nis', $nis)->get();
    $targetWajib = 5; // Default jumlah wajib
    $uploaded = $dokumens->whereNotNull('file_path')->count();
    
    // Hitung persentase
    $percent = $targetWajib > 0 ? round(($uploaded / $targetWajib) * 100) : 0;
    if($percent > 100) $percent = 100;
@endphp

<style>
    /* === GLOBAL RESPONSIVE STYLES === */
    body { background-color: #f5f7fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased; }
    
    /* Agar padding tidak menambah lebar elemen */
    * { box-sizing: border-box; }

    /* === HEADER SAMBUTAN === */
    .welcome-card {
        background: linear-gradient(135deg, #17375d 0%, #102a48 100%);
        color: #fff; border-radius: 12px; padding: 25px 30px; margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(23, 55, 93, 0.2); position: relative; overflow: hidden;
    }
    .welcome-card::after {
        content: ''; position: absolute; top: -50%; right: -10%; width: 200px; height: 200px;
        background: rgba(255, 255, 255, 0.05); border-radius: 50%;
    }
    .welcome-card h2 { margin: 0; font-size: 22px; font-weight: 700; }
    .welcome-card p { margin-top: 8px; font-size: 15px; opacity: 0.9; }

    /* === NOTIFIKASI ALERT === */
    .notif-box {
        background-color: #fff8e1; border-left: 5px solid #ffc107;
        color: #856404; border-radius: 8px; padding: 15px 20px;
        margin-bottom: 20px; display: flex; align-items: start; gap: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05); animation: fadeIn 0.5s ease;
    }
    .notif-icon {
        background: #ffc107; color: #fff; width: 35px; height: 35px;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 16px; flex-shrink: 0;
    }
    .notif-content h4 { margin: 0 0 4px 0; font-size: 16px; font-weight: 700; color: #d39e00; }
    .notif-content p { margin: 0; font-size: 14px; line-height: 1.4; color: #666; word-wrap: break-word; }
    
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

    /* === GRID LAYOUT (RESPONSIVE OTOMATIS) === */
    .dashboard-grid {
        display: grid; 
        /* Minmax 240px berarti jika layar < 500px dia otomatis jadi 1 kolom, jika lebar dia jadi banyak kolom */
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); 
        gap: 20px;
    }

    /* === CARD STYLE === */
    .card-box {
        background: #ffffff; border-radius: 12px; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        overflow: hidden; transition: transform 0.2s ease, box-shadow 0.2s ease;
        display: flex; flex-direction: column; height: 100%;
    }
    .card-box:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
    
    .card-header {
        background-color: #17375d; color: white; font-weight: 600;
        font-size: 14px; padding: 12px 15px; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .card-content { 
        padding: 20px; text-align: center; background: #fff; 
        flex: 1; display: flex; flex-direction: column; 
        justify-content: center; align-items: center; 
    }

    /* === KOMPONEN KARTU === */
    .barcode-box { 
        background: #f8f9fa; border: 1px solid #e9ecef; 
        border-radius: 8px; padding: 15px 10px; margin-top: 5px; width: 100%;
        /* Agar barcode panjang tidak merusak layout HP */
        overflow-x: auto; display: flex; justify-content: center;
    }
    
    .progress-bar-wrapper { width: 100%; margin-top: 10px; }
    .progress-bar-bg { width: 100%; height: 8px; background: #e9ecef; border-radius: 20px; overflow: hidden; }
    .progress-bar-fill { height: 100%; background: #0d6efd; border-radius: 20px; transition: width 0.5s ease; }

    .status-circle {
        background: #d4edda; color: #155724; border-radius: 50%; width: 50px; height: 50px;
        display: flex; justify-content: center; align-items: center; font-size: 24px; margin-bottom: 10px;
    }

    .btn-cetak {
        background-color: #17375d; color: white; padding: 12px 25px; border: none;
        border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px;
        transition: background 0.2s ease; width: 100%;
        /* Min height agar mudah dipencet di HP */
        min-height: 44px; display: flex; align-items: center; justify-content: center;
    }
    .btn-cetak:hover { background-color: #0f2740; }
    .btn-cetak:active { transform: scale(0.98); }

    /* === TABEL JADWAL === */
    .jadwal-box {
        background: #fff; border-radius: 12px; overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-top: 25px; border: 1px solid #eee;
    }
    .jadwal-header {
        background-color: #17375d; color: white; font-weight: 600; 
        padding: 15px 20px; font-size: 16px;
        display: flex; justify-content: space-between; align-items: center;
    }
    
    .link-lihat-semua {
        color: #e0f2fe; font-size: 12px; text-decoration: none;
        background: rgba(255,255,255,0.1); padding: 5px 12px;
        border-radius: 20px; transition: 0.3s; white-space: nowrap;
    }
    .link-lihat-semua:hover { background: rgba(255,255,255,0.2); color: #fff; }

    /* Responsive Table Wrapper */
    .table-responsive {
        width: 100%; 
        overflow-x: auto; 
        -webkit-overflow-scrolling: touch; /* Smooth scroll di iOS */
    }
    
    .table-dashboard { 
        width: 100%; border-collapse: collapse; 
        min-width: 600px; /* Memaksa scroll muncul di HP agar tabel tidak gepeng */
    }
    .table-dashboard th, .table-dashboard td { padding: 15px; text-align: left; font-size: 14px; border-bottom: 1px solid #f0f0f0; }
    .table-dashboard th { background: #f8f9fa; color: #17375d; font-weight: 700; white-space: nowrap; }
    .table-dashboard td { color: #555; vertical-align: middle; }
    .table-dashboard tr:last-child td { border-bottom: none; }

    /* Status Badges */
    .badge-sm { 
        padding: 5px 12px; border-radius: 20px; font-size: 11px; 
        font-weight: 600; display: inline-block; white-space: nowrap; 
    }
    .bg-menunggu { background: #fff3cd; color: #856404; }
    .bg-disetujui { background: #d1e7dd; color: #0f5132; }
    .bg-ditolak { background: #f8d7da; color: #721c24; }
    .bg-selesai { background: #cff4fc; color: #055160; }

    /* === MEDIA QUERIES (OPTIMASI HP) === */
    @media (max-width: 768px) {
        .welcome-card { padding: 20px; text-align: center; }
        .welcome-card h2 { font-size: 18px; }
        .welcome-card p { font-size: 13px; }
        
        /* Mengurangi gap antar kartu di HP */
        .dashboard-grid { gap: 15px; }

        /* Notifikasi stack vertikal di HP */
        .notif-box { flex-direction: column; align-items: flex-start; }
        .notif-icon { margin-bottom: 10px; }

        .card-content { padding: 15px; }
        
        /* Font tabel lebih kecil sedikit di HP */
        .table-dashboard th, .table-dashboard td { padding: 12px 15px; font-size: 13px; }
    }
</style>

{{-- === 1. AREA NOTIFIKASI === --}}
@foreach($notifikasis as $notif)
    <div class="notif-box">
        <div class="notif-icon"><i class="fas fa-bell"></i></div>
        
        <div class="notif-content" style="flex:1;">
            <h4>{{ $notif->judul }}</h4>
            <p>{{ $notif->pesan }}</p>

            {{-- Tombol telah dibaca --}}
            <form action="{{ route('siswa.baca-peringatan', $notif->id) }}" method="POST" style="margin-top:10px;">
                @csrf
                <button type="submit" 
                        style="
                            background:#17375d;
                            color:white;
                            border:none;
                            padding:6px 14px;
                            border-radius:6px;
                            font-size:12px;
                            cursor:pointer;
                        ">
                    ✅ Telah Dibaca
                </button>
            </form>
        </div>
    </div>
@endforeach


{{-- === 2. WELCOME CARD === --}}
<div class="welcome-card">
    
    <h2>Selamat Datang, {{ $siswa->nama_lengkap }}</h2>
    <p>{{ $siswa->jurusan }} | {{ $siswa->rombel }}</p>
     @if(Auth::guard('siswa')->check() && strtolower(Auth::guard('siswa')->user()->role) === 'koor_kelas')
        <div style="background:#3279CD; color:#ffff; padding:6px 12px; border-radius:6px; font-size:13px; font-weight:600; display:inline-block; margin-bottom:10px;">
            <i class="fas fa-user-check"></i>
            Anda adalah Koordinator Kelas
        </div>
    @endif
</div>

{{-- === 3. GRID DASHBOARD === --}}
<div class="dashboard-grid">
    
    {{-- Card 1: Barcode --}}
    <div class="card-box">
        <div class="card-header">Barcode NIS</div>
        <div class="card-content">
            <div class="barcode-box">
                @if(class_exists('DNS1D'))
                    {!! DNS1D::getBarcodeHTML($nis, 'C128', 2, 45) !!}
                @else
                    <span style="color:red; font-size:12px;">Library DNS1D Missing</span>
                @endif
            </div>
            <p style="margin-top:10px; font-weight:700; color:#333; letter-spacing:1px; word-break: break-all;">{{ $nis }}</p>
        </div>
    </div>

    {{-- Card 2: Status Dokumen --}}
    <div class="card-box" onclick="window.location='{{ route('siswa.dokumensiswa') }}'" style="cursor:pointer;">
        <div class="card-header">Kelengkapan Data</div>
        <div class="card-content">
            <div class="progress-bar-wrapper">
                <div class="progress-bar-bg">
                    <div class="progress-bar-fill" style="width: {{ $percent }}%;"></div>
                </div>
            </div>
            <h3 style="font-weight:700; color:#0d6efd; margin-top:15px; font-size:24px;">
                {{ $percent }}%
            </h3>
            <small style="color:#666;">{{ $uploaded }} dari {{ $targetWajib }} Dokumen</small>
        </div>
    </div>

    {{-- Card 3: Cetak Kartu --}}
    <div class="card-box">
        <div class="card-header">Kartu Pelajar</div>
        <div class="card-content">
            <button class="btn-cetak" onclick="window.location.href='{{ route('siswa.kartupelajar.index') }}'">
                <i class="fas fa-print" style="margin-right:8px;"></i> Cetak Kartu
            </button>
        </div>
    </div>

    {{-- Card 4: Status Akademik --}}
    <div class="card-box">
        <div class="card-header">Status Akademik</div>
        <div class="card-content">
            <div class="status-circle">
                <i class="fas fa-user-check"></i>
            </div>
            <p style="font-weight:700; color:#155724; font-size:16px; margin:0;">Aktif</p>
            <small style="color:#666; margin-top:5px;">T.A. 2025/2026</small>
        </div>
    </div>
</div>

{{-- === 4. TABEL RIWAYAT KONSELING (RESPONSIVE WRAPPER) === --}}
<div class="jadwal-box">
    <div class="jadwal-header">
        <div>
            <i class="fas fa-history" style="margin-right:8px;"></i> Riwayat Konseling
        </div>
        <a href="{{ route('siswa.konseling.index') }}" class="link-lihat-semua">
            Lihat Semua <i class="fas fa-arrow-right" style="font-size:10px; margin-left:3px;"></i>
        </a>
    </div>
    
    {{-- Wrapper ini penting agar tabel bisa di-scroll di HP --}}
    <div class="table-responsive">
        <table class="table-dashboard">
            <thead>
                <tr>
                    <th width="25%">Waktu</th>
                    <th width="25%">Guru BK</th>
                    <th width="30%">Topik</th>
                    <th width="20%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($konselings->take(5) as $konseling)
                    <tr>
                        <td>
                            <div style="font-weight:600; color:#123B6B;">
                                {{ \Carbon\Carbon::parse($konseling->tanggal)->format('d M Y') }}
                            </div>
                            <div style="font-size:12px; color:#666;">
                                <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($konseling->jam_pengajuan)->format('H:i') }} WIB
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:500;">
                                {{ $konseling->guru->nama ?? '-' }}
                            </div>
                            <div style="font-size:11px; color:#888;">
                                {{ $konseling->jenis_layanan ?? 'Konseling' }}
                            </div>
                        </td>
                        <td>
                            {{ Str::limit($konseling->topik, 30) }}
                        </td>
                        <td>
                            @php
                                $statusClass = 'bg-menunggu';
                                if($konseling->status == 'Disetujui') $statusClass = 'bg-disetujui';
                                elseif($konseling->status == 'Ditolak') $statusClass = 'bg-ditolak';
                                elseif($konseling->status == 'Selesai') $statusClass = 'bg-selesai';
                            @endphp
                            <span class="badge-sm {{ $statusClass }}">
                                {{ ucfirst($konseling->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding: 40px 20px; color:#999;">
                            <div style="background:#f8f9fa; width:60px; height:60px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 15px;">
                                <i class="far fa-calendar-times" style="font-size: 24px; color:#ccc;"></i>
                            </div>
                            <p style="margin:0; font-weight:500;">Belum ada riwayat konseling.</p>
                            <a href="{{ route('siswa.konseling.create') }}" style="font-size:13px; color:#123B6B; font-weight:600; text-decoration:none; margin-top:5px; display:inline-block;">
                                + Ajukan Sekarang
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection