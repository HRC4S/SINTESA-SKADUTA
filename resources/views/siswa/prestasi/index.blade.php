@extends('layouts.siswa')

@section('title', 'Prestasi Siswa')
@section('page_title', 'Prestasi Siswa')

@section('content')

{{-- Import Font & Icons --}}
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    :root {
        --primary: #1e3a8a;
        --primary-hover: #172554;
        --secondary: #64748b;
        --bg-light: #f1f5f9;
        --white: #ffffff;
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --radius: 16px;
    }

    body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); }

    /* ===== LAYOUT ===== */
    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 50px;
    }

    /* ===== HEADER CARD (Desain Baru) ===== */
    .header-card {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        padding: 30px;
        border-radius: var(--radius);
        color: var(--white);
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--shadow);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    /* Dekorasi Background Header */
    .header-card::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 150px; height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .user-info h2 { font-size: 24px; font-weight: 700; margin: 0; }
    .user-info p { font-size: 14px; opacity: 0.9; margin-top: 5px; }
    
    .header-icon i {
        font-size: 60px;
        color: rgba(255,255,255,0.8);
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        transform: rotate(-10deg);
        display: inline-block;
    }

    /* ===== STATISTIK GRID ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--white);
        padding: 25px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: transform 0.2s;
    }

    .stat-card:hover { transform: translateY(-5px); }

    .stat-icon {
        width: 60px; height: 60px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 28px;
    }

    .icon-blue { background: #eff6ff; color: #3b82f6; }
    .icon-yellow { background: #fffbeb; color: #d97706; }

    .stat-text p { margin: 0; font-size: 14px; color: var(--secondary); font-weight: 500; }
    .stat-text h3 { margin: 0; font-size: 28px; font-weight: 700; color: #0f172a; }

    /* ===== CONTENT AREA ===== */
    .content-card {
        background: var(--white);
        border-radius: var(--radius);
        padding: 25px;
        box-shadow: var(--shadow);
    }

    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .content-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-add {
        background: var(--primary);
        color: var(--white);
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s;
        font-size: 14px;
    }
    
    .btn-add:hover { background: var(--primary-hover); }

    /* ===== TABLE STYLE ===== */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .custom-table thead {
        background-color: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }

    .custom-table th {
        text-align: left;
        padding: 16px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .custom-table td {
        padding: 16px;
        vertical-align: middle;
        font-size: 14px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }

    .custom-table tr:hover { background-color: #f8fafc; }

    /* Badges */
    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    .badge-sertifikat { background: #dbeafe; color: #1e40af; }
    .badge-lomba { background: #dcfce7; color: #166534; }
    .badge-seminar { background: #fce7f3; color: #9d174d; }
    .badge-lainnya { background: #f3f4f6; color: #4b5563; }

    /* Action Buttons */
    .action-group { display: flex; gap: 8px; }
    .btn-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        border: none; cursor: pointer; transition: 0.2s;
        text-decoration: none;
    }
    .btn-edit { background: #fff7ed; color: #c2410c; }
    .btn-edit:hover { background: #ffedd5; }
    .btn-delete { background: #fef2f2; color: #b91c1c; }
    .btn-delete:hover { background: #fee2e2; }
    
    .btn-link {
        font-size: 13px;
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .btn-link:hover { text-decoration: underline; }

    /* ===== MODAL ===== */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);
        display: none; justify-content: center; align-items: center; z-index: 9999;
        animation: fadeIn 0.2s ease-out;
    }

    .modal-box {
        background: var(--white);
        width: 90%; max-width: 600px;
        border-radius: 20px;
        padding: 30px;
        position: relative;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        transform: scale(0.95);
        animation: zoomIn 0.2s ease-out forwards;
        max-height: 90vh; overflow-y: auto;
    }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes zoomIn { to { transform: scale(1); } }

    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .modal-title { font-size: 20px; font-weight: 700; color: var(--primary); margin: 0; }
    .btn-close { background: #f1f5f9; border: none; font-size: 20px; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; color: var(--secondary); }
    .btn-close:hover { background: #e2e8f0; }

    /* Form Elements */
    .form-group { margin-bottom: 15px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 8px; }
    .form-control {
        width: 100%; padding: 10px 15px;
        border: 1px solid #cbd5e1; border-radius: 10px;
        font-size: 14px; font-family: inherit;
        transition: 0.2s; box-sizing: border-box;
    }
    .form-control:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1); }

    .upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: 0.2s;
        background: #f8fafc;
    }
    .upload-area:hover { border-color: var(--primary); background: #eff6ff; }
    .upload-text { color: var(--secondary); font-size: 13px; display: block; margin-top: 5px; }

    .btn-submit {
        width: 100%; background: var(--primary); color: white;
        padding: 12px; border: none; border-radius: 12px;
        font-size: 16px; font-weight: 600; cursor: pointer;
        margin-top: 10px; transition: 0.2s;
    }
    .btn-submit:hover { background: var(--primary-hover); }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .header-card { flex-direction: column; text-align: center; gap: 15px; padding: 20px; }
        .user-info h2 { font-size: 20px; }
        .stats-grid { grid-template-columns: 1fr; }
        .content-header { flex-direction: column; align-items: stretch; }
        .btn-add { justify-content: center; }
        .modal-box { padding: 20px; width: 95%; }
    }
</style>

<div class="dashboard-container">

    {{-- 1. HEADER CARD (JUDUL & INFO) --}}
    <div class="header-card">
        <div class="user-info">
            <h2>Portofolio Prestasi</h2>
            <p>
                <i class="bi bi-person-lines-fill" style="margin-right: 5px;"></i> 
                {{ $siswa->nama_lengkap }} | {{ $siswa->rombel }}
            </p>
        </div>
        <div class="header-icon">
            {{-- Ikon Piala (Simbol Prestasi) --}}
            <i class="bi bi-trophy-fill"></i>
        </div>
    </div>

    {{-- 2. STATISTIK --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon icon-blue">
                <i class="bi bi-award-fill"></i>
            </div>
            <div class="stat-text">
                <p>Total Prestasi</p>
                <h3>{{ $totalPrestasi }}</h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon icon-yellow">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div class="stat-text">
                <p>Total Sertifikat</p>
                <h3>{{ $totalSertifikat }}</h3>
            </div>
        </div>
    </div>

    {{-- 3. CONTENT (TABEL) --}}
    <div class="content-card">
        <div class="content-header">
            <div class="content-title">
                <i class="bi bi-clock-history"></i> Riwayat Prestasi
            </div>
            <button onclick="openModal()" class="btn-add">
                <i class="bi bi-plus-lg"></i> Tambah Prestasi
            </button>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Judul Prestasi</th>
                        <th width="15%">Jenis</th>
                        <th width="15%">Tanggal</th>
                        <th width="25%">Keterangan</th>
                        <th width="15%">Bukti</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prestasi as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $p->judul }}</strong>
                            </td>
                            <td>
                                @php
                                    $badge = 'badge-lainnya';
                                    if($p->jenis == 'sertifikat') $badge = 'badge-sertifikat';
                                    if($p->jenis == 'lomba') $badge = 'badge-lomba';
                                    if($p->jenis == 'seminar') $badge = 'badge-seminar';
                                @endphp
                                <span class="badge {{ $badge }}">{{ ucfirst($p->jenis) }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_prestasi)->format('d M Y') }}</td>
                            <td>{{ Str::limit($p->deskripsi, 40) }}</td>
                            <td>
                                @if ($p->link)
                                    <a href="{{ $p->link }}" target="_blank" class="btn-link">
                                        <i class="bi bi-link-45deg"></i> Buka Link
                                    </a>
                                @elseif ($p->file)
                                    <a href="{{ asset('storage/prestasi/'.$p->file) }}" target="_blank" class="btn-link">
                                        <i class="bi bi-file-earmark-pdf"></i> Lihat File
                                    </a>
                                @else
                                    <span style="color:#cbd5e1; font-size:12px;">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('siswa.prestasi.edit', $p->id) }}" class="btn-icon btn-edit" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('siswa.prestasi.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon btn-delete" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                                <i class="bi bi-inbox" style="font-size: 32px; display: block; margin-bottom: 10px;"></i>
                                <p>Belum ada data prestasi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- === MODAL FORM PRESTASI === --}}
<div class="modal-overlay" id="modalPrestasi">
    <div class="modal-box">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Data Prestasi</h3>
            <button class="btn-close" onclick="closeModal()">
                <i class="bi bi-x"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('siswa.prestasi.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Judul Prestasi <span style="color:red">*</span></label>
                <input type="text" name="judul" class="form-control" placeholder="Contoh: Juara 1 Lomba Web Design" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label class="form-label">Jenis Prestasi <span style="color:red">*</span></label>
                    <select name="jenis" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="sertifikat">Sertifikat</option>
                        <option value="seminar">Seminar</option>
                        <option value="lomba">Lomba</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal <span style="color:red">*</span></label>
                    <input type="date" name="tanggal_prestasi" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan / Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Tambahkan detail singkat..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Link Eksternal (Opsional)</label>
                <input type="url" name="link" class="form-control" placeholder="https://...">
            </div>

            <div class="form-group">
                <label class="form-label">Upload Bukti (Foto/PDF)</label>
                <label class="upload-area" for="fileInput">
                    <i class="bi bi-cloud-arrow-up-fill" style="font-size: 24px; color: #64748b;"></i>
                    <span class="upload-text">Klik untuk memilih file</span>
                    <input type="file" id="fileInput" name="file" style="display:none" onchange="previewFile()">
                </label>
                <p id="fileName" style="font-size:12px; margin-top:5px; color:#1e3a8a; font-weight:600; display:none;"></p>
            </div>

            <button type="submit" class="btn-submit">Simpan Data</button>
        </form>
    </div>
</div>

{{-- Script Modal --}}
<script>
    function openModal() {
        document.getElementById('modalPrestasi').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modalPrestasi').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('modalPrestasi');
        if (event.target == modal) {
            closeModal();
        }
    }

    function previewFile() {
        const input = document.getElementById('fileInput');
        const fileName = document.getElementById('fileName');
        if(input.files.length > 0) {
            fileName.innerText = "File terpilih: " + input.files[0].name;
            fileName.style.display = 'block';
        } else {
            fileName.innerText = "";
            fileName.style.display = 'none';
        }
    }
</script>

@endsection