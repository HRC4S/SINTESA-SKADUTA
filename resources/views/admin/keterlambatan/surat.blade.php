<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keterlambatan</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            line-height: 1.6;
        }
        /* Style untuk Konten Surat Keterlambatan */
        .content-surat { 
            margin: 0 50px;
        }
        .footer-surat {
            text-align: right;
            margin-top: 40px;
        }
        
        /* ===== STYLE KOP SURAT BARU (DISESUAIKAN DARI KARTU PELAJAR) ===== */
        .header-kop {
            text-align: center;
            border-bottom: 3px solid black; /* Garis tebal di bawah kop */
            padding-bottom: 5px;
            margin-bottom: 20px;
            position: relative;
            font-family: Arial, sans-serif;
            color: #000;
        }
        .header-kop .title-utama { 
            font-size: 16px; 
            font-weight: bold; 
            margin-top: -5px; 
        }
        .header-kop .title-pendukung { 
            font-size: 12px; 
            line-height: 1.2; 
        }
        
        .logo-left,
        .logo-right {
            position: absolute;
            top: 5px;
            width: 70px; /* Diperbesar agar jelas di dokumen A4 */
            height: 70px;
        }
        .logo-left { left: 50px; }
        .logo-right { right: 50px; }
        .header-kop img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }
        .aksara-jawa-kop img {
            width: 200px; /* Ukuran Aksara Jawa di dokumen A4 */
            height: auto;
            display: block;
            margin: 5px auto;
        }
        .alamat-kop {
            font-size: 11px;
            line-height: 1.2;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    @php
    use Carbon\Carbon;
    // 1. Pastikan Carbon menggunakan locale Bahasa Indonesia
    Carbon::setLocale('id');

    // 2. Tentukan Bulan untuk Footer (kode ini tidak mempengaruhi kop)
    $bulanFooter = request('bulan');
    
    if (!empty($bulanFooter)) {
        // Coba parsing bulan (misalnya jika inputnya angka '8' atau nama Inggris 'August')
        try {
            $date = Carbon::parse($bulanFooter);
            $bulanIndonesia = $date->translatedFormat('F');
        } catch (\Exception $e) {
            // Fallback jika parsing gagal (misalnya jika inputnya sudah nama Indonesia 'Agustus')
            $bulanIndonesia = $bulanFooter; 
        }
    } else {
        // Default ke bulan saat ini jika tidak ada request
        $bulanIndonesia = Carbon::now()->translatedFormat('F'); 
    }

    $tahunFooter = request('tahun') ?? date('Y');
    @endphp

{{-- Ganti seluruh blok <div class="header-kop"> ... </div> --}}

<div class="header-kop">
    
    @php
        // Definisikan path gambar di PHP (Anda mungkin perlu menambahkan ini di Controller atau di dalam blok Blade @php)
        $logoJogjaPath = public_path('images/jogja.png');
        $logoSmkPath = public_path('images/skaduta_logo.png');
        $aksaraJawaPath = public_path('images/aksara_jawa_black.png');

        // Fungsi helper untuk Base64 Encoding
        // Digunakan untuk memastikan gambar muncul di PDF
        function getBase64Image($path) {
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                return 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
            return ''; // Kembalikan string kosong jika file tidak ditemukan
        }
    @endphp

    <div class="logo-left">
        {{-- Gunakan Base64 Encoding untuk logo DIY --}}
        <img src="{{ getBase64Image($logoJogjaPath) }}" alt="Logo DIY">
    </div>
    <div class="logo-right">
        {{-- Gunakan Base64 Encoding untuk logo SMK --}}
        <img src="{{ getBase64Image($logoSmkPath) }}" alt="Logo SMK">
    </div>
    
    <p class="title-pendukung">
        PEMERINTAH DAERAH DAERAH ISTIMEWA YOGYAKARTA<br>
        DINAS PENDIDIKAN, PEMUDA, DAN OLAHRAGA<br>
        BALAI PENDIDIKAN MENENGAH KOTA YOGYAKARTA
    </p>
    <p class="title-utama">SMK NEGERI 2 YOGYAKARTA</p>
    
    <div class="aksara-jawa-kop">
        {{-- Gunakan Base64 Encoding untuk Aksara Jawa --}}
        <img src="{{ getBase64Image($aksaraJawaPath) }}" alt="Aksara Jawa">
    </div>
    
    <p class="alamat-kop">
        Jl. P.Mangkubumi / AM.Sangaji 47 55233 Telp. (0274) 513490 Fax. (0274) 512639<br>
        Pos-el: info@smk2-yk.sch.id | www.smk2-yk.sch.id
    </p>
</div>

{{-- Judul dan Konten Surat selanjutnya... --}}
    
    {{-- JUDUL DITEMPATKAN DI BAWAH KOP --}}
    <h2 style="text-align:center; margin-top: 20px;">SURAT KETERLAMBATAN</h2>
    
    <div class="content-surat">
        <p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>
        <table style="margin-left: 20px;">
            <tr><td>Nama Siswa</td><td>:</td><td>{{ $data->nama_siswa }}</td></tr>
            <tr><td>NIS</td><td>:</td><td>{{ $data->nis }}</td></tr>
            <tr><td>Tanggal</td><td>:</td><td>{{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }}</td></tr>
            <tr><td>Jam Datang</td><td>:</td><td>{{ $data->jam_datang }}</td></tr>
            <tr><td>Menit Terlambat</td><td>:</td><td>{{ $data->menit_terlambat }} menit</td></tr>
            <tr><td>Keterangan</td><td>:</td><td>{{ $data->keterangan }}</td></tr>
        </table>

        <p style="margin-top: 20px;">Demikian surat keterangan keterlambatan ini dibuat untuk digunakan sebagaimana mestinya.</p>

        <div class="footer-surat">
            <p>Yogyakarta, {{ now()->translatedFormat('d F Y') }}</p>
            <p>Guru BK</p>
            <br><br>
            <p><strong>________________________</strong></p>
        </div>
    </div>
</body>
</html>