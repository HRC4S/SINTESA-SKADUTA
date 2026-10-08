@extends('layouts.admin')

@section('title', 'Dokumen Siswa')
@section('page_title', 'Dokumen Siswa — Wali Kelas')

@section('content')
{{-- Notifikasi --}}
@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

{{-- Filter Section --}}
<div class="card filter-card">
    <form method="GET" action="{{ route('wali.dokumensiswa') }}" class="filter-flex">
        <div class="filter-inputs">
            {{-- Filter Jenis Dokumen --}}
            <div class="filter-group">
                <label for="dokumen">Jenis Dokumen</label>
                <select name="dokumen" id="dokumen" class="form-control">
                    <option value="">Semua Dokumen</option>
                    <option value="kartu_keluarga" {{ request('dokumen') == 'kartu_keluarga' ? 'selected' : '' }}>Kartu Keluarga</option>
                    <option value="akta_kelahiran" {{ request('dokumen') == 'akta_kelahiran' ? 'selected' : '' }}>Akta Kelahiran</option>
                    <option value="KPSPKH" {{ request('dokumen') == 'KPSPKH' ? 'selected' : '' }}>KPSPKH</option>
                    <option value="KIP" {{ request('dokumen') == 'KIP' ? 'selected' : '' }}>KIP</option>
                    <option value="pas_foto" {{ request('dokumen') == 'pas_foto' ? 'selected' : '' }}>Pas Foto</option>
                </select>
            </div>

            {{-- Pencarian --}}
            <div class="filter-group">
                <label for="search">Cari Siswa</label>
                <div class="search-wrapper">
                    <input type="text" name="search" id="search" placeholder="NIS atau Nama..." value="{{ request('search') }}">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
        
        @if(request()->filled('dokumen') || request()->filled('search'))
            <div class="filter-actions">
                <a href="{{ route('wali.dokumensiswa') }}" class="btn-reset">Reset</a>
            </div>
        @endif
    </form>
</div>

{{-- Table Section --}}
<div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;" class="text-center">No</th>
                    <th style="width: 120px;" class="text-center">NIS</th>
                    <th class="text-left">Nama Lengkap</th> <th class="text-center">Total Dokumen</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswa as $i => $s)
                <tr>
                    <td class="text-center">{{ $i + $siswa->firstItem() }}</td>
                    <td class="text-center col-nis">{{ $s->nis }}</td>
                    <td class="text-left col-nama">{{ $s->nama_lengkap }}</td> <td class="text-center">
                        <span class="badge-count {{ ($s->dokumen_uploaded_count ?? 0) >= ($totalDokumenWajib ?? 5) ? 'complete' : '' }}">
                            {{ $s->dokumen_uploaded_count ?? 0 }} / {{ $totalDokumenWajib ?? 5 }}
                        </span>
                    </td>
                <td data-label="Aksi">
                    <div class="aksi-container">
                        <a href="{{ route('wali.dokumensiswa.show', $s->nis) }}" class="btn-icon btn-view" title="Lihat">
                            <i class="fas fa-eye"></i>
                        </a> 
                        <a href="{{ route('wali.dokumensiswa.edit', $s->nis) }}" class="btn-icon btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a> 
                        <form action="{{ route('wali.dokumensiswa.destroy', $s->nis) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus dokumen siswa ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form> 
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-empty">Tidak ada data siswa dalam rombel Anda.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="pagination-container">
    {{ $siswa->appends(request()->query())->links('pagination::simple-tailwind') }}
</div>

<style>
    /* --- General Design --- */
    :root {
        --primary: #2c3e50;
        --accent: #3498db;
        --success: #27ae60;
        --warning: #f39c12;
        --danger: #e74c3c;
        --light-bg: #f8f9fa;
        --shadow: 0 4px 6px rgba(0,0,0,0.07);
    }

    .alert {
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alert-success { background: #d4edda; color: #155724; border-left: 5px solid var(--success); }
    .alert-danger { background: #f8d7da; color: #721c24; border-left: 5px solid var(--danger); }

    /* --- Filter Card --- */
    .filter-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: var(--shadow);
        margin-bottom: 25px;
    }
    .filter-flex {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 15px;
    }
    .filter-inputs {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        flex-grow: 1;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 220px;
    }
    .filter-group label {
        font-weight: 700;
        font-size: 0.9rem;
        color: #555;
    }

    /* --- Inputs --- */
    .form-control, .search-wrapper input {
        background: var(--light-bg);
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 14px;
        transition: 0.3s;
        width: 100%;
    }
    .form-control:focus, .search-wrapper input:focus {
        border-color: var(--accent);
        outline: none;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }
    .search-wrapper {
        position: relative;
        display: flex;
    }
    .search-wrapper button {
        position: absolute;
        right: 5px;
        top: 5px;
        bottom: 5px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0 12px;
        cursor: pointer;
    }

    .btn-reset {
        color: #888;
        text-decoration: underline;
        font-size: 0.9rem;
        margin-bottom: 10px;
        display: inline-block;
    }

    /* --- Table Design --- */
    .table-responsive {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }
    .custom-table th {
        background: var(--primary);
        color: white;
        padding: 15px;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .custom-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        color: #444;
        font-size: 14px;
    }
    .custom-table tbody tr:hover { background: #fcfcfc; }

    /* --- Badges & Buttons --- */
    .badge-count {
        background: #eee;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.8rem;
    }
    .badge-count.complete {
        background: #d4edda;
        color: var(--success);
    }
    .aksi-container {
        display: flex;
        justify-content: center;
        gap: 8px;
    }
    .btn-icon {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: 0.2s;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    .btn-view { background: #e3f2fd; color: #1976d2; }
    .btn-edit { background: #fff3e0; color: #f57c00; }
    .btn-delete { background: #ffebee; color: #d32f2f; }
    .btn-icon:hover { transform: translateY(-2px); filter: brightness(0.9); }

    .text-center { text-align: center; }
    .text-empty { text-align: center; padding: 40px !important; color: #999; font-style: italic; }

    /* Memastikan judul kolom dan isi sel sejajar */
    .text-left {
        text-align: left !important;
        padding-left: 20px !important;
    }

    .text-center {
        text-align: center !important;
    }

    /* Styling Spesifik NIS agar lebih menonjol */
    .col-nis {
        font-weight: 700;
        font-size: 15px !important;
        color: #2c3e50;
        letter-spacing: 0.5px;
    }

    /* Styling Nama Lengkap agar lebih profesional */
    .col-nama {
        font-weight: 600;
        color: #1e293b;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    /* Penyesuaian Table Header */
    .custom-table th {
        background: #2c3e50;
        color: white;
        padding: 18px 15px; /* Sedikit lebih tebal */
        font-weight: 600;
        font-size: 13px;
    }

    /* Memberi jarak yang konsisten pada baris */
    .custom-table td {
        padding: 16px 15px;
        vertical-align: middle;
    }

    /* Perbaikan Badge Count */
    .badge-count {
        display: inline-block;
        min-width: 60px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        padding: 6px 12px;
        border-radius: 6px; /* Gunakan rounded square agar lebih modern */
    }

    /* --- Responsive Mobile (Stack View) --- */
    @media (max-width: 768px) {
        .filter-inputs { flex-direction: column; }
        .filter-group { min-width: 100%; }
        
        .custom-table thead { display: none; }
        .custom-table tr {
            display: block;
            border: 1px solid #eee;
            margin-bottom: 15px;
            border-radius: 10px;
            padding: 10px;
        }
        .custom-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f9f9f9;
            text-align: right;
            padding: 10px 5px;
        }
        .custom-table td::before {
            content: attr(data-label);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: #888;
            float: left;
        }
        .custom-table td:last-child { border-bottom: none; }
        .aksi-container { justify-content: flex-end; }
    }

    .pagination-container {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }
</style>

<script>
    // Auto submit saat ganti dokumen
    document.getElementById('dokumen').addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endsection