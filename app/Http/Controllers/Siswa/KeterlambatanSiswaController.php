<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keterlambatan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class KeterlambatanSiswaController extends Controller
{
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();

        $jumlah = Keterlambatan::where('nis', $siswa->nis)
    ->where('jenis', 'masuk') // ganti $jenis dengan nilai/variabel jenis yang diinginkan
    ->count();
        $poin = $jumlah * 5;

        $riwayat = Keterlambatan::where('nis', $siswa->nis)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // ⚠️ Peringatan: sudah izin masuk kelas (terlambat) 2x atau lebih, disetujui, BULAN INI.
        $terlambatBulanIni = Keterlambatan::where('nis', $siswa->nis)
            ->where('jenis', 'masuk')
            ->where('status', 'terima')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $tampilkanPeringatan = $terlambatBulanIni >= 2;

        return view('siswa.keterlambatan.index', compact(
            'siswa', 'jumlah', 'poin', 'riwayat', 'terlambatBulanIni', 'tampilkanPeringatan'
        ));
    }

    public function ajukan(Request $request)
    {
        $request->validate([
            'jenis'      => ['required', 'in:masuk,keluar'],
            'tanggal'    => ['required', 'date'],
            'no_absen'   => ['required', 'string', 'max:10'],
            'jam_ke'     => ['required', 'string', 'max:50'],
            'keterangan' => ['required', 'string', 'max:255'],
            'dokumen'    => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],
        ], [
            'no_absen.required' => 'Nomor absen wajib diisi.',
            'jam_ke.required'   => 'Jam ke wajib diisi.',
        ]);

        $siswa = Auth::guard('siswa')->user();
        $filename = null;

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/dokumen_izin'), $filename);
        }

        Keterlambatan::create([
            'nis'             => $siswa->nis,
            'jenis'           => $request->jenis,
            'nama_siswa'      => $siswa->nama_lengkap,
            'rombel'          => $siswa->rombel,
            'no_absen'        => $request->no_absen,
            'tanggal'         => $request->tanggal,
            'jam_datang'      => '00:00:00', // sudah tidak dipakai untuk jenis baru, diisi default
            'jam_ke'          => $request->jam_ke,
            'menit_terlambat' => 0,
            'keterangan'      => $request->keterangan,
            'dokumen'         => $filename,
            'status'          => 'menunggu',
        ]);

        $label = $request->jenis === 'keluar' ? 'Izin meninggalkan kelas' : 'Izin memasuki kelas';

        return back()->with('success', $label . ' berhasil diajukan, menunggu persetujuan Admin/Kesiswaan.');
    }

    public function cetakSIT($id)
    {
        $siswa = Auth::guard('siswa')->user();

        $data = Keterlambatan::where('id', $id)
            ->where('nis', $siswa->nis)
            ->where('status', 'terima')
            ->firstOrFail();

        $view = $data->jenis === 'keluar' ? 'admin.keterlambatan.surat_keluar' : 'admin.keterlambatan.surat_masuk';

        $pdf = Pdf::loadView($view, compact('data'))->setPaper('A5', 'portrait');

        return $pdf->stream('Surat-Izin-' . $data->nama_siswa . '.pdf');
    }
}
