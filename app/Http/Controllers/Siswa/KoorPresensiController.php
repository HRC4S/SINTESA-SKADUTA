<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\PresensiSesi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KoorPresensiController extends Controller
{
    /**
     * 🔹 Form input presensi untuk kelas koor yang login.
     * Kalau tanggal yang dipilih sudah pernah diinput, tampilkan
     * mode "lihat saja" (readonly) karena koor tidak boleh input ulang.
     */
    public function create(Request $request)
    {
        $koor = Auth::guard('siswa')->user();
        $rombel = $koor->rombel;

        $tanggal = $request->query('tanggal', now()->toDateString());

        // Batasi: tidak boleh input untuk tanggal di masa depan.
        if ($tanggal > now()->toDateString()) {
            $tanggal = now()->toDateString();
        }

        $sesi = PresensiSesi::where('rombel', $rombel)
            ->where('tanggal', $tanggal)
            ->with('detail')
            ->first();

        $siswaSekelas = Siswa::where('rombel', $rombel)
            ->orderBy('nama_lengkap')
            ->get();

        $sudahDiinput = (bool) $sesi;

        // Kalau sudah pernah diinput, siapkan data existing untuk ditampilkan (readonly).
        $existingStatus = [];
        if ($sesi) {
            foreach ($sesi->detail as $d) {
                $existingStatus[$d->nis] = $d->status;
            }
        }

        return view('siswa.presensi.koor-input', compact(
            'koor', 'rombel', 'tanggal', 'siswaSekelas', 'sudahDiinput', 'existingStatus', 'sesi'
        ));
    }

    /**
     * 🔹 Simpan presensi kelas (hanya bisa sekali per kelas per tanggal).
     */
    public function store(Request $request)
    {
        $koor = Auth::guard('siswa')->user();
        $rombel = $koor->rombel;

        $request->validate([
            'tanggal'            => ['required', 'date', 'before_or_equal:today'],
            'status'             => ['required', 'array'],
            'status.*'           => ['required', 'in:hadir,sakit,izin,alpa'],
        ]);

        $tanggal = $request->tanggal;

        // 🔒 Kunci: kalau kelas ini di tanggal ini sudah pernah diinput, tolak.
        $sudahAda = PresensiSesi::where('rombel', $rombel)
            ->where('tanggal', $tanggal)
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Presensi untuk kelas ' . $rombel . ' tanggal ' . $tanggal . ' sudah pernah diinput dan tidak bisa diubah lagi. Hubungi Admin/Kesiswaan kalau ada kesalahan.');
        }

        // Pastikan semua NIS yang dikirim benar-benar siswa di kelas koor ini
        // (mencegah orang mengirim NIS siswa kelas lain lewat form yang dimodifikasi).
        $nisSekelas = Siswa::where('rombel', $rombel)->pluck('nis')->all();
        $statusInput = $request->status; // ['NIS' => 'hadir'|'sakit'|'izin'|'alpa']

        $invalidNis = array_diff(array_keys($statusInput), $nisSekelas);
        if (! empty($invalidNis)) {
            return back()->with('error', 'Data tidak valid: ada siswa di luar kelas kamu.');
        }

        if (count($statusInput) !== count($nisSekelas)) {
            return back()->with('error', 'Semua siswa di kelas harus diisi statusnya.');
        }

        DB::transaction(function () use ($rombel, $tanggal, $koor, $statusInput) {
            $sesi = PresensiSesi::create([
                'rombel'   => $rombel,
                'tanggal'  => $tanggal,
                'koor_nis' => $koor->nis,
            ]);

            $rows = [];
            $now = now();
            foreach ($statusInput as $nis => $status) {
                $rows[] = [
                    'presensi_sesi_id' => $sesi->id,
                    'nis'              => $nis,
                    'rombel'           => $rombel,
                    'tanggal'          => $tanggal,
                    'status'           => $status,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];
            }

            Presensi::insert($rows);
        });

        return redirect()->route('siswa.presensi.koor.create', ['tanggal' => $tanggal])
            ->with('success', 'Presensi kelas ' . $rombel . ' tanggal ' . $tanggal . ' berhasil disimpan.');
    }

    /**
     * 🔹 Riwayat presensi yang pernah diinput koor ini.
     */
    public function riwayat()
    {
        $koor = Auth::guard('siswa')->user();

        $riwayat = PresensiSesi::where('rombel', $koor->rombel)
            ->withCount('detail')
            ->orderByDesc('tanggal')
            ->paginate(15);

        return view('siswa.presensi.koor-riwayat', compact('riwayat'));
    }
}
