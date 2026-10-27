@extends('layouts.admin')

@section('title', 'Edit Template Kartu Pelajar')
@section('page_title', 'Edit Template Kartu Pelajar')

@section('content')
<style>
    .card-settings {
        max-width: 850px;
        margin: 0 auto;
        padding: 24px;
        background: #fff;
        border: 1px solid #e8edf3;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, .05);
    }
    .card-settings h2 { margin: 0 0 6px; color: #123B6B; font-size: 20px; }
    .card-settings-intro { margin: 0 0 22px; color: #64748b; font-size: 13px; }
    .card-settings-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
    .card-settings-field { display: flex; flex-direction: column; gap: 7px; }
    .card-settings-field.full-width { grid-column: 1 / -1; }
    .card-settings-field label { color: #334155; font-size: 13px; font-weight: 700; }
    .card-settings-field input, .card-settings-field select {
        width: 100%; min-height: 42px; padding: 9px 11px; border: 1px solid #cbd5e1;
        border-radius: 8px; background: #fff; color: #1e293b; font: inherit;
    }
    .card-settings-help { color: #64748b; font-size: 11px; }
    .card-settings-signature {
        display: block; max-width: 180px; max-height: 110px; margin-top: 10px;
        padding: 8px; border: 1px dashed #cbd5e1; border-radius: 8px; object-fit: contain;
    }
    .card-settings-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 24px; }
    .card-settings-button, .card-settings-back {
        display: inline-flex; min-height: 42px; align-items: center; justify-content: center;
        gap: 8px; padding: 9px 16px; border: 0; border-radius: 8px;
        color: #fff; font-size: 13px; font-weight: 700; text-decoration: none; cursor: pointer;
    }
    .card-settings-button { background: #123B6B; }
    .card-settings-button:hover { background: #0f2e52; }
    .card-settings-back { background: #64748b; }
    .card-settings-alert { margin-bottom: 18px; padding: 12px 15px; border-radius: 8px; font-size: 13px; }
    .card-settings-success { color: #166534; background: #dcfce7; border: 1px solid #bbf7d0; }
    .card-settings-error { color: #991b1b; background: #fee2e2; border: 1px solid #fecaca; }
    .card-settings-errors { margin: 0; padding-left: 20px; }
    .card-settings-year-select {
        display: flex; align-items: end; gap: 12px; margin-bottom: 20px;
        padding: 14px; border: 1px solid #dbeafe; border-radius: 10px; background: #f8fbff;
    }
    .card-settings-year-select .card-settings-field { flex: 1; }
    .card-settings-year-select button {
        min-height: 42px; padding: 9px 15px; border: 0; border-radius: 8px;
        color: #fff; background: #475569; font-weight: 700; cursor: pointer;
    }
    .card-preview {
        margin: 0 0 22px; padding: 16px; border: 1px solid #dbeafe;
        border-radius: 10px; background: #f8fafc;
    }
    .card-preview h3 { margin: 0 0 5px; color: #123B6B; font-size: 15px; }
    .card-preview p { margin: 0 0 12px; color: #64748b; font-size: 12px; }
    .card-preview-frame-wrap { width: 100%; overflow-x: auto; border-radius: 8px; background: #e2e8f0; }
    .card-preview-frame { display: block; width: 100%; min-width: 750px; height: 250px; border: 0; background: #fff; }
    @media (max-width: 600px) {
        .card-settings { padding: 17px; }
        .card-settings-grid { grid-template-columns: 1fr; }
        .card-settings-field.full-width { grid-column: auto; }
        .card-settings-year-select { align-items: stretch; flex-direction: column; }
        .card-preview { padding: 12px; }
    }
</style>

<section class="card-settings">
    <h2><i class="fas fa-signature"></i> Data Kepala Sekolah pada Kartu</h2>
    <p class="card-settings-intro">
        Pilih angkatan untuk mengatur desain dan identitas kartu siswa angkatan tersebut. Angkatan lain tidak ikut berubah.
    </p>

    @if (session('success'))
        <div class="card-settings-alert card-settings-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="card-settings-alert card-settings-error">
            <ul class="card-settings-errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET" action="{{ route('admin.kartupelajar.pengaturan') }}" class="card-settings-year-select">
        <div class="card-settings-field">
            <label for="pilih_angkatan">Angkatan yang diatur</label>
            <select name="angkatan" id="pilih_angkatan" required>
                @foreach ($daftarAngkatan as $tahunAngkatan)
                    <option value="{{ $tahunAngkatan }}" {{ (int) $angkatan === (int) $tahunAngkatan ? 'selected' : '' }}>
                        Angkatan {{ $tahunAngkatan }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit"><i class="fas fa-filter"></i> Pilih Angkatan</button>
    </form>

    <section class="card-preview">
        <h3><i class="fas fa-eye"></i> Preview Kartu Angkatan {{ $angkatan }}</h3>
        <p>Preview berubah otomatis saat desain atau data kepala sekolah diedit, tetapi belum tersimpan. Klik tombol Simpan Angkatan setelah selesai agar perubahan tersimpan dan berlaku di perangkat lain. Geser ke samping untuk melihat sisi belakang.</p>
        <div class="card-preview-frame-wrap">
            <iframe
                id="cardTemplatePreview"
                class="card-preview-frame"
                title="Preview kartu pelajar"
                src="{{ route('admin.kartupelajar.pengaturan.preview', [
                    'angkatan' => $angkatan,
                    'template_style' => old('template_style', $pengaturan->template_style),
                    'bulan' => old('bulan_terbit', $pengaturan->bulan_terbit),
                    'tahun' => old('tahun_terbit', $pengaturan->tahun_terbit),
                    'nama_kepsek' => old('nama_kepsek', $pengaturan->nama_kepsek),
                    'nip' => old('nip_kepsek', $pengaturan->nip_kepsek),
                ]) }}"
            ></iframe>
        </div>
    </section>

    <form action="{{ route('admin.kartupelajar.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="angkatan" value="{{ $angkatan }}">

        <div class="card-settings-grid">
            <div class="card-settings-field full-width">
                <label for="template_style">Desain kartu untuk angkatan {{ $angkatan }}</label>
                <select name="template_style" id="template_style" required>
                    <option value="standar" {{ old('template_style', $pengaturan->template_style) === 'standar' ? 'selected' : '' }}>
                        Template sebelumnya
                    </option>
                    <option value="baru" {{ old('template_style', $pengaturan->template_style) === 'baru' ? 'selected' : '' }}>
                        Template baru (latar putih)
                    </option>
                </select>
            </div>

            <div class="card-settings-field">
                <label for="bulan_terbit">Bulan terbit</label>
                <select name="bulan_terbit" id="bulan_terbit" required>
                    @for ($bulan = 1; $bulan <= 12; $bulan++)
                        <option value="{{ $bulan }}" {{ (int) old('bulan_terbit', $pengaturan->bulan_terbit) === $bulan ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($bulan)->locale('id')->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="card-settings-field">
                <label for="tahun_terbit">Tahun terbit</label>
                <input type="number" name="tahun_terbit" id="tahun_terbit" min="2000" max="2100" value="{{ old('tahun_terbit', $pengaturan->tahun_terbit) }}" required>
            </div>

            <div class="card-settings-field">
                <label for="nama_kepsek">Nama kepala sekolah</label>
                <input type="text" name="nama_kepsek" id="nama_kepsek" maxlength="255" value="{{ old('nama_kepsek', $pengaturan->nama_kepsek) }}" required>
            </div>

            <div class="card-settings-field">
                <label for="nip_kepsek">NIP kepala sekolah</label>
                <input type="text" name="nip_kepsek" id="nip_kepsek" maxlength="50" value="{{ old('nip_kepsek', $pengaturan->nip_kepsek) }}" required>
            </div>

            <div class="card-settings-field full-width">
                <label for="tanda_tangan">Gambar cap dan tanda tangan</label>
                <input type="file" name="tanda_tangan" id="tanda_tangan" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                <span class="card-settings-help">Format JPG atau PNG, maksimal 2 MB. Kosongkan bila tidak ingin mengganti gambar.</span>
                @if ($pengaturan->tanda_tangan_path)
                    <img class="card-settings-signature" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($pengaturan->tanda_tangan_path) }}" alt="Cap dan tanda tangan tersimpan">
                @else
                    <img class="card-settings-signature" src="{{ asset('images/ttd_cap_kepsek.png') }}" alt="Cap dan tanda tangan saat ini">
                @endif
            </div>
        </div>

        <div class="card-settings-actions">
            <button type="submit" class="card-settings-button"><i class="fas fa-save"></i> Simpan Angkatan {{ $angkatan }}</button>
            <a href="{{ route('admin.kartupelajar.index') }}" class="card-settings-back"><i class="fas fa-arrow-left"></i> Kembali ke Kartu Pelajar</a>
        </div>
    </form>
</section>

<script>
(function () {
    const preview = document.getElementById('cardTemplatePreview');
    const angkatan = @json($angkatan);
    const previewUrl = @json(route('admin.kartupelajar.pengaturan.preview'));
    const signatureInput = document.getElementById('tanda_tangan');
    let signatureData = null;
    let previewTimer;

    function applySignaturePreview() {
        if (!signatureData || !preview.contentDocument) return;
        const image = preview.contentDocument.querySelector('.ttd-cap');
        if (image) image.src = signatureData;
    }

    preview.addEventListener('load', applySignaturePreview);

    function refreshPreview() {
        const params = new URLSearchParams({
            angkatan: String(angkatan),
            template_style: document.getElementById('template_style').value,
            bulan: document.getElementById('bulan_terbit').value,
            tahun: document.getElementById('tahun_terbit').value,
            nama_kepsek: document.getElementById('nama_kepsek').value,
            nip: document.getElementById('nip_kepsek').value
        });

        preview.src = previewUrl + '?' + params.toString();
    }

    document.querySelectorAll('#template_style, #bulan_terbit, #tahun_terbit, #nama_kepsek, #nip_kepsek')
        .forEach(function (input) {
            input.addEventListener('input', function () {
                window.clearTimeout(previewTimer);
                previewTimer = window.setTimeout(refreshPreview, 300);
            });
            input.addEventListener('change', refreshPreview);
        });

    signatureInput.addEventListener('change', function () {
        const file = signatureInput.files && signatureInput.files[0];
        if (!file) {
            signatureData = null;
            return;
        }

        const reader = new FileReader();
        reader.onload = function () {
            signatureData = reader.result;
            applySignaturePreview();
        };
        reader.onerror = function () {
            alert('Gambar tanda tangan gagal dibaca. Silakan pilih file gambar lain.');
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('pilih_angkatan').addEventListener('change', function () {
        this.form.submit();
    });
})();
</script>
@endsection
