@extends('layouts.siswa')

@section('title', 'Keterlambatan & Perizinan')
@section('page_title', 'Dashboard Keterlambatan')

@section('content')

{{-- Import Font & Icons --}}
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    :root {
        --primary-color: #1e3a8a;
        --primary-hover: #162d6d;
        --secondary-bg: #f8fafc;
        --card-bg: #ffffff;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-radius: 16px;
        --shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    body { font-family: 'Poppins', sans-serif; background-color: #f1f5f9; }

    /* ===== MAIN WRAPPER ===== */
    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 40px;
    }

    /* ===== HEADER CARD (REVISI: Lebih Kompak) ===== */
    .header-card {
        background: linear-gradient(135deg, #1e3a8a, #17375d);
        color: white;
        padding: 20px 25px; /* Padding dikecilkan */
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    /* Hiasan background abstrak */
    .header-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -10%;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .profile-section h2 { font-size: 18px; font-weight: 700; margin: 0; } /* Font Judul Dikecilkan */
    .profile-section p { font-size: 12px; opacity: 0.9; margin: 2px 0 0 0; }
    
    .clock-section { text-align: right; }
    .clock-time { font-size: 20px; font-weight: 700; line-height: 1; } /* Jam Dikecilkan */
    .clock-date { font-size: 11px; opacity: 0.9; margin-top: 3px; }

    /* ===== STATISTIK GRID (REVISI: Ukuran Font) ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: var(--card-bg);
        padding: 20px;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        gap: 15px;
        transition: transform 0.3s ease;
    }

    .stat-card:hover { transform: translateY(-3px); }

    .stat-icon {
        width: 50px; height: 50px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
    }

    .icon-blue { background: #e0f2fe; color: #0284c7; }
    .icon-red { background: #fee2e2; color: #dc2626; }

    .stat-info h3 { font-size: 24px; font-weight: 700; margin: 0; color: var(--text-dark); } /* Angka Dikecilkan */
    .stat-info p { font-size: 12px; color: var(--text-muted); margin: 0; font-weight: 500; }

    /* ===== CONTENT CARD (TABEL) ===== */
    .content-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .content-header {
        padding: 15px 25px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .content-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-action {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: 0.3s;
        box-shadow: 0 4px 10px rgba(30, 58, 138, 0.2);
    }

    .btn-action:hover { background: var(--primary-hover); transform: translateY(-2px); }

    /* ===== TABEL ===== */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    table { width: 100%; border-collapse: collapse; min-width: 800px; }
    
    thead { background: #f8fafc; }
    
    th {
        text-align: left;
        padding: 14px 20px;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        padding: 14px 20px;
        font-size: 13px;
        color: var(--text-dark);
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    tr:last-child td { border-bottom: none; }
    tbody tr:hover { background-color: #f8fafc; }

    /* Status Badges */
    .badge {
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-pending { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .badge-proses { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }
    .badge-sukses { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
    .badge-tolak  { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; } /* Tambahan untuk status ditolak */

    .badge-jenis { padding: 4px 10px; border-radius: 12px; font-size: 10.5px; font-weight: 700; display: inline-block; text-transform: uppercase; letter-spacing: 0.3px; }
    .badge-jenis-masuk { background: #dbeafe; color: #1e40af; }
    .badge-jenis-keluar { background: #fde9d0; color: #92400e; }

    .warning-banner {
        background: linear-gradient(135deg, #fee2e2, #fef2f2);
        border: 1px solid #fecaca; color: #991b1b;
        padding: 16px 20px; border-radius: 14px; margin-bottom: 20px;
        display: flex; align-items: center; gap: 14px;
    }
    .warning-banner .icon { font-size: 26px; flex: 0 0 auto; }
    .warning-banner strong { display: block; font-size: 14px; }
    .warning-banner span { font-size: 12.5px; opacity: 0.9; }

    .btn-group-ajukan { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn-action.outline { background: #fff; color: var(--primary-color); border: 2px solid var(--primary-color); box-shadow: none; }
    .btn-action.outline:hover { background: #eff6ff; }
    
    /* Tombol Cetak & Lihat Bukti */
    .btn-xs {
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 4px;
        transition: 0.2s;
    }
    .btn-print { background: #15803d; color: white; }
    .btn-print:hover { background: #14532d; }
    
    .btn-view { background: #e0f2fe; color: #0284c7; }
    .btn-view:hover { background: #bae6fd; color: #0369a1; }

    /* ===== MODAL ===== */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);
        display: none; justify-content: center; align-items: center; z-index: 9999;
        opacity: 0; transition: opacity 0.3s ease;
    }
    .modal-overlay.show { opacity: 1; }

    .modal-box {
        background: white;
        width: 90%; max-width: 600px;
        border-radius: 16px;
        padding: 25px;
        position: relative;
        transform: scale(0.95); transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        max-height: 90vh; overflow-y: auto;
    }
    .modal-overlay.show .modal-box { transform: scale(1); }

    .modal-header h3 { margin: 0 0 20px 0; color: var(--primary-color); font-size: 18px; }
    .btn-close {
        position: absolute; top: 20px; right: 20px;
        background: #f1f5f9; border: none; width: 32px; height: 32px;
        border-radius: 50%; font-size: 18px; cursor: pointer; color: var(--text-muted);
        display: flex; align-items: center; justify-content: center;
        transition: 0.2s;
    }
    .btn-close:hover { background: #e2e8f0; color: var(--text-dark); }

    /* Form Styles */
    .form-group { margin-bottom: 15px; }
    .form-label { display: block; font-weight: 600; font-size: 12px; color: var(--text-dark); margin-bottom: 6px; }
    .form-control {
        width: 100%; padding: 10px 12px;
        border: 1px solid #e2e8f0; border-radius: 8px;
        font-family: inherit; font-size: 13px;
        transition: 0.2s; background: #f8fafc;
        box-sizing: border-box;
    }
    .form-control:focus { border-color: var(--primary-color); background: white; outline: none; box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1); }
    
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

    /* Upload Box */
    .upload-box {
        border: 2px dashed #cbd5e1; border-radius: 10px;
        padding: 15px; text-align: center; cursor: pointer;
        transition: 0.2s; color: var(--text-muted); font-size: 12px;
    }
    .upload-box:hover { border-color: var(--primary-color); background: #eff6ff; color: var(--primary-color); }

    .btn-submit {
        width: 100%; background: var(--primary-color); color: white;
        padding: 12px; border: none; border-radius: 10px;
        font-weight: 600; font-size: 14px; margin-top: 10px;
        cursor: pointer; transition: 0.2s;
    }
    .btn-submit:hover { background: var(--primary-hover); }

    /* ===== MOBILE RESPONSIVE ===== */
    @media (max-width: 768px) {
        .header-card { flex-direction: column; align-items: flex-start; gap: 10px; padding: 20px; }
        .clock-section { text-align: left; }
        .form-grid { grid-template-columns: 1fr; gap: 0; }
        .content-header { flex-direction: column; align-items: stretch; }
        .btn-action { justify-content: center; }
        .btn-group-ajukan { flex-direction: column; width: 100%; }
        .warning-banner { flex-direction: column; text-align: center; }
        .modal-box { padding: 20px; width: 95%; }
    }
</style>

@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp

<div class="dashboard-container">

    {{-- ========================================================= --}}
    {{-- === 1. ALERT ERROR VALIDASI (KODE YANG DISISIPKAN) === --}}
    {{-- ========================================================= --}}
    @if ($errors->any())
        <div style="background: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #fca5a5;">
            <div style="font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-exclamation-circle-fill"></i> Gagal Menyimpan!
            </div>
            <ul style="margin: 5px 0 0 20px; font-size: 13px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- ========================================================= --}}

    @if ($tampilkanPeringatan)
        <div class="warning-banner">
            <div class="icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <strong>Perhatian! Kamu sudah terlambat {{ $terlambatBulanIni }}x bulan ini.</strong>
                <span>Terlalu sering terlambat bisa berpengaruh ke poin pelanggaran. Yuk, coba datang lebih pagi ya.</span>
            </div>
        </div>
    @endif

    {{-- === 2. HEADER KARTU (JUDUL BARU) === --}}
    <div class="header-card">
        <div class="profile-section">
            <h2>Portal Perizinan</h2> <p>Kelola izin dan pantau status kehadiran Anda.</p>
        </div>
        <div class="clock-section">
            <div class="clock-time" id="digitalClock">00:00:00</div>
            <div class="clock-date" id="dateString">...</div>
        </div>
    </div>

    {{-- === 3. STATISTIK KETERLAMBATAN (UKURAN DIPERKECIL) === --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon icon-blue">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $jumlah }}</h3>
                <p>Total Terlambat</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-red">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $poin }}</h3>
                <p>Poin Pelanggaran</p>
            </div>
        </div>
    </div>

    {{-- === 4. TABEL RIWAYAT === --}}
    <div class="content-card">
        <div class="content-header">
            <div class="content-title">
                <i class="bi bi-clock-history"></i> Riwayat Pengajuan SIT
            </div>
            
            <div class="btn-group-ajukan">
                <button onclick="openModal('modalMasuk')" class="btn-action">
                    <i class="bi bi-door-open-fill"></i> Izin Masuk Kelas
                </button>
                <button onclick="openModal('modalKeluar')" class="btn-action outline">
                    <i class="bi bi-box-arrow-right"></i> Izin Keluar Kelas
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="13%">Tanggal</th>
                        <th width="12%">Jenis</th>
                        <th width="8%">Jam Ke</th>
                        <th width="30%">Keterangan</th>
                        <th width="12%">Bukti</th> <th width="25%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $data)
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: #334155;">{{ Carbon::parse($data->tanggal)->translatedFormat('d M Y') }}</div>
                        </td>
                        <td>
                            <span class="badge-jenis {{ $data->jenis == 'keluar' ? 'badge-jenis-keluar' : 'badge-jenis-masuk' }}">
                                {{ $data->jenis == 'keluar' ? 'Keluar' : 'Masuk' }}
                            </span>
                        </td>
                        <td>{{ $data->jam_ke ?: '-' }}</td>
                        <td>{{ Str::limit($data->keterangan, 40) }}</td>
                        
                        {{-- LOGIKA KOLOM BUKTI --}}
                        <td>
                            @if($data->dokumen)
                                {{-- Asumsi 'dokumen' menyimpan path file. Sesuaikan path 'storage/' dengan config Anda --}}
                                <a href="{{ asset('uploads/dokumen_izin/' . $data->dokumen) }}" target="_blank" class="btn-xs btn-view">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                            @else
                                <span style="color:#cbd5e1;">-</span>
                            @endif
                        </td>

                        <td>
                            {{-- Jika Status DITERIMA --}}
                            @if ($data->status == 'terima' || $data->status == 'Disetujui')
                                <a href="{{ route('siswa.cetak.sit', $data->id) }}" class="btn-xs btn-print" target="_blank">
                                    <i class="bi bi-printer-fill"></i> Cetak SIT
                                </a>

                            {{-- Jika Status DITOLAK --}}
                            @elseif ($data->status == 'tolak' || $data->status == 'Ditolak')
                                <span class="badge badge-tolak">
                                    <i class="bi bi-x-circle"></i> Ditolak
                                </span>

                            {{-- Jika Status MENUNGGU / DIPROSES --}}
                            @else
                                {{-- Cek apakah Menunggu atau Diproses --}}
                                @php
                                    // Logika warna badge
                                    $badgeClass = 'badge-pending'; // Default kuning
                                    
                                    if($data->status == 'diproses' || $data->status == 'Diproses') {
                                        $badgeClass = 'badge-proses'; // Biru
                                    }
                                @endphp

                                <span class="badge {{ $badgeClass }}">
                                    @if($data->status == 'Menunggu' || $data->status == 'pending') 
                                        <i class="bi bi-hourglass-split"></i>
                                    @else 
                                        <i class="bi bi-arrow-repeat"></i>
                                    @endif
                                    
                                    {{ ucfirst($data->status) }}
                                </span>
                            @endif
                        </td>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">
                            <i class="bi bi-inbox" style="font-size: 32px; display: block; margin-bottom: 10px;"></i>
                            Belum ada riwayat pengajuan izin.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- === MODAL IZIN MASUK KELAS === --}}
<div class="modal-overlay" id="modalMasuk">
    <div class="modal-box">
        <button class="btn-close" onclick="closeModal('modalMasuk')">
            <i class="bi bi-x-lg"></i>
        </button>

        <div class="modal-header">
            <h3>Permohonan Mengikuti Pelajaran (Izin Masuk Kelas)</h3>
        </div>

        <form method="POST" action="{{ route('siswa.keterlambatan.ajukan') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis" value="masuk">

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Siswa</label>
                    <input type="text" class="form-control" value="{{ $siswa->nama_lengkap }}" readonly
                           style="background: #e2e8f0; cursor: not-allowed;">
                </div>
                <div class="form-group">
                    <label class="form-label">Kelas</label>
                    <input type="text" class="form-control" value="{{ $siswa->rombel }}" readonly
                           style="background: #e2e8f0; cursor: not-allowed;">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Tanggal <span style="color:red">*</span></label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">No. Absen <span style="color:red">*</span></label>
                    <input type="number" name="no_absen" class="form-control" placeholder="Contoh: 12" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Mulai Mengikuti Pelajaran Jam Ke <span style="color:red">*</span></label>
                <input type="number" name="jam_ke" class="form-control" placeholder="Contoh: 3 (s/d selesai)" required>
            </div>

            <div class="form-group">
                <label class="form-label">Karena <span style="color:red">*</span></label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Jelaskan alasan kamu telat masuk kelas..." required></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Dokumen Pendukung (Foto/Surat)</label>
                <label class="upload-box">
                    <i class="bi bi-cloud-arrow-up" style="font-size: 20px;"></i><br>
                    <span>Klik untuk unggah file (Opsional)</span>
                    <input type="file" name="dokumen" hidden onchange="previewFile(this, 'fileNameMasuk')">
                </label>
                <small id="fileNameMasuk" style="display:block; margin-top:5px; color:#15803d; font-size:12px;"></small>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-send-fill"></i> Kirim Pengajuan
            </button>
        </form>
    </div>
</div>

{{-- === MODAL IZIN KELUAR KELAS === --}}
<div class="modal-overlay" id="modalKeluar">
    <div class="modal-box">
        <button class="btn-close" onclick="closeModal('modalKeluar')">
            <i class="bi bi-x-lg"></i>
        </button>

        <div class="modal-header">
            <h3>Permohonan Meninggalkan Pelajaran (Izin Keluar Kelas)</h3>
        </div>

        <form method="POST" action="{{ route('siswa.keterlambatan.ajukan') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis" value="keluar">

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Nama Siswa</label>
                    <input type="text" class="form-control" value="{{ $siswa->nama_lengkap }}" readonly
                           style="background: #e2e8f0; cursor: not-allowed;">
                </div>
                <div class="form-group">
                    <label class="form-label">Kelas</label>
                    <input type="text" class="form-control" value="{{ $siswa->rombel }}" readonly
                           style="background: #e2e8f0; cursor: not-allowed;">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Tanggal <span style="color:red">*</span></label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">No. Absen <span style="color:red">*</span></label>
                    <input type="number" name="no_absen" class="form-control" placeholder="Contoh: 12" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Jam Ke <span style="color:red">*</span></label>
                <input type="number" name="jam_ke" class="form-control" placeholder="Contoh: 5" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kepentingan <span style="color:red">*</span></label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Jelaskan keperluan kamu meninggalkan pelajaran..." required></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Dokumen Pendukung (Foto/Surat)</label>
                <label class="upload-box">
                    <i class="bi bi-cloud-arrow-up" style="font-size: 20px;"></i><br>
                    <span>Klik untuk unggah file (Opsional)</span>
                    <input type="file" name="dokumen" hidden onchange="previewFile(this, 'fileNameKeluar')">
                </label>
                <small id="fileNameKeluar" style="display:block; margin-top:5px; color:#15803d; font-size:12px;"></small>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-send-fill"></i> Kirim Pengajuan
            </button>
        </form>
    </div>
</div>

{{-- === SCRIPT === --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. Clock Script
    function updateClock() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        
        document.getElementById('dateString').innerText = now.toLocaleDateString('id-ID', options);
        document.getElementById('digitalClock').innerText = now.toLocaleTimeString('id-ID', { 
            hour: '2-digit', minute: '2-digit', second: '2-digit' 
        }).replace(/\./g, ':');
    }
    setInterval(updateClock, 1000);
    updateClock();

    // 2. Modal Logic
    function openModal(id) {
        const modal = document.getElementById(id);
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('show'), 10);
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('show');
        setTimeout(() => modal.style.display = 'none', 300);
    }

    window.onclick = function(event) {
        document.querySelectorAll('.modal-overlay').forEach(function (modal) {
            if (event.target == modal) closeModal(modal.id);
        });
    }

    // 3. File Preview
    function previewFile(input, targetId) {
        if (input.files && input.files[0]) {
            document.getElementById(targetId).innerText = 'File terpilih: ' + input.files[0].name;
        }
    }

    // 4. SweetAlert
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#1e3a8a',
            timer: 3000
        });
    @endif
</script>

@endsection