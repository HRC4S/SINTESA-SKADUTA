@extends('layouts.admin')

@section('title', 'Edit Dokumen Siswa')
@section('page_title', 'Edit Dokumen Siswa Wali Kelas')

@section('content')
@if(session('success'))
    <div style="background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:15px;">
        {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div style="background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:15px;">
        <ul style="margin:0; padding-left:20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header" style="background-color:#123B6B; color:white; padding:12px; border-radius:8px 8px 0 0;">
        <h5 style="margin:0;">Form Edit Dokumen Siswa</h5>
    </div>

    <div class="card-body" style="background:white; padding:20px; border-radius:0 0 8px 8px;">
        <form action="{{ route('wali.dokumensiswa.update', $siswa->nis) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nis">NIS</label>
                <input type="text" name="nis" id="nis" value="{{ $siswa->nis }}" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ $siswa->nama_lengkap }}" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="dokumen">Jenis Dokumen</label>
                <select name="dokumen" id="dokumen" class="form-control" required>
                    <option value="">Pilih Dokumen</option>
                    @foreach ($dokumenWajib as $jenis)
                        <option value="{{ $jenis }}"
                            {{ (old('dokumen') ?? $dokumenDipilih) == $jenis ? 'selected' : '' }}>
                            {{ $jenis }}
                        </option>
                    @endforeach
                </select>
            </div>

            @php
                // file existing berdasarkan jenis terpilih
                $dokumenExisting = $siswa->dokumen->where('jenis_dokumen', $dokumenDipilih)->first();
            @endphp

            <div class="form-group">
                <label for="file">Upload Dokumen Baru (Opsional)</label>
                <input type="file" name="file" id="file" class="form-control">

                @if ($dokumenExisting && $dokumenExisting->file_path)
                    <small>
                        Dokumen saat ini:
                        <a href="{{ asset('storage/'.$dokumenExisting->file_path) }}" target="_blank">
                            Lihat File
                        </a>
                    </small>
                @else
                    <small style="color:#888;">Belum ada dokumen yang diunggah.</small>
                @endif
            </div>

            <div style="margin-top:20px; display:flex; justify-content:flex-end; gap:10px;">
                <a href="{{ route('wali.dokumensiswa') }}" class="btn-batal">Batal</a>
                <button type="submit" class="btn-simpan">Simpan Perubahan</button>
            </div>

        </form>
    </div>
</div>

<style>
.card {
    background: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 6px;
    color: #333;
}

.form-control {
    background: #f8f9fa;
    border: none;
    border-radius: 8px;
    padding: 10px 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    width: 100%;
}

.btn-simpan {
    background: #1abc9c;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    cursor: pointer;
    font-weight: 600;
    transition: 0.2s;
}

.btn-simpan:hover {
    background: #16a085;
}

.btn-batal {
    background: #e0e0e0;
    color: #333;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.2s;
}

.btn-batal:hover {
    background: #ccc;
}
</style>
@endsection
