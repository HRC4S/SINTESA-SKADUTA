<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Permohonan Meninggalkan Pelajaran</title>
    <style>
        @page { margin: 12mm 10mm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 10.5px; line-height: 1.4; color: #000; }

        .kode-form {
            position: absolute; top: 0; right: 0; width: 95px;
            border: 1px solid #000; text-align: center; font-size: 8.5px;
        }
        .kode-form div { padding: 2px; }
        .kode-form div:first-child { border-bottom: 1px solid #000; }

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
        .form-table td { padding: 4px 0; vertical-align: top; }
        .label-col { width: 110px; text-transform: uppercase; }
        .titik-dua { width: 10px; }
        .isi-col { border-bottom: 1px dotted #000; }

        .footer-surat { margin-top: 22px; width: 100%; }
        .tgl-kota { text-align: right; }
        .siswa-block { text-align: right; margin-top: 4px; }
        .ttd-space { height: 34px; }
        .garis-ttd { border-bottom: 1px dotted #000; width: 55%; margin-left: auto; }

        .approve-table { width: 100%; margin-top: 18px; border-collapse: collapse; }
        .approve-table td { vertical-align: top; width: 50%; text-align: center; padding-top: 6px; }
        .approve-garis { border-bottom: 1px dotted #000; width: 80%; margin: 28px auto 0; }

        .catatan { margin-top: 18px; font-size: 8.5px; }
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

        if (! function_exists('getBase64ImageSuratKeluar')) {
            function getBase64ImageSuratKeluar($path) {
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    return 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($path));
                }
                return '';
            }
        }
    @endphp

    <div class="header-kop">
        <div class="logo-left"><img src="{{ getBase64ImageSuratKeluar($logoJogjaPath) }}" alt="Logo DIY"></div>
        <div class="logo-right"><img src="{{ getBase64ImageSuratKeluar($logoSmkPath) }}" alt="Logo SMK"></div>

        <p class="title-pendukung">
            PEMERINTAH DAERAH DAERAH ISTIMEWA YOGYAKARTA<br>
            DINAS PENDIDIKAN, PEMUDA, DAN OLAHRAGA
        </p>
        <p class="title-utama">SMKN 2 YOGYAKARTA</p>

        <div class="aksara-jawa-kop">
            <img src="{{ getBase64ImageSuratKeluar($aksaraJawaPath) }}" alt="Aksara Jawa">
        </div>

        <p class="alamat-kop">
            Jalan P. Mangkubumi / AM. Sangaji 47 Telepon (0274) 513490 Faksimile (0274) 512639 Yogyakarta<br>
            Website: www.smk2-yk.sch.id  E-mail: info@smk2-yk.sch.id  Kode Pos 55233
        </p>
    </div>
</div>

<div class="judul-surat">Permohonan Meninggalkan Pelajaran</div>

<table class="form-table">
    <tr>
        <td class="label-col">Nama</td>
        <td class="titik-dua">:</td>
        <td class="isi-col">{{ $data->nama_siswa }}</td>
    </tr>
    <tr>
        <td class="label-col">Kelas</td>
        <td class="titik-dua">:</td>
        <td class="isi-col">
            {{ $data->rombel ?? ($data->siswa->rombel ?? '-') }}
            <span style="margin-left: 30px;">No. Absen {{ $data->no_absen ?? '-' }}</span>
        </td>
    </tr>
    <tr>
        <td class="label-col">Jam Ke</td>
        <td class="titik-dua">:</td>
        <td class="isi-col">{{ $data->jam_ke ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label-col" style="padding-top: 10px;">Kepentingan</td>
        <td class="titik-dua" style="padding-top: 10px;">:</td>
        <td class="isi-col" style="padding-top: 10px;">{{ $data->keterangan }}</td>
    </tr>
    <tr>
        <td></td>
        <td class="titik-dua">:</td>
        <td class="isi-col">&nbsp;</td>
    </tr>
</table>

<div class="footer-surat">
    <div class="tgl-kota">Yogyakarta, {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }}</div>
    <div class="siswa-block">Siswa</div>
    <div class="ttd-space"></div>
    <div class="garis-ttd"></div>
</div>

<table class="approve-table">
    <tr>
        <td colspan="2">Menyetujui,</td>
    </tr>
    <tr>
        <td>Guru Mata Diklat</td>
        <td>Guru Piket</td>
    </tr>
    <tr>
        <td><div class="approve-garis"></div></td>
        <td><div class="approve-garis"></div></td>
    </tr>
</table>

<p class="catatan">Nb: Putih untuk Guru Mapel</p>

</body>
</html>
