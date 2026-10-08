@extends('layouts.admin')
@section('title', 'Kartu Pelajar')
@section('page_title', isset($siswas) ? 'Preview Kartu Pelajar Massal' : 'Preview Kartu Pelajar')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

/* ====== FONT & STRUKTUR DASAR ====== */
.preview-container,
.preview-container * {
    font-family: 'Poppins', sans-serif !important;
}

.preview-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 1rem;
    padding: 10px;
}

/* ====== PREVIEW FRAME ====== */
iframe.preview-frame {
    width: 100%;
    max-width: 750px;
    height: 850px;
    border: none;
    background: transparent;
    margin-bottom: 10px;
}

/* ====== AREA CETAK ====== */
@page {
    size: A4;
    margin-top: 0.5cm; /* ✅ Set jarak atas 0.5cm untuk setiap halaman */
    margin-bottom: 0;
    margin-left: 0;
    margin-right: 0;
}

body {
    background: none !important;
}

/* Area utama print */
#printArea {
    display: none;
    background: none;
}

/* Lembar cetak massal */
.print-sheet {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 21cm;
    height: auto;
    margin: 0 auto;
    padding-top: 0.5cm; /* ✅ tambahan aman supaya isi turun sedikit */
    box-sizing: border-box;
    background: none;
}

/* Tiap kartu */
.print-card {
    width: 19.5cm;
    height: 5.4cm;
    margin-bottom: 1cm;
    overflow: hidden;
    border: 0.2px solid #ddd;
    display: flex;
    justify-content: center;
    align-items: center;
    page-break-inside: avoid;
    background: transparent !important;
}

/* Iframe di dalam kartu */
.print-card iframe {
    width: 100%;
    height: 100%;
    border: none;
    overflow: hidden;
    background: transparent !important;
}

/* ✅ Hilangkan background putih di dalam iframe */
.print-card iframe html,
.print-card iframe body {
    background-color: transparent !important;
}

/* ✅ Page break setiap 4 kartu */
.print-card:nth-of-type(4n) {
    page-break-after: always;
}

/* ====== GAYA TOMBOL ====== */
.btn-custom {
    padding: 10px 22px;
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: 0.2s ease;
    margin: 0 6px;
}

.btn-custom:hover {
    opacity: 0.9;
}

.btn-cetak {
    background-color: #28a745;
}

.btn-edit {
    background-color: #007bff;
}

.btn-batal {
    background-color: #6c757d;
}

/* ====== CETAK KHUSUS ====== */
@media print {
    html, body {
        margin: 0;
        padding: 0;
        background: none !important;
    }

    body * {
        visibility: hidden !important;
        background: none !important;
    }

    #printArea, #printArea * {
        visibility: visible !important;
        background: none !important;
    }

    /* ✅ Semua halaman turun 0.5 cm */
    #printArea {
        display: block !important;
        position: absolute;
        left: 0;
        top: 0.5cm;
        width: 100%;
        margin: 0;
        padding: 0;
    }

    iframe {
        overflow: hidden !important;
        background: transparent !important;
        color-adjust: exact !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .noprint {
        display: none !important;
    }

    /* ✅ Pastikan semua halaman punya jarak atas konsisten */
    @page {
        margin-top: 0.5cm;
        background: none;
    }
}
</style>


<div class="container py-3">

    <div class="noprint text-center mb-3">
        <p class="text-muted" style="font-size: 0.95rem;">
            {{ isset($siswas) ? 'Preview kartu pelajar sebelum cetak massal' : 'Preview Kartu Pelajar' }}
        </p>
    </div>

    <div class="preview-container noprint">
    {{-- ✅ Preview layar --}}
    @if(isset($siswas))
        {{-- === CETAK MASSAL === --}}
        @foreach($siswas as $siswa)
            <iframe class="preview-frame" src="{{ route('wali.kartupelajar.frame', $siswa->nis) }}"></iframe>
            <hr style="width:100%; max-width:750px; border:1px dashed #ccc;">
        @endforeach

        <button onclick="window.print()" class="btn-custom btn-cetak">🖨 Cetak Semua</button>

{{-- HANYA ADMIN & BK YANG BOLEH EDIT --}}
@unless(auth('guru')->user()->role === 'kesiswaan')
    <button type="button" class="btn-custom btn-edit" onclick="toggleFooterForm()">🧾 Edit Data Kartu</button>
@endunless

<a href="{{ route('kartupelajar.index') }}" class="btn-custom btn-batal">✖ Kembali</a>


   @else
    {{-- === CETAK SATUAN === --}}
    <iframe class="preview-frame" id="kartuFrame" src="{{ route('wali.kartupelajar.frame', $siswa->nis) }}"></iframe>

    <div style="margin-top: 10px; text-align:center;">

        {{-- Tombol Cetak — Semua role boleh --}}
        <button onclick="document.getElementById('kartuFrame').contentWindow.print()" 
                class="btn-custom btn-cetak">
            🖨 Cetak
        </button>

        {{-- HANYA ADMIN & BK yang boleh edit --}}
        @unless(auth('guru')->user()->role === 'kesiswaan')

            <a href="{{ route('wali.datasiswa.edit', $siswa->nis) }}" 
                class="btn-custom btn-edit">
                ✏ Edit
            </a>

            <button type="button" 
                    class="btn-custom btn-edit" 
                    onclick="toggleFooterForm()">
                🧾 Edit Data Kartu
            </button>

        @endunless

        {{-- Tombol kembali — semua role boleh --}}
        <a href="{{ route('wali.kartupelajar.index') }}" 
            class="btn-custom btn-batal">
            ✖ Kembali
        </a>

    </div>
@endif


 {{-- ======================================
   FORM EDIT DATA KARTU (Hanya Admin & BK)
======================================= --}}
@unless(auth('guru')->user()->role === 'kesiswaan')

<div id="footerFormContainer" style="display:none; margin-top:20px; text-align:center;">
    <form id="footerForm" onsubmit="return updateFrameFooter();" 
          style="display:inline-block; background:#f0f4ff; padding:20px; border-radius:8px; text-align:left;">

        <h4 style="color:#17375d; margin-bottom:10px;">Edit Data Kartu</h4>

        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
            <div>
                <label>Bulan:</label>
                <input type="text" name="bulan" id="bulan" value="{{ date('F') }}"
                       required style="padding:5px 10px; border-radius:6px;">
            </div>

            <div>
                <label>Tahun:</label>
                <input type="number" name="tahun" id="tahun" value="{{ date('Y') }}"
                       required style="padding:5px 10px; border-radius:6px;">
            </div>

            <div>
                <label>Nama Kepala Sekolah:</label>
                <input type="text" name="nama_kepsek" id="nama_kepsek"
                       placeholder="Drs. Agus Waluyo, M.Eng."
                       required style="padding:5px 10px; border-radius:6px;">
            </div>

            <div>
                <label>NIP:</label>
                <input type="text" name="nip" id="nip"
                       placeholder="196512271994121002"
                       required style="padding:5px 10px; border-radius:6px;">
            </div>

            <div>
                <label>Gambar Cap & TTD Kepala Sekolah:</label>
                <input type="file" id="cap_kepsek" accept="image/*"
                       style="padding:5px 10px; border-radius:6px;">
            </div>
        </div>

        <div style="margin-top: 10px; text-align:center;">
            <button type="submit" class="btn-custom btn-cetak">💾 Simpan Perubahan</button>
            <button type="button" class="btn-custom btn-batal" onclick="toggleFooterForm()">Batal</button>
        </div>

    </form>
</div>

@endunless


</div>

    {{-- ✅ Area cetak massal --}}
    @if(isset($siswas))
    <div id="printArea">
        <div class="print-sheet">
            @foreach($siswas as $siswa)
            <div class="print-card">
                <iframe src="{{ route('wali.kartupelajar.frame', $siswa->nis) }}" scrolling="no"></iframe>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

    <script>
function toggleFooterForm() {
    const form = document.getElementById('footerFormContainer');
    form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
}

function updateFrameFooter() {
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;
    const nama_kepsek = document.getElementById('nama_kepsek').value;
    const nip = document.getElementById('nip').value;
    const fileInput = document.getElementById('cap_kepsek');
    const file = fileInput.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const cap_kepsek = encodeURIComponent(e.target.result);
            refreshFrames(bulan, tahun, nama_kepsek, nip, cap_kepsek);
        };
        reader.readAsDataURL(file);
    } else {
        refreshFrames(bulan, tahun, nama_kepsek, nip, '');
    }

    toggleFooterForm();
    alert('✅ Data kartu berhasil diperbarui!');
    return false;
}

function refreshFrames(bulan, tahun, nama_kepsek, nip, cap_kepsek) {
    const previewFrames = document.querySelectorAll('.preview-frame');
    const printFrames = document.querySelectorAll('#printArea iframe');

    [...previewFrames, ...printFrames].forEach(frame => {
        const baseSrc = frame.src.split('?')[0];
        frame.src = `${baseSrc}?bulan=${encodeURIComponent(bulan)}&tahun=${encodeURIComponent(tahun)}&nama_kepsek=${encodeURIComponent(nama_kepsek)}&nip=${encodeURIComponent(nip)}&cap_kepsek=${cap_kepsek}`;
    });
}
</script>

@endsection



