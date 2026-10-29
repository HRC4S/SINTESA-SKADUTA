@extends('layouts.admin')

@section('title', 'List Bukti Presensi')
@section('page_title', 'List Bukti Presensi')

@section('content')
<style>
    .bukti-page { background: #fff; border-radius: 12px; padding: 22px; box-shadow: 0 4px 15px rgba(0,0,0,.05); }
    .bukti-page-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 18px; }
    .bukti-page-header h2 { margin: 0; color: #123B6B; font-size: 18px; }
    .bukti-page-header p { color: #64748b; font-size: 13px; margin: 5px 0 0; }
    .bukti-back { color: #123B6B; font-size: 13px; font-weight: 600; text-decoration: none; white-space: nowrap; }
    .bukti-count { display: inline-block; border-radius: 20px; background: #fef3c7; color: #92400e; padding: 5px 11px; font-size: 12px; font-weight: 700; margin-bottom: 12px; }
    .bukti-table-wrap { width: 100%; overflow-x: auto; }
    .bukti-table { width: 100%; border-collapse: collapse; min-width: 700px; }
    .bukti-table th, .bukti-table td { border-bottom: 1px solid #e2e8f0; padding: 12px; text-align: left; font-size: 13px; }
    .bukti-table th { background: #f8fafc; color: #123B6B; font-weight: 700; white-space: nowrap; }
    .bukti-student { color: #1e293b; font-weight: 700; }
    .bukti-subtext { color: #64748b; font-size: 11px; margin-top: 3px; }
    .bukti-status { display: inline-block; border-radius: 14px; padding: 4px 9px; color: #854d0e; background: #fef3c7; font-size: 11px; font-weight: 700; }
    .bukti-view-button { display: inline-block; border: 0; border-radius: 7px; background: #123B6B; color: #fff; padding: 8px 12px; cursor: pointer; font-size: 12px; font-weight: 600; text-decoration: none; }
    .bukti-view-button:hover { background: #0f2e52; }
    .bukti-empty { padding: 32px 16px !important; color: #64748b; text-align: center !important; }
    .bukti-pagination { margin-top: 16px; }
    @media (max-width: 600px) {
        .bukti-page { padding: 15px; }
        .bukti-page-header { align-items: flex-start; flex-direction: column; }
    }
</style>

<section class="bukti-page">
    <div class="bukti-page-header">
        <div>
            <h2>Bukti presensi yang menunggu ditinjau</h2>
            <p>Klik “Lihat Bukti” untuk membuka file di tab baru. Setelah itu, bukti otomatis dikeluarkan dari daftar.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="bukti-back"><i class="fas fa-arrow-left"></i> Dashboard</a>
    </div>

    <span class="bukti-count"><span data-pending-count>{{ $buktiPresensi->total() }}</span> bukti menunggu</span>

    <div class="bukti-table-wrap">
        <table class="bukti-table">
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Tanggal Presensi</th>
                    <th>Diunggah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($buktiPresensi as $bukti)
                    <tr data-bukti-row>
                        <td>
                            <div class="bukti-student">{{ $bukti->siswa->nama_lengkap ?? $bukti->nis }}</div>
                            <div class="bukti-subtext">NIS {{ $bukti->nis }}</div>
                        </td>
                        <td>{{ $bukti->rombel }}</td>
                        <td>{{ $bukti->tanggal->locale('id')->translatedFormat('d F Y') }}</td>
                        <td>
                            @if ($bukti->bukti_uploaded_at)
                                {{ $bukti->bukti_uploaded_at->locale('id')->translatedFormat('d F Y, H:i') }} WIB
                            @else
                                -
                            @endif
                        </td>
                        <td><span class="bukti-status">{{ $bukti->label_status }}</span></td>
                        <td>
                            <a
                                href="{{ asset('storage/' . $bukti->bukti_path) }}"
                                target="_blank"
                                rel="noopener"
                                class="bukti-view-button"
                                data-review-url="{{ route('admin.presensi.bukti.lihat', $bukti->id) }}"
                                data-csrf-token="{{ csrf_token() }}"
                            ><i class="fas fa-eye"></i> Lihat Bukti</a>
                        </td>
                    </tr>
                @empty
                    <tr data-empty-row>
                        <td colspan="6" class="bukti-empty">
                            <i class="fas fa-circle-check" style="font-size:24px; display:block; margin-bottom:8px; color:#16a34a;"></i>
                            Semua bukti sudah ditinjau. Belum ada bukti baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($buktiPresensi->hasPages())
        <div class="bukti-pagination">{{ $buktiPresensi->links() }}</div>
    @endif
</section>

<script>
document.querySelectorAll('[data-review-url]').forEach(function (link) {
    link.addEventListener('click', function () {
        const row = link.closest('[data-bukti-row]');
        if (!row) return;

        fetch(link.dataset.reviewUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': link.dataset.csrfToken,
                'Accept': 'application/json'
            }
        }).then(function (response) {
            if (!response.ok) throw new Error('Status ' + response.status);

            row.remove();
            const pendingCount = document.querySelector('[data-pending-count]');
            const remaining = document.querySelectorAll('[data-bukti-row]').length;
            pendingCount.textContent = Math.max(0, Number(pendingCount.textContent) - 1);

            if (remaining === 0 && !document.querySelector('[data-empty-row]')) {
                const emptyRow = document.createElement('tr');
                emptyRow.setAttribute('data-empty-row', '');
                emptyRow.innerHTML = '<td colspan="6" class="bukti-empty">Semua bukti yang tampil sudah ditinjau.</td>';
                document.querySelector('.bukti-table tbody').appendChild(emptyRow);
            }
        }).catch(function () {
            alert('File tetap dibuka, tetapi status tinjauan gagal disimpan. Silakan muat ulang halaman dan coba lagi.');
        });
    });
});
</script>
@endsection
