@extends('layouts.admin')

@section('title', 'Pengaturan Poin Pelanggaran')
@section('page_title', 'Pengaturan')

@section('content')
<div style="max-width: 720px;">
    @if (session('success'))
        <div role="status" style="margin-bottom: 18px; padding: 12px 16px; border: 1px solid #bbf7d0; border-radius: 6px; background: #f0fdf4; color: #166534;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div role="alert" style="margin-bottom: 18px; padding: 12px 16px; border: 1px solid #fecaca; border-radius: 6px; background: #fef2f2; color: #991b1b;">
            {{ $errors->first('poin_per_keterlambatan') }}
        </div>
    @endif

    <section style="padding: 22px; border: 1px solid #e2e8f0; border-radius: 6px; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        <h3 style="margin: 0 0 6px; color: #17375d; font-size: 18px;">Poin Pelanggaran</h3>
        <p style="margin: 0 0 20px; color: #64748b; font-size: 13px;">Atur jumlah poin untuk setiap izin masuk kelas yang tercatat sebagai keterlambatan.</p>

        <form method="POST" action="{{ route('admin.pengaturan.poin.update') }}">
            @csrf
            @method('PUT')

            <label for="poin_per_keterlambatan" style="display: block; margin-bottom: 7px; color: #334155; font-size: 13px; font-weight: 600;">
                Poin per keterlambatan
            </label>
            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <input
                    id="poin_per_keterlambatan"
                    name="poin_per_keterlambatan"
                    type="number"
                    min="0"
                    max="1000"
                    step="1"
                    required
                    value="{{ old('poin_per_keterlambatan', $poinPerKeterlambatan) }}"
                    style="width: 150px; min-height: 42px; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 4px; color: #1e293b; font: inherit;"
                >
                <span style="color: #64748b; font-size: 13px;">poin</span>
                <button type="submit" style="min-height: 42px; padding: 9px 16px; border: 0; border-radius: 4px; background: #17375d; color: #fff; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-save" aria-hidden="true"></i> Simpan
                </button>
            </div>
            <small style="display: block; margin-top: 9px; color: #64748b; font-size: 12px;">Masukkan angka 0 sampai 1000. Perubahan langsung digunakan pada dashboard siswa.</small>
        </form>
    </section>
</div>
@endsection