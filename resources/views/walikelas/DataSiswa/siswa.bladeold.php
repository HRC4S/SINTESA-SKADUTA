@extends('layouts.admin')

@section('title', 'Data Siswa Wali Kelas')
@section('page_title', 'Data Siswa Kelas ' . $rombel)

@section('content')
@if(session('success'))
    <div style="background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:15px;">
        {{ session('success') }}
    </div>
@endif

<div class="filter-container" style="margin-bottom:20px;">
   {{-- INFORMASI ROMBEL WALI --}}
    <div style="background:#1e3a67; padding:18px; border-radius:8px; color:#fff;">
        <div style="font-size:1.1rem; font-weight:600;">
            Rombel yang Anda ampu : {{ $rombel }}
        </div>

        {{-- FORM SEARCH (Tanpa dropdown kelas karena otomatis filter) --}}
        <form action="{{ route('wali.kartupelajar.index') }}" method="GET" style="margin-top:12px; display:flex; gap:8px;">
            <input name="q" type="text" placeholder="Cari nama / NIS..." 
                   value="{{ request('q') }}"
                   style="flex:1; padding:10px 12px; border-radius:6px; border:none; outline:none;">

            <button type="submit" style="background:#fff; color:#1e3a67; padding:8px 12px; border-radius:6px; border:none;">
                Cari
            </button>
        </form>
    </div>
 
    <div style="margin-top:6px; text-align:right;">
        <strong>Jumlah siswa di {{ $rombel }}:</strong> {{ $jumlah }}
    </div>

       <div style="margin-top:10px; text-align:right;">
    <form action="{{ route('wali.datasiswa.naikkanSemua') }}" method="POST"
          onsubmit="return confirm('Yakin ingin menaikkan semua siswa di rombel ini?');">
        @csrf
        <button class="btn btn-success" style="background:#8e44ad; color:white; padding:7px 14px; border-radius:8px;">
            <i class="fas fa-arrow-circle-up"></i> Naikkan Semua Siswa
        </button>
    </form>
    </div>
</div>

<style>
.table { 
    width:100%; 
    border-collapse:collapse; 
    background:#fff; 
    border-radius:8px; 
    overflow:hidden; 
}

.table thead { 
    background:#2c3e50; 
    color:#fff; 
}

.table th, 
.table td { 
    padding:10px; 
    border-top:1px solid #eee; 
    font-size:14px; 
    vertical-align: middle;     /* ➜ Biar sejajar vertikal (tidak mencong) */
    text-align: left;           /* ➜ Semua teks rata kiri */
    white-space: nowrap;        /* ➜ Mencegah teks turun baris */
}

/* Kolom nomor tetap rata tengah */
.table th:nth-child(1),
.table td:nth-child(1) {
    text-align:center;
}

.table tbody tr:hover { 
    background:#f8f9fa; 
}

.search-box { 
    display:flex; 
    background:#f8f9fa; 
    border-radius:12px; 
    box-shadow:0 3px 6px rgba(0,0,0,0.06); 
}

.search-box input { 
    border:none; 
    padding:10px 12px; 
    background:transparent; 
    width:260px; 
}

.search-box button { 
    background:transparent; 
    border:none; 
    padding:8px 12px; 
    cursor:pointer; 
}
/* Container aksi */
.aksi-container {
    display: flex;
    align-items: center;
    gap: 18px;
}

/* === GAYA UMUM UNTUK SEMUA TOMBOL === */
.aksi-lihat,
.aksi-edit,
.aksi-hapus,
.aksi-toggle,
.aksi-naik {
    display: flex;
    flex-direction: column;   /* ikon di atas, teks di bawah */
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 12px;
    background: none;
    border: none;
    cursor: pointer;
}

/* Ikon semua aksi */
.aksi-lihat i,
.aksi-edit i,
.aksi-hapus i,
.aksi-toggle i,
.aksi-naik i {
    font-size: 18px;
    margin-bottom: 3px;
}

/* === WARNA TETAP SAMA === */

/* Lihat (biru) */
.aksi-lihat { color: #007bff; }

/* Edit (oranye) */
.aksi-edit { color: #ff9800; }

/* Hapus (merah) */
.aksi-hapus { color: #e74c3c; }

/* Toggle akses (warna sudah di inline dari kode kamu) */

/* Naik kelas (ungu, sama seperti admin) */
.aksi-naik { color: #8e44ad; }

/* Hover tetap sama */
.aksi-lihat:hover,
.aksi-edit:hover,
.aksi-hapus:hover,
.aksi-naik:hover,
.aksi-toggle:hover {
    opacity: 0.7;
}


/* WRAPPER GROUP */
.filter-group {
    margin-bottom: 20px;
}

.filter-group label {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 6px;
    display: block;
}

/* SEARCH BOX */
.search-box {
    display: flex;
    align-items: center;
    background: #ffffff;
    border: 1px solid #ddd;
    padding: 0 10px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    transition: 0.2s ease-in-out;
}

.search-box:hover {
    border-color: #b5b5b5;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
}

.search-box input {
    width: 100%;
    padding: 10px 8px;
    border: none;
    outline: none;
    font-size: 14px;
    background: transparent;
}

.search-box button {
    border: none;
    background: none;
    cursor: pointer;
    padding: 6px;
    font-size: 18px;
    color: #555;
    transition: 0.2s;
}

.search-box button:hover {
    color: #000;
}

</style>


<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th style="width:5%; text-align:center;">No</th>
                <th style="width:15%;">NIS</th>
                <th style="width:15%;">NISN</th>
                <th>Nama Lengkap</th>
                <th style="width:15%;">Jenis Kelamin</th>
                <th style="width:20%;text-align:center;">Email</th>
                <th style="width:15%; text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswas as $i => $s)
            <tr>
                <td style="text-align:center;">{{ $i + $siswas->firstItem() }}</td>
                <td>{{ $s->nis }}</td>
                <td>{{ $s->nisn }}</td>
                <td>{{ $s->nama_lengkap }}</td>
                <td>{{ $s->jenis_kelamin }}</td>
                <td>{{ $s->email }}</td>
                <td>
                 
    <div class="aksi-container">

                {{-- Tombol Lihat --}}
                <a href="{{ route('wali.datasiswa.show', $s->nis) }}" class="aksi-lihat">
                    <i class="fas fa-eye"></i> Lihat
                </a>

                {{-- Tombol Edit --}}
                <a href="{{ route('wali.datasiswa.edit', $s->nis) }}" class="aksi-edit">
                    <i class="fas fa-edit"></i> Edit
                </a>

                {{-- Tombol Hapus --}}
                <form action="{{ route('wali.datasiswa.destroy', $s->nis) }}" method="POST" 
                    onsubmit="return confirm('Yakin ingin menghapus siswa ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="aksi-hapus" style="background:none; border:none;">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>

                {{-- Toggle Akses Edit --}}
                <form action="{{ route('wali.datasiswa.toggleAkses', $s->nis) }}" method="POST">
                    @csrf
                    @if($s->akses_edit)
                        <button type="submit" class="aksi-toggle" style="color:#16a085;">
                            <i class="fas fa-unlock"></i> Aktif
                        </button>
                    @else
                        <button type="submit" class="aksi-toggle" style="color:#e74c3c;">
                            <i class="fas fa-lock"></i> Nonaktif
                        </button>
                    @endif
                </form>

                <form action="{{ route('wali.datasiswa.naikkanSatu', $s->nis) }}" method="POST"
                onsubmit="return confirm('Naikkan kelas siswa ini?');">
                @csrf
                <button type="submit" class="aksi-naik" >
                    <i class="fas fa-arrow-circle-up"></i> Naikkan
                </button>
            </form>
    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:12px;">Tidak ada data siswa pada rombel ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- pagination --}}
<div style="margin-top:18px; display:flex; justify-content:center;">
    {{ $siswas->appends(request()->query())->links('pagination::simple-tailwind') }}
</div>
@endsection
