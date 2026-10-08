<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Permohonan Mengikuti Pelajaran</title>
    <style>
        @page { margin: 12mm 10mm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 10.5px; line-height: 1.4; color: #000; }

        .kode-form {
            position: absolute; top: 0; right: 0; width: 95px;
            border: 1px solid #000; text-align: center;  
        }
        .kode-form div { padding: 2px; }
        .kode-form div:first-child { border-bottom: 1px solid #000; }

        .total-terlambat {
            position: absolute; margin-top: 15px; width: 120px; font-size: 9px; font-weight: bold;
        }

        .header-kop { text-align: center; border-bottom: 2px solid black; padding-bottom: 4px; margin-bottom: 10px; position: relative; font-family: Arial, sans-serif; color: #000; }
        .header-kop .title-pendukung { font-size: 8.5px; line-height: 1.15; }
        .header-kop .title-utama { font-size: 11.5px; font-weight: bold; margin-top: -2px; }
        .logo-left, .logo-right { position: absolute; top: 3px; width: 40px; height: 40px; }
        .logo-left { left: 25px; } .logo-right { right: 25px; }
        .header-kop img { width: 100%; height: 100%; object-fit: contain; display: block; }
        .aksara-jawa-kop img { width: 115px; height: auto; display: block; margin: 3px auto; }
        .alamat-kop { font-size: 7.5px; line-height: 1.15; margin-top: 3px; }

        .judul-surat { text-align: center; font-weight: bold; font-size: 11.5px; text-decoration: underline; margin: 12px 0 16px; text-transform: uppercase; }

        .form-table { width: 100%; margin-left: 5px; border-collapse: collapse; }
        .form-table td { padding: 3px 0; vertical-align: top; }
        .label-col { width: 150px; }
        .titik-dua { width: 10px; }
        .isi-col { border-bottom: 1px dotted #000; }

        .garis-alasan { border-bottom: 1px dotted #000; height: 16px; margin-top: 3px; }

        .footer-surat { margin-top: 40px; width: 100%; }
        .footer-surat td { vertical-align: top; width: 50%; text-align: center; }
        .tgl-kota { text-align: right; margin-bottom: 16px; }
        .ttd-space { height: 38px; }
        .garis-ttd { border-bottom: 1px dotted #000; width: 80%; margin: 0 auto; }
    </style>
</head>
<body>

<div style="position: relative;">
    <div class="kode-form">
        <div>F/83/Waka 3/2</div>
        <div>1 Tahun</div>
    </div>

    @php
        $logoJogjaPath = public_path('images/jogja.png');
        $logoSmkPath = public_path('images/skaduta_logo.png');
        $aksaraJawaPath = public_path('images/aksara_jawa_black.png');

        if (! function_exists('getBase64ImageSuratMasuk')) {
            function getBase64ImageSuratMasuk($path) {
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    return 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($path));
                }
                return '';
            }
        }
    @endphp

    <div class="header-kop">
        <div class="logo-left"><img src="{{ getBase64ImageSuratMasuk($logoJogjaPath) }}" alt="Logo DIY"></div>
        <p class="title-pendukung">
            PEMERINTAH DAERAH DAERAH ISTIMEWA YOGYAKARTA<br>
            DINAS PENDIDIKAN, PEMUDA, DAN OLAHRAGA<br>
            BALAI PENDIDIKAN MENENGAH KOTA YOGYAKARTA
        </p>
        <p class="title-utama">SMKN 2 YOGYAKARTA</p>

        <div class="aksara-jawa-kop">
            <img src="{{ getBase64ImageSuratMasuk($aksaraJawaPath) }}" alt="Aksara Jawa">
        </div>

        <p class="alamat-kop">
            Jl. P.Mangkubumi / AM.Sangaji 47 Yogyakarta 55233 Telepon (0274) 513490, Faksimile (0274) 512639<br>
            Website: www.smk2-yk.sch.id  Email: info@smk2-yk.sch.id
        </p>
    </div>
</div>

<div class="judul-surat">Permohonan Mengikuti Pelajaran</div>

<table class="form-table">
    <tr>
        <td class="label-col">Nama</td>
        <td class="titik-dua">:</td>
        <td class="isi-col">{{ $data->nama_siswa }}</td>
    </tr>
    <tr>
        <td class="label-col">Kelas</td>
        <td class="titik-dua">:</td>
        <td class="isi-col" style="width:45%;">
            {{ $data->rombel ?? ($data->siswa->rombel ?? '-') }}
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="margin-left: 20px;">NIS : {{ $data->nis }}</span>
        </td>
    </tr>
    <tr>
        <td class="label-col">Absen</td>
        <td class="titik-dua">:</td>
        <td class="isi-col">{{ $data->no_absen ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label-col">Mulai mengikuti pelajaran jam ke</td>
        <td class="titik-dua">:</td>
        <td class="isi-col">{{ $data->jam_ke ?? '-' }} &nbsp;&nbsp;&nbsp;&nbsp; s/d selesai</td>
    </tr>
    <tr>
        <td class="label-col" style="vertical-align: top; padding-top: 10px;">Karena</td>
        <td class="titik-dua" style="vertical-align: top; padding-top: 10px;">:</td>
        <td style="padding-top: 10px;">{{ $data->keterangan }}</td>
    </tr>
</table>

<div class="garis-alasan"></div>
<div class="garis-alasan"></div>

<div class="total-terlambat">Total Keterlambatan: {{ $total_terlambat }} kali</div>

<table class="footer-surat">
    <tr>
        <td colspan="2" class="tgl-kota">Yogyakarta, {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }}</td>
    </tr>
    <tr>
        <td>Mengetahui,<br>Guru BK</td>
        <td>Siswa</td>
    </tr>
    <tr>
        <td class="ttd-space"></td>
        <td class="ttd-space"></td>
    </tr>
    <tr>
        <td><div class="garis-ttd"></div></td>
        <td><div class="garis-ttd"></div></td>
    </tr>
</table>

</body>
</html>
