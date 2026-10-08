@extends('layouts.admin')

@section('title', 'Manajemen Kartu Pelajar')
@section('page_title', 'Manajemen Kartu Pelajar')

@section('content')
<style>
    /* Base Styles */
    .kartu-wrap { padding: 18px; }
    
    .search-box {
        background:#1e3a67; 
        padding:18px; 
        border-radius:8px; 
        color:#fff;
        display:flex; 
        gap:12px; 
        align-items:center;
    }

    .search-input { 
        flex:1; 
        display:flex; 
        gap:8px; 
        align-items:center; 
    }

    /* Style untuk Label Filter */
    .filter-label {
        font-weight: 600;
        font-size: 0.95rem;
        white-space: nowrap; /* Agar teks tidak turun baris di desktop */
        margin-right: 5px;
    }

    /* Penyesuaian responsif untuk search box */
    @media (max-width: 768px) {
        .search-box {
            flex-direction: column;
            align-items: stretch;
            padding: 15px;
        }
        
        .search-input {
            flex-direction: column;
            width: 100%;
            align-items: flex-start; /* Rata kiri di mobile */
        }
        
        #form-search {
            flex-direction: column;
            width: 100% !important;
            gap: 10px !important;
        }
        
        .search-input input, 
        .search-input select,
        .search-input button {
            width: 100% !important;
        }

        .filter-label {
            margin-bottom: 2px; /* Jarak antara label dan kotak di HP */
        }
    }

    .search-input input,
    .search-input select {
        width:100%; 
        padding:10px 12px; 
        border-radius:6px; 
        border:none; 
        outline:none;
        color: #333;
    }

    /* Warna Optgroup */
    optgroup {
        color: #1e3a67;
        font-weight: 700;
        background-color: #f0f4f8;
    }
    option {
        color: #333;
        background-color: #fff;
    }

    .btn-cetak-mass {
        background-color: #17375d;
        color: white;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        transition: 0.2s ease-in-out;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .btn-cetak-mass:hover { background-color:#0069d9; }

    .card-table { 
        background:#f5f7fa; 
        border-radius:8px; 
        padding:18px; 
        margin-top:18px; 
        overflow-x: auto; 
    }

    .table-header {
        padding:12px; border-radius:6px;
        display:flex; justify-content:space-between; align-items:center;
        box-shadow:0 2px 6px rgba(0,0,0,0.04);
        margin-bottom: 15px; 
        flex-wrap: wrap; 
        gap: 10px;
    }

    .table-list { width:100%; border-collapse:collapse; margin-top:12px; min-width: 600px; }
    
    .table-list th, .table-list td { padding:10px 12px; text-align:left; }
    
    .table-list th {
        font-weight:700; font-size:0.95rem; color:#fff; background:#2c3e50;
        white-space: nowrap; 
    }
    
    .table-list tr td { border-bottom:1px solid rgba(0,0,0,0.05); }
    
    .btn-cetak {
        background:#1e3a67; color:#fff; padding:6px 10px; border-radius:20px;
        text-decoration:none; font-size:0.85rem; white-space: nowrap;
    }
    
    .checkbox-cell { text-align:center; }

    /* Footer aksi massal responsif */
    .mass-action-footer {
        margin-top:15px; 
        display:flex; 
        justify-content:space-between; 
        align-items:center;
        flex-wrap: wrap;
        gap: 10px;
    }

    @media (max-width: 576px) {
        .mass-action-footer {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }
        
        .btn-cetak-mass {
            justify-content: center;
            width: 100%;
        }
    }

    /* Pagination Styles */
    nav[role="navigation"] > div:first-child { display:none !important; }
    nav[role="navigation"] {
        display:flex; justify-content:center; align-items:center; gap:8px; margin-top:15px;
    }
    .pagination .page-item:not(.disabled):not(.active) { display:none !important; }
    .pagination .page-item.active { display:none !important; }
    .pagination .page-item .page-link {
        padding:8px 18px; border-radius:30px; background:#17375d; color:white; font-weight:600; border:none;
    }
    .pagination .page-item .page-link:hover { background:#0069d9; color:white; }
    nav[role="navigation"] svg { width:16px!important; height:16px!important; }
</style>

<div class="kartu-wrap">
    <div class="search-box">
        <div class="search-input">
            
            <form id="form-search" action="{{ route('admin.kartupelajar.index') }}" method="GET" 
                  style="width:100%; display:flex; gap:8px; align-items: center;">

                <label for="rombel-select" class="filter-label">Pilih Rombel:</label>

                <select name="rombel" id="rombel-select">
                    <option value="">-- Semua Kelas --</option>

                    <optgroup label="Kelas X">
                        <option value="X DKV 1" {{ request('rombel') == 'X DKV 1' ? 'selected' : '' }}>X DKV 1</option>
                        <option value="X DKV 2" {{ request('rombel') == 'X DKV 2' ? 'selected' : '' }}>X DKV 2</option>
                        <option value="X DPIB 1" {{ request('rombel') == 'X DPIB 1' ? 'selected' : '' }}>X DPIB 1</option>
                        <option value="X DPIB 2" {{ request('rombel') == 'X DPIB 2' ? 'selected' : '' }}>X DPIB 2</option>
                        <option value="X DPIB 3" {{ request('rombel') == 'X DPIB 3' ? 'selected' : '' }}>X DPIB 3</option>
                        <option value="X GEOMATIKA" {{ request('rombel') == 'X GEOMATIKA' ? 'selected' : '' }}>X GEOMATIKA</option>
                        <option value="X KGS" {{ request('rombel') == 'X KGS' ? 'selected' : '' }}>X KGS</option>
                        <option value="X MEKATRONIKA" {{ request('rombel') == 'X MEKATRONIKA' ? 'selected' : '' }}>X MEKATRONIKA</option>
                        <option value="X SIJA 1" {{ request('rombel') == 'X SIJA 1' ? 'selected' : '' }}>X SIJA 1</option>
                        <option value="X SIJA 2" {{ request('rombel') == 'X SIJA 2' ? 'selected' : '' }}>X SIJA 2</option>
                        <option value="X TAV" {{ request('rombel') == 'X TAV' ? 'selected' : '' }}>X TAV</option>
                        <option value="X TITL 1" {{ request('rombel') == 'X TITL 1' ? 'selected' : '' }}>X TITL 1</option>
                        <option value="X TITL 2" {{ request('rombel') == 'X TITL 2' ? 'selected' : '' }}>X TITL 2</option>
                        <option value="X TITL 3" {{ request('rombel') == 'X TITL 3' ? 'selected' : '' }}>X TITL 3</option>
                        <option value="X TITL 4" {{ request('rombel') == 'X TITL 4' ? 'selected' : '' }}>X TITL 4</option>
                        <option value="X TKR 1" {{ request('rombel') == 'X TKR 1' ? 'selected' : '' }}>X TKR 1</option>
                        <option value="X TKR 2" {{ request('rombel') == 'X TKR 2' ? 'selected' : '' }}>X TKR 2</option>
                        <option value="X TKR 3" {{ request('rombel') == 'X TKR 3' ? 'selected' : '' }}>X TKR 3</option>
                        <option value="X TKR 4" {{ request('rombel') == 'X TKR 4' ? 'selected' : '' }}>X TKR 4</option>
                        <option value="X TP 1" {{ request('rombel') == 'X TP 1' ? 'selected' : '' }}>X TP 1</option>
                        <option value="X TP 2" {{ request('rombel') == 'X TP 2' ? 'selected' : '' }}>X TP 2</option>
                        <option value="X TP 3" {{ request('rombel') == 'X TP 3' ? 'selected' : '' }}>X TP 3</option>
                        <option value="X TP 4" {{ request('rombel') == 'X TP 4' ? 'selected' : '' }}>X TP 4</option>
                    </optgroup>

                    <optgroup label="Kelas XI">
                        <option value="XI DKV 1" {{ request('rombel') == 'XI DKV 1' ? 'selected' : '' }}>XI DKV 1</option>
                        <option value="XI DKV 2" {{ request('rombel') == 'XI DKV 2' ? 'selected' : '' }}>XI DKV 2</option>
                        <option value="XI DPIB 1" {{ request('rombel') == 'XI DPIB 1' ? 'selected' : '' }}>XI DPIB 1</option>
                        <option value="XI DPIB 2" {{ request('rombel') == 'XI DPIB 2' ? 'selected' : '' }}>XI DPIB 2</option>
                        <option value="XI DPIB 3" {{ request('rombel') == 'XI DPIB 3' ? 'selected' : '' }}>XI DPIB 3</option>
                        <option value="XI GEOMATIKA" {{ request('rombel') == 'XI GEOMATIKA' ? 'selected' : '' }}>XI GEOMATIKA</option>
                        <option value="XI KGS" {{ request('rombel') == 'XI KGS' ? 'selected' : '' }}>XI KGS</option>
                        <option value="XI SIJA 1" {{ request('rombel') == 'XI SIJA 1' ? 'selected' : '' }}>XI SIJA 1</option>
                        <option value="XI SIJA 2" {{ request('rombel') == 'XI SIJA 2' ? 'selected' : '' }}>XI SIJA 2</option>
                        <option value="XI MEKATRONIKA" {{ request('rombel') == 'XI MEKATRONIKA' ? 'selected' : '' }}>XI MEKATRONIKA</option>
                        <option value="XI TAV" {{ request('rombel') == 'XI TAV' ? 'selected' : '' }}>XI TAV</option>
                        <option value="XI TITL 1" {{ request('rombel') == 'XI TITL 1' ? 'selected' : '' }}>XI TITL 1</option>
                        <option value="XI TITL 2" {{ request('rombel') == 'XI TITL 2' ? 'selected' : '' }}>XI TITL 2</option>
                        <option value="XI TITL 3" {{ request('rombel') == 'XI TITL 3' ? 'selected' : '' }}>XI TITL 3</option>
                        <option value="XI TITL 4" {{ request('rombel') == 'XI TITL 4' ? 'selected' : '' }}>XI TITL 4</option>
                        <option value="XI TKR 1" {{ request('rombel') == 'XI TKR 1' ? 'selected' : '' }}>XI TKR 1</option>
                        <option value="XI TKR 2" {{ request('rombel') == 'XI TKR 2' ? 'selected' : '' }}>XI TKR 2</option>
                        <option value="XI TKR 3" {{ request('rombel') == 'XI TKR 3' ? 'selected' : '' }}>XI TKR 3</option>
                        <option value="XI TKR 4" {{ request('rombel') == 'XI TKR 4' ? 'selected' : '' }}>XI TKR 4</option>
                        <option value="XI TP 1" {{ request('rombel') == 'XI TP 1' ? 'selected' : '' }}>XI TP 1</option>
                        <option value="XI TP 2" {{ request('rombel') == 'XI TP 2' ? 'selected' : '' }}>XI TP 2</option>
                        <option value="XI TP 3" {{ request('rombel') == 'XI TP 3' ? 'selected' : '' }}>XI TP 3</option>
                        <option value="XI TP 4" {{ request('rombel') == 'XI TP 4' ? 'selected' : '' }}>XI TP 4</option>
                    </optgroup>

                    <optgroup label="Kelas XII">
                        <option value="XII DKV 1" {{ request('rombel') == 'XII DKV 1' ? 'selected' : '' }}>XII DKV 1</option>
                        <option value="XII DKV 2" {{ request('rombel') == 'XII DKV 2' ? 'selected' : '' }}>XII DKV 2</option>
                        <option value="XII DPIB 1" {{ request('rombel') == 'XII DPIB 1' ? 'selected' : '' }}>XII DPIB 1</option>
                        <option value="XII DPIB 2" {{ request('rombel') == 'XII DPIB 2' ? 'selected' : '' }}>XII DPIB 2</option>
                        <option value="XII DPIB 3" {{ request('rombel') == 'XII DPIB 3' ? 'selected' : '' }}>XII DPIB 3</option>
                        <option value="XII GEOMATIKA" {{ request('rombel') == 'XII GEOMATIKA' ? 'selected' : '' }}>XII GEOMATIKA</option>
                        <option value="XII KGS" {{ request('rombel') == 'XII KGS' ? 'selected' : '' }}>XII KGS</option>
                        <option value="XII SIJA 1" {{ request('rombel') == 'XII SIJA 1' ? 'selected' : '' }}>XII SIJA 1</option>
                        <option value="XII SIJA 2" {{ request('rombel') == 'XII SIJA 2' ? 'selected' : '' }}>XII SIJA 2</option>
                        <option value="XII MEKATRONIKA" {{ request('rombel') == 'XII MEKATRONIKA' ? 'selected' : '' }}>XII MEKATRONIKA</option>
                        <option value="XII TAV" {{ request('rombel') == 'XII TAV' ? 'selected' : '' }}>XII TAV</option>
                        <option value="XII TITL 1" {{ request('rombel') == 'XII TITL 1' ? 'selected' : '' }}>XII TITL 1</option>
                        <option value="XII TITL 2" {{ request('rombel') == 'XII TITL 2' ? 'selected' : '' }}>XII TITL 2</option>
                        <option value="XII TITL 3" {{ request('rombel') == 'XII TITL 3' ? 'selected' : '' }}>XII TITL 3</option>
                        <option value="XII TITL 4" {{ request('rombel') == 'XII TITL 4' ? 'selected' : '' }}>XII TITL 4</option>
                        <option value="XII TKR 1" {{ request('rombel') == 'XII TKR 1' ? 'selected' : '' }}>XII TKR 1</option>
                        <option value="XII TKR 2" {{ request('rombel') == 'XII TKR 2' ? 'selected' : '' }}>XII TKR 2</option>
                        <option value="XII TKR 3" {{ request('rombel') == 'XII TKR 3' ? 'selected' : '' }}>XII TKR 3</option>
                        <option value="XII TKR 4" {{ request('rombel') == 'XII TKR 4' ? 'selected' : '' }}>XII TKR 4</option>
                        <option value="XII TP 1" {{ request('rombel') == 'XII TP 1' ? 'selected' : '' }}>XII TP 1</option>
                        <option value="XII TP 2" {{ request('rombel') == 'XII TP 2' ? 'selected' : '' }}>XII TP 2</option>
                        <option value="XII TP 3" {{ request('rombel') == 'XII TP 3' ? 'selected' : '' }}>XII TP 3</option>
                        <option value="XII TP 4" {{ request('rombel') == 'XII TP 4' ? 'selected' : '' }}>XII TP 4</option>
                    </optgroup>

                    <optgroup label="Kelas XIII (Khusus)">
                        <option value="XIII KGS" {{ request('rombel') == 'XIII KGS' ? 'selected' : '' }}>XIII KGS</option>
                        <option value="XIII SIJA 1" {{ request('rombel') == 'XIII SIJA 1' ? 'selected' : '' }}>XIII SIJA 1</option>
                        <option value="XIII SIJA 2" {{ request('rombel') == 'XIII SIJA 2' ? 'selected' : '' }}>XIII SIJA 2</option>
                    </optgroup>
                </select>

                <input name="q" type="text" placeholder="Cari Nama Siswa / NIS..." value="{{ request('q') }}">

                <button type="submit" style="background:#fff; color:#1e3a67; padding:8px 12px; border-radius:6px; border:none; cursor: pointer; font-weight: 600;">
                    Cari
                </button>
            </form>

        </div>
    </div>

    <div class="card-table">
        <div class="table-header">
            <div style="font-weight:700;">Daftar Siswa</div>
            <div style="font-size:0.9rem; color:#666;">Total: {{ $siswas->total() }}</div>
        </div>

        <form id="form-cetak-mass" action="{{ route('admin.kartupelajar.printMass') }}" method="POST">
            @csrf
            <input type="hidden" name="nis_list" id="mass-ids">

            {{-- Wrapper untuk scroll horizontal tabel --}}
            <div style="overflow-x: auto;">
                <table class="table-list">
                    <thead>
                        <tr>
                            <th class="checkbox-cell" style="width: 40px;"><input type="checkbox" id="check-all"></th>
                            <th style="width: 50px;">No</th>
                            <th>NIS</th>
                            <th>Nama Lengkap</th>
                            <th>ROMBEL</th>
                            <th>Jurusan</th>
                            <th style="width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswas as $i => $siswa)
                        <tr>
                            <td class="checkbox-cell"><input class="row-check" data-nis="{{ $siswa->nis }}" type="checkbox"></td>
                            <td>{{ $i + $siswas->firstItem() }}</td>
                            <td>{{ $siswa->nis }}</td>
                            <td>{{ $siswa->nama_lengkap }}</td>
                            <td>{{ $siswa->rombel }}</td>
                            <td>{{ $siswa->jurusan }}</td>
                            <td>
                                <a href="{{ route('admin.kartupelajar.preview', $siswa->nis) }}" class="btn-cetak">Lihat Kartu</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mass-action-footer">
                <div id="jumlahDipilih" style="color:#555; font-weight:500;">0 siswa dipilih</div>
                <button type="submit" class="btn-cetak-mass">🖨 Cetak Kartu Terpilih</button>
            </div>
        </form>

        <div style="margin-top:12px; display:flex; justify-content:center;">
            {{ $siswas->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const checkAll = document.getElementById('check-all');
    const rowChecks = document.querySelectorAll('.row-check');
    const jumlahDipilih = document.getElementById('jumlahDipilih');
    const massIdsInput = document.getElementById('mass-ids');

    checkAll.addEventListener('change', function() {
        rowChecks.forEach(cb => cb.checked = this.checked);
        updateSelected();
    });

    rowChecks.forEach(cb => cb.addEventListener('change', updateSelected));

    function updateSelected() {
        const checked = Array.from(rowChecks).filter(cb => cb.checked);
        const nisList = checked.map(cb => cb.dataset.nis);
        massIdsInput.value = nisList.join(',');
        jumlahDipilih.textContent = `${nisList.length} siswa dipilih`;
    }
});
</script>
@endsection