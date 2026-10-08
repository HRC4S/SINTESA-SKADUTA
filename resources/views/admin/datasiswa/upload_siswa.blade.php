@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Unggah Data Siswa dari Excel</h2>
    
    <div class="card p-4">
        <p class="text-muted">Pilih file Excel (.xlsx / .xls) untuk mengunggah data siswa.</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach</ul>
            </div>
        @endif
    
        <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data" class="mt-3">
            @csrf
            <div class="form-group mb-3">
                <label for="file" class="form-label">Pilih File</label>
                <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
            </div>
            <button type="submit" class="btn btn-primary">
                Unggah Data
            </button>
        </form>
    </div>

    <hr class="my-4">
    <div class="card p-4">
        <p class="text-muted">Template header file Excel (baris pertama) yang benar:</p>
        <pre><code>nis, nisn, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, nama_orang_tua, alamat_siswa, kelas, jurusan</code></pre>
    </div>
</div>
@endsection