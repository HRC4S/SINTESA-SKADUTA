@extends('layouts.admin')

@section('title', 'Detail Rekap Izin')
@section('page_title', 'Detail Pengajuan Izin Siswa')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    * { font-family: 'Poppins', sans-serif; }
    .card-custom { background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; }
    .header-detail {
        background-color: #123B6B; color: white; padding: 20px 25px;
        display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;
    }
    .header-detail h4 { margin: 0; font-weight: 600; font-size: 1.2rem; }
    .header-detail p { margin: 4px 0 0; font-size: 12.5px; opacity: 0.85; }
    .back-link {
        color: #123B6B; font-size: 13px; text-decoration: none; font-weight: 600;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .back-link:hover { text-decoration: underline; }
    .table-responsive { width: 100%; overflow-x: auto; padding: 20px 25px 25px; }
    table { width: 100%; border-collapse: collapse; min-width: 780px; }
    thead { background-color: #2c3e50; color: white; }
    th, td { padding: 12px 14px; border-bottom: 1px solid #eee; text-align: left; font-size: 13.5px; vertical-align: middle; }
    th.center, td.center { text-align: center; }
    tbody tr:hover { background-color: #f9fafb; }
    .badge-jenis {
        display: inline-block; padding: 4px 10px; border-radius: 12px;
        font-size: 11px; font-weight: 700;
    }
    .badge-masuk { background: #dbeafe; color: #1e40af; }
    .badge-keluar { background: #ffedd5; color: #9a3412; }
    .empty-state { text-align: center; padding: 40px; color: #777; }
    .detail-actions { padding: 0 25px 20px; }
</style>

<div class="card-custom">
    <div class="header-detail">
        <div>
            <h4><i class="fas fa-user-clock"></i> {{ $siswa->nama_lengkap }}</h4>
            <p>NIS: {{ $siswa->nis }} &middot; Kelas: {{ $siswa->rombel ?: '-' }} &middot; Rekap {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</p>
        </div>
    </div>

    <div class="detail-actions">
        <a href="{{ route('admin.keterlambatan.rekapBulanan', ['rombel' => $siswa->rombel, 'bulan' => $bulan, 'tahun' => $tahun]) }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Rekap Bulanan
        </a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th class="center">No</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Jenis Izin</th>
                    <th>Jam Ke</th>
                    <th>Keterangan</th>
                    <th class="center">Bukti</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengajuan as $item)
                    <tr>
                        <td class="center">{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                        <td>
                            <span class="badge-jenis {{ $item->jenis === 'keluar' ? 'badge-keluar' : 'badge-masuk' }}">
                                {{ $item->jenis === 'keluar' ? 'Izin Keluar' : 'Izin Masuk / Terlambat' }}
                            </span>
                        </td>
                        <td>{{ $item->jam_ke ?: '-' }}</td>
                        <td>{{ $item->keterangan ?: '-' }}</td>
                        <td class="center">
                            @if ($item->dokumen)
                                <a href="{{ asset('uploads/dokumen_izin/' . $item->dokumen) }}" target="_blank"
                                   title="Lihat bukti pengajuan">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            @else
                                <span style="color:#ccc;">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">Tidak ada pengajuan disetujui pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
