@extends('layouts.admin')
@section('title', 'Kartu Pelajar')
@section('page_title', isset($siswas) ? 'Preview Kartu Pelajar Massal' : 'Preview Kartu Pelajar')

@section('content')

{{-- 1. LOAD FONT AWESOME VERSI 6 --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

/* ====== PERBAIKAN UTAMA DISINI ====== */
/* Terapkan font Poppins ke container, TAPI JANGAN ke tag <i> (ikon) */
.preview-container {
    font-family: 'Poppins', sans-serif !important;
}

/* Selector ini memastikan teks biasa kena Poppins, tapi ikon FontAwesome dibiarkan */
.preview-container *:not(i) { 
    font-family: 'Poppins', sans-serif !important;
}

.preview-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 1rem;
    padding: 10px;
    width: 100%;
}

/* ====== PREVIEW FRAME ====== */
iframe.preview-frame {
    width: 100%;
    max-width: 750px;
    height: 850px;
    border: none;
    background: transparent;
    margin-bottom: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
    border-radius: 8px;
}

/* ====== AREA CETAK ====== */
@page {
    size: A4;
    margin-top: 0.5cm;
    margin-bottom: 0;
    margin-left: 0;
    margin-right: 0;
}

body { background: none !important; }

#printArea { display: none; background: none; }

.print-sheet {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 21cm;
    height: auto;
    margin: 0 auto;
    padding-top: 0.5cm;
    box-sizing: border-box;
    background: none;
}

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

.print-card iframe {
    width: 100%; height: 100%; border: none; overflow: hidden; background: transparent !important;
}

.print-card:nth-of-type(4n) { page-break-after: always; }

/* ====== TOMBOL AKSI ====== */
.action-buttons {
    display: flex; justify-content: center; flex-wrap: wrap; gap: 10px;
    margin-top: 10px; width: 100%; max-width: 750px;
}

.btn-custom {
    padding: 10px 22px;
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-family: 'Poppins', sans-serif; /* Pastikan teks tombol Poppins */
}

/* Pastikan Ikon FontAwesome (tag i) TIDAK tertimpa font-family */
.btn-custom i {
    font-family: "Font Awesome 6 Free" !important; /* Paksa font ikon */
    font-weight: 900;
    font-style: normal;
    font-size: 1.1rem;
}

.btn-custom:hover { opacity: 0.9; color: white; }
.btn-cetak { background-color: #28a745; }
.btn-edit { background-color: #007bff; }
.btn-batal { background-color: #6c757d; }
.btn-orange { background-color: #f39c12; }

/* ====== FORM EDIT ====== */
#footerFormContainer { width: 100%; display: flex; justify-content: center; }

#footerForm {
    display: inline-block; background: #f0f4ff; padding: 20px;
    border-radius: 8px; text-align: left; width: 100%; max-width: 750px;
    box-sizing: border-box; border: 1px solid #dbeafe;
}

.form-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px; margin-bottom: 20px;
}

.form-group { display: flex; flex-direction: column; }
.form-group label { font-size: 0.9rem; font-weight: 600; color: #17375d; margin-bottom: 5px; }
.form-group input { padding: 8px 12px; border-radius: 6px; border: 1px solid #ccc; font-size: 0.95rem; width: 100%; box-sizing: border-box; }

/* ====== MEDIA QUERY MOBILE ====== */
@media (max-width: 768px) {
    iframe.preview-frame { height: 600px; }
    .action-buttons { flex-direction: column; }
    .btn-custom { width: 100%; margin: 0; }
    .form-grid { grid-template-columns: 1fr; }
    .container { padding-left: 10px; padding-right: 10px; }
}

/* ====== CETAK KHUSUS ====== */
@media print {
    html, body { margin: 0; padding: 0; background: none !important; }
    body * { visibility: hidden !important; background: none !important; }
    #printArea, #printArea * { visibility: visible !important; background: none !important; }
    
    #printArea {
        display: block !important; position: absolute; left: 0; top: 0.5cm;
        width: 100%; margin: 0; padding: 0;
    }
    iframe { overflow: hidden !important; background: transparent !important; print-color-adjust: exact !important; }
    .noprint { display: none !important; }
    @page { margin-top: 0.5cm; background: none; }
}
</style>

<div class="container py-3">

    <div class="noprint text-center mb-3">
        <p class="text-muted" style="font-size: 0.95rem;">
            {{ isset($siswas) ? 'Preview kartu pelajar sebelum cetak massal' : 'Preview Kartu Pelajar' }}
        </p>
    </div>

    <div class="preview-container noprint">
    
    @if(isset($siswas))
        {{-- === CETAK MASSAL === --}}
        @foreach($siswas as $siswa)
            <iframe class="preview-frame" src="{{ route('admin.kartupelajar.frame', $siswa->nis) }}"></iframe>
            <hr style="width:100%; max-width:750px; border:1px dashed #ccc; margin: 20px 0;">
        @endforeach

        <div class="action-buttons">
            <button onclick="window.print()" class="btn-custom btn-cetak">
                <i class="fa-solid fa-print"></i> Cetak Semua
            </button>

            @unless(auth('guru')->user()->role === 'kesiswaan')
                <button type="button" class="btn-custom btn-edit" onclick="toggleFooterForm()">
                    <i class="fa-solid fa-cog"></i> Edit Data Kartu
                </button>
            @endunless

            <a href="{{ route('admin.kartupelajar.index') }}" class="btn-custom btn-batal">
                <i class="fa-solid fa-times"></i> Kembali
            </a>
        </div>

   @else
    {{-- === CETAK SATUAN === --}}
    <iframe class="preview-frame" id="kartuFrame" src="{{ route('admin.kartupelajar.frame', $siswa->nis) }}"></iframe>

    <div class="action-buttons">
        
        <button onclick="document.getElementById('kartuFrame').contentWindow.print()" class="btn-custom btn-cetak">
            <i class="fa-solid fa-print"></i> Cetak
        </button>

        @unless(auth('guru')->user()->role === 'kesiswaan')
            <a href="{{ route('admin.datasiswa.edit', $siswa->nis) }}" class="btn-custom btn-orange">
                <i class="fa-solid fa-user-pen"></i> Edit Siswa
            </a>

            <button type="button" class="btn-custom btn-edit" onclick="toggleFooterForm()">
                <i class="fa-solid fa-cog"></i> Edit Data Kartu
            </button>
        @endunless

        <a href="{{ route('admin.kartupelajar.index') }}" class="btn-custom btn-batal">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>

    </div>
@endif


{{-- FORM EDIT DATA KARTU --}}
@unless(auth('guru')->user()->role === 'kesiswaan')
<div id="footerFormContainer" style="display:none; margin-top:20px;">
    <form id="footerForm" onsubmit="return updateFrameFooter();">
        <h4 style="color:#17375d; margin-bottom:15px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
            <i class="fa-solid fa-pen-to-square"></i> Edit Atribut Kartu
        </h4>
        <div class="form-grid">
            <div class="form-group">
                <label>Bulan:</label>
                <input type="text" name="bulan" id="bulan" value="{{ date('F') }}" required>
            </div>
            <div class="form-group">
                <label>Tahun:</label>
                <input type="number" name="tahun" id="tahun" value="{{ date('Y') }}" required>
            </div>
            <div class="form-group">
                <label>Nama Kepala Sekolah:</label>
                <input type="text" name="nama_kepsek" id="nama_kepsek" placeholder="Nama Kepsek" required>
            </div>
            <div class="form-group">
                <label>NIP:</label>
                <input type="text" name="nip" id="nip" placeholder="NIP" required>
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Gambar Cap & TTD:</label>
                <input type="file" id="cap_kepsek" accept="image/*" style="background: #fff;">
                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah.</small>
            </div>
        </div>
        <div style="margin-top: 15px; text-align:center; display: flex; gap: 10px; justify-content: center;">
            <button type="submit" class="btn-custom btn-cetak" style="flex:1;">
                <i class="fa-solid fa-save"></i> Simpan
            </button>
            <button type="button" class="btn-custom btn-batal" onclick="toggleFooterForm()" style="flex:1;">
                Batal
            </button>
        </div>
    </form>
</div>
@endunless

</div>

{{-- AREA CETAK --}}
@if(isset($siswas))
<div id="printArea">
    <div class="print-sheet">
        @foreach($siswas as $siswa)
        <div class="print-card">
            <iframe src="{{ route('admin.kartupelajar.frame', $siswa->nis) }}" scrolling="no"></iframe>
        </div>
        @endforeach
    </div>
</div>
@endif

</div>

<script>
function toggleFooterForm() {
    const form = document.getElementById('footerFormContainer');
    form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'flex' : 'none';
    if(form.style.display === 'flex') { form.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
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
    return false;
}

function refreshFrames(bulan, tahun, nama_kepsek, nip, cap_kepsek) {
    const previewFrames = document.querySelectorAll('.preview-frame');
    const printFrames = document.querySelectorAll('#printArea iframe');
    document.body.style.cursor = 'wait';
    [...previewFrames, ...printFrames].forEach(frame => {
        let src = frame.src;
        if(src.indexOf('?') !== -1) { src = src.split('?')[0]; }
        frame.src = `${src}?bulan=${encodeURIComponent(bulan)}&tahun=${encodeURIComponent(tahun)}&nama_kepsek=${encodeURIComponent(nama_kepsek)}&nip=${encodeURIComponent(nip)}&cap_kepsek=${cap_kepsek}`;
    });
    setTimeout(() => { document.body.style.cursor = 'default'; }, 500);
}
</script>

@endsection