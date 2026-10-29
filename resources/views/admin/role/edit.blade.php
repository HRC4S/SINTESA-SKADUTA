@extends('layouts.admin')

@section('title', 'Edit Role')
@section('page_title', 'Edit Role')

@section('content')

{{-- Load FontAwesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
    
    /* ===========================
       BASE STYLES
       =========================== */
    * { font-family: 'Poppins', sans-serif; box-sizing: border-box; }

    .edit-container {
        display: flex;
        justify-content: center;
        padding-top: 20px;
    }

    .card-edit {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        width: 100%;
        max-width: 600px; /* Batas lebar di desktop */
        overflow: hidden;
    }

    /* HEADER */
    .card-header {
        background: #123B6B;
        color: white;
        padding: 20px 25px;
        border-bottom: 3px solid #0a2240;
    }
    .card-header h4 { margin: 0; font-weight: 600; font-size: 1.1rem; }

    /* BODY */
    .card-body { padding: 30px; }

    .form-group { margin-bottom: 20px; }

    label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        color: #333;
        font-size: 0.9rem;
    }

    /* INPUT STYLE */
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        transition: 0.3s;
        background-color: #fff;
    }

    .form-control:focus {
        border-color: #123B6B;
        outline: none;
        box-shadow: 0 0 0 3px rgba(18, 59, 107, 0.1);
    }

    /* READONLY INPUT STYLE */
    .input-readonly {
        background-color: #f3f4f6;
        color: #6b7280;
        cursor: not-allowed;
        border-color: #e5e7eb;
    }

    /* BUTTONS */
    .btn-group {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #f3f4f6;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s;
        border: none;
    }

    .btn-primary {
        background: #123B6B;
        color: white;
    }
    .btn-primary:hover {
        background: #0f2e52;
        box-shadow: 0 4px 10px rgba(18, 59, 107, 0.2);
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
    }
    .btn-secondary:hover {
        background: #d1d5db;
        color: #111;
    }

    /* ===========================
       MOBILE RESPONSIVE
       =========================== */
    @media (max-width: 768px) {
        .card-body { padding: 20px; }
        
        .btn-group {
            flex-direction: column-reverse; /* Tombol Simpan di atas */
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="edit-container">
    <div class="card-edit">
        
        {{-- Header --}}
        <div class="card-header">
            <h4><i class="fas fa-user-edit"></i> Edit Role User</h4>
        </div>

        {{-- Form Body --}}
        <div class="card-body">
            <form action="{{ route('admin.role.update', $role->nip ?? $role->nis) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nama Lengkap (Readonly) --}}
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control input-readonly" 
                           value="{{ $role->nama_lengkap ?? $role->nama }}" readonly>
                </div>

                {{-- NIP/NIS (Readonly) --}}
                <div class="form-group">
                    <label>NIP / NIS</label>
                    <input type="text" class="form-control input-readonly" 
                           value="{{ $role->nip ?? $role->nis }}" readonly>
                </div>

                {{-- Email (Readonly) --}}
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control input-readonly" 
                           value="{{ $role->email }}" readonly>
                </div>

                {{-- Role Selection --}}
                <div class="form-group">
                    <label for="role">Role Pengguna <span style="color:red">*</span></label>
                    <select name="role" id="role" class="form-control" required>
                        @if($role instanceof \App\Models\Siswa)
                            <option value="siswa" {{ strtolower($role->role) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="koor_kelas" {{ strtolower($role->role) == 'koor_kelas' ? 'selected' : '' }}>Koor Kelas (Presensi)</option>
                        @else
                            <option value="admin" {{ $role->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="guru_bk" {{ $role->role == 'guru_bk' ? 'selected' : '' }}>Guru BK</option>
                            <option value="guru" {{ $role->role == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="kesiswaan" {{ $role->role == 'kesiswaan' ? 'selected' : '' }}>Kesiswaan</option>
                        @endif
                    </select>
                    @if($role instanceof \App\Models\Siswa)
                        <small style="color:#666; font-size:12px; margin-top:5px; display:block;">
                            Kelas: <strong>{{ $role->rombel }}</strong> — kalau dijadikan Koor Kelas, koor lama di kelas
                            yang sama otomatis diturunkan jadi siswa biasa (hanya boleh 1 koor aktif per kelas).
                        </small>
                    @endif
                </div>

                {{-- 🔹 Kondisional: Jika User adalah Guru, tampilkan Wali Kelas --}}
                @if($role instanceof \App\Models\Guru)
                    <div class="form-group" style="background:#f9fafb; padding:15px; border-radius:8px; border:1px dashed #ccc;">
                        <label for="walikelas" style="color:#123B6B;">
                            <i class="fas fa-chalkboard-teacher"></i> Wali Kelas (Opsional)
                        </label>
                        <select name="walikelas" id="walikelas" class="form-control">
                            <option value="">-- Tidak Menjadi Wali Kelas --</option>
                            @foreach($rombels as $r)
                                <option value="{{ $r->rombel }}" 
                                    {{ $role->walikelas == $r->rombel ? 'selected' : '' }}>
                                    {{ $r->rombel }}
                                </option>
                            @endforeach
                        </select>
                        <small style="color:#666; font-size:12px; margin-top:5px; display:block;">
                            Pilih kelas jika guru ini menjabat sebagai Wali Kelas.
                        </small>
                    </div>
                @endif

                {{-- Action Buttons --}}
                <div class="btn-group">
                    <a href="{{ route('admin.role.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection