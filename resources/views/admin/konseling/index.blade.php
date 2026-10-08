@extends('layouts.admin')

@section('title', 'Manajemen Konseling')
@section('page_title', 'Manajemen Konseling')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    /* ... (Style Container & Header sama seperti sebelumnya) ... */
    .konseling-container { background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 30px; }
    .header-konseling { background: #123B6B; color: white; padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; }
    .header-konseling h4 { margin: 0; font-weight: 600; font-size: 1.2rem; }
    
    /* Toolbar & Filter */
    .toolbar-wrapper { background: #f8f9fa; padding: 15px 30px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 15px; }
    .btn-tambah { border: 2px solid #123B6B; color: #123B6B; background: #fff; padding: 8px 18px; border-radius: 8px; font-weight: 600; text-decoration: none; transition: 0.3s; font-size: 14px; }
    .btn-tambah:hover { background: #123B6B; color: #fff; }

    /* Tabel */
    .table-responsive { width: 100%; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 1000px; }
    thead { background-color: #2c3e50; color: white; }
    th, td { padding: 15px 20px; text-align: left; border-bottom: 1px solid #f0f0f0; font-size: 13px; vertical-align: middle; }
    tbody tr:hover { background-color: #f9fbfd; }

    /* Badge & Aksi */
    .badge { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block; }
    .bg-menunggu { background: #fff3cd; color: #856404; }
    .bg-disetujui { background: #d1e7dd; color: #0f5132; }
    .bg-ditolak { background: #f8d7da; color: #721c24; }

    /* Tombol Approval (Bulat) */
    .btn-circle { width: 32px; height: 32px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; border: none; cursor: pointer; transition: 0.2s; margin-right: 5px; }
    .btn-approve { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
    .btn-approve:hover { background: #22c55e; color: white; transform: scale(1.1); }
    .btn-reject { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .btn-reject:hover { background: #ef4444; color: white; transform: scale(1.1); }

    /* Tanggapan Admin Display */
    .admin-note { font-size: 12px; color: #555; background: #f3f4f6; padding: 8px; border-radius: 6px; border-left: 3px solid #123B6B; margin-top: 5px; display: inline-block; max-width: 200px; }

    /* === MODAL STYLE === */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999; display: none; align-items: center; justify-content: center; }
    .modal-box { background: white; width: 90%; max-width: 500px; border-radius: 10px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.2); animation: slideDown 0.3s ease; }
    .modal-header { background: #123B6B; color: white; padding: 15px 20px; font-weight: 600; display: flex; justify-content: space-between; align-items: center; }
    .modal-body { padding: 20px; }
    .modal-footer { padding: 15px 20px; background: #f8f9fa; text-align: right; border-top: 1px solid #eee; }
    .close-modal { background: none; border: none; color: white; font-size: 20px; cursor: pointer; }
    
    @keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>

{{-- ALERT SUCCESS --}}
@if(session('success'))
    <div style="background:#d1fae5; color:#065f46; padding:15px; border-radius:8px; margin-bottom:20px; border:1px solid #a7f3d0;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="konseling-container">
    {{-- Header --}}
    <div class="header-konseling">
        <h4>📋 Manajemen Konseling</h4>
        <div id="tanggalJamAdmin" style="text-align: right; font-size: 0.9rem;"></div>
    </div>

    {{-- Toolbar --}}
    <div class="toolbar-wrapper">
        <form method="GET" action="{{ route('admin.keterlambatan.index') }}" style="display:flex; gap:10px;">
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" style="padding:8px; border-radius:6px; border:1px solid #ccc;">
            <button type="submit" style="background:#123B6B; color:white; border:none; padding:8px 15px; border-radius:6px; cursor:pointer;">Filter</button>
        </form>
        <a href="{{ route('admin.konseling.create') }}" class="btn-tambah">+ Tambah Data</a>
    </div>

    {{-- Tabel --}}
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Siswa & Kelas</th>
                    <th width="20%">Topik & Layanan</th>
                    <th width="15%">Jadwal Request</th>
                    <th width="10%">Status</th>
                    <th width="20%">Konfirmasi / Tanggapan</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($konselings as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <b>{{ $item->nama_siswa }}</b><br>
                            <span style="font-size:11px; color:#666;">{{ $item->kelas }}</span>
                        </td>
                        <td>
                            <div style="color:#123B6B; font-weight:600;">{{ Str::limit($item->topik, 20) }}</div>
                            <span style="font-size:11px; color:#888;">{{ $item->jenis_layanan ?? '-' }}</span>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}<br>
                            <small>{{ \Carbon\Carbon::parse($item->jam_pengajuan)->format('H:i') }} WIB</small>
                        </td>
                        <td>
                            @php
                                $cls = 'bg-menunggu';
                                if($item->status == 'Disetujui') $cls = 'bg-disetujui';
                                elseif($item->status == 'Ditolak') $cls = 'bg-ditolak';
                            @endphp
                            <span class="badge {{ $cls }}">{{ ucfirst($item->status) }}</span>
                        </td>
                        
                        {{-- LOGIKA APPROVAL --}}
                        <td>
                            @if($item->status == 'Menunggu')
                                {{-- Tombol Membuka Modal --}}
                                <button type="button" class="btn-circle btn-approve" onclick="openModal('{{ $item->id }}', 'Disetujui')" title="Setujui">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button type="button" class="btn-circle btn-reject" onclick="openModal('{{ $item->id }}', 'Ditolak')" title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>
                            @else
                                {{-- Jika sudah diproses, TAMPILKAN TANGGAPAN --}}
                                <div class="admin-note">
                                    <b>Admin:</b> {{ $item->tanggapan_admin }}
                                </div>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.konseling.show', $item->id) }}" style="color:#123B6B;"><i class="fas fa-eye"></i></a>
                            <form action="{{ route('admin.konseling.destroy', $item->id) }}" method="POST" style="display:inline; margin-left:10px;">
                                @csrf @method('DELETE')
                                <button type="submit" style="border:none; background:none; color:red; cursor:pointer;" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center; padding:30px; color:#999;">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- === MODAL POPUP APPROVAL === --}}
<div class="modal-overlay" id="approvalModal">
    <div class="modal-box">
        <div class="modal-header">
            <span id="modalTitle">Konfirmasi Konseling</span>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        
        {{-- Form action akan di-set lewat JS --}}
        <form id="approvalForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="modal-body">
                {{-- Input Hidden Status --}}
                <input type="hidden" name="status" id="inputStatus">

                <div style="margin-bottom: 15px;">
                    <label style="display:block; font-weight:600; margin-bottom:5px;">Berikan Tanggapan / Catatan:</label>
                    <textarea name="tanggapan_admin" id="inputTanggapan" rows="4" 
                        style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;" 
                        placeholder="Contoh: Silakan datang ke ruang BK jam 10.00..." required></textarea>
                    <small style="color:#666;">*Wajib diisi agar siswa mengetahui alasannya.</small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal()" style="background:#ccc; border:none; padding:8px 15px; border-radius:6px; cursor:pointer; margin-right:5px;">Batal</button>
                <button type="submit" style="background:#123B6B; color:white; border:none; padding:8px 15px; border-radius:6px; cursor:pointer;">Simpan & Proses</button>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT --}}
<script>
    function openModal(id, status) {
        // Set Action URL Form secara dinamis
        let url = "{{ route('admin.konseling.proses', ':id') }}";
        url = url.replace(':id', id);
        document.getElementById('approvalForm').action = url;

        // Set Status di Input Hidden
        document.getElementById('inputStatus').value = status;

        // Ubah Judul Modal & Placeholder sesuai status
        if(status === 'Disetujui') {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-check-circle"></i> Setujui Pengajuan';
            document.getElementById('inputTanggapan').placeholder = "Berikan pesan untuk siswa (Misal: Datang tepat waktu ya...)";
        } else {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-times-circle"></i> Tolak Pengajuan';
            document.getElementById('inputTanggapan').placeholder = "Berikan alasan penolakan (Misal: Jadwal penuh, coba hari lain...)";
        }

        // Tampilkan Modal
        document.getElementById('approvalModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('approvalModal').style.display = 'none';
    }

    // Jam Digital
    setInterval(() => {
        const now = new Date();
        document.getElementById('tanggalJamAdmin').innerHTML = now.toLocaleDateString('id-ID') + '<br>' + now.toLocaleTimeString('id-ID');
    }, 1000);
</script>

@endsection