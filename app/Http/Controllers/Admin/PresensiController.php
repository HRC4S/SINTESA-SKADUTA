<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\PresensiBulananExport;
use App\Exports\PresensiTotalExport;
use App\Models\Presensi;
use App\Models\PresensiSesi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PresensiController extends Controller
{
    /**
     * 🔹 Dashboard: daftar semua kelas, status hari ini (sudah/belum input), dan siapa koor-nya.
     */
    public function dashboard(Request $request)
    {
        $tanggal = $request->query('tanggal', now()->toDateString());

        $rombelList = Siswa::select('rombel')
            ->whereNotNull('rombel')
            ->distinct()
            ->orderBy('rombel')
            ->pluck('rombel');

        // Koor per rombel (kalau ada lebih dari satu karena data lama, ambil salah satu)
        $koorPerRombel = Siswa::where('role', 'koor_kelas')
            ->get()
            ->groupBy('rombel');

        // Sesi yang sudah diinput untuk tanggal ini
        $sesiHariIni = PresensiSesi::where('tanggal', $tanggal)
            ->with('koor')
            ->get()
            ->keyBy('rombel');

        $data = $rombelList->map(function ($rombel) use ($koorPerRombel, $sesiHariIni) {
            $koor = optional($koorPerRombel->get($rombel))->first();
            $sesi = $sesiHariIni->get($rombel);

            return [
                'rombel'        => $rombel,
                'koor_nama'     => $koor->nama_lengkap ?? null,
                'koor_nis'      => $koor->nis ?? null,
                'sudah_diinput' => (bool) $sesi,
                'sesi_id'       => $sesi->id ?? null,
                'diinput_oleh'  => $sesi->koor->nama_lengkap ?? null,
                'diedit_oleh'   => $sesi->diedit_oleh ?? null,
            ];
        });

        return view('admin.presensi.dashboard', compact('data', 'tanggal'));
    }

    /**
     * 🔹 Detail + form edit presensi 1 kelas pada 1 tanggal.
     * Kalau belum ada sesi sama sekali, admin bisa input dari nol.
     */
    public function kelas(Request $request, string $rombel)
    {
        $tanggal = $request->query('tanggal', now()->toDateString());

        $sesi = PresensiSesi::where('rombel', $rombel)->where('tanggal', $tanggal)->first();

        $siswaSekelas = Siswa::where('rombel', $rombel)->orderBy('nama_lengkap')->get();

        $existing = [];
        if ($sesi) {
            $existing = Presensi::where('presensi_sesi_id', $sesi->id)->get()->keyBy('nis');
        }

        return view('admin.presensi.kelas', compact('rombel', 'tanggal', 'sesi', 'siswaSekelas', 'existing'));
    }

    /**
     * 🔹 Simpan (buat baru ATAU update) presensi 1 kelas pada 1 tanggal.
     * Admin/Kesiswaan TIDAK terkena kunci "sekali input" milik koor.
     */
    public function simpan(Request $request, string $rombel)
    {
        $request->validate([
            'tanggal'  => ['required', 'date'],
            'status'   => ['required', 'array'],
            'status.*' => ['required', 'in:hadir,sakit,izin,alpa'],
        ]);

        $tanggal = $request->tanggal;
        $guru = Auth::guard('guru')->user();
        $pengedit = trim(($guru->role ?? 'admin') . ': ' . ($guru->nama ?? $guru->nip ?? '-'));

        $nisSekelas = Siswa::where('rombel', $rombel)->pluck('nis')->all();
        $statusInput = $request->status;

        $invalidNis = array_diff(array_keys($statusInput), $nisSekelas);
        if (! empty($invalidNis)) {
            return back()->with('error', 'Data tidak valid: ada siswa di luar kelas ini.');
        }

        DB::transaction(function () use ($rombel, $tanggal, $pengedit, $statusInput) {
            $sesi = PresensiSesi::firstOrNew([
                'rombel'  => $rombel,
                'tanggal' => $tanggal,
            ]);

            $isBaru = ! $sesi->exists;

            if ($isBaru) {
                $sesi->koor_nis = null; // diinput langsung oleh admin/kesiswaan, bukan koor
            }

            $sesi->diedit_oleh = $pengedit;
            $sesi->diedit_at = now();
            $sesi->save();

            foreach ($statusInput as $nis => $status) {
                Presensi::updateOrCreate(
                    ['presensi_sesi_id' => $sesi->id, 'nis' => $nis],
                    ['rombel' => $rombel, 'tanggal' => $tanggal, 'status' => $status]
                );
            }
        });

        return redirect()->route('admin.presensi.kelas', ['rombel' => $rombel, 'tanggal' => $tanggal])
            ->with('success', 'Presensi kelas ' . $rombel . ' berhasil disimpan.');
    }

    /**
     * 🔹 Rekap bulanan per kelas: grid siswa x tanggal + total per siswa.
     */
    public function rekapBulanan(Request $request)
    {
        $rombelList = Siswa::select('rombel')->whereNotNull('rombel')->distinct()->orderBy('rombel')->pluck('rombel');

        $rombel = $request->query('rombel', $rombelList->first());
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $d = $this->buildRekapBulananData($rombel, $bulan, $tahun);

        return view('admin.presensi.rekap-bulanan', array_merge($d, compact('rombelList', 'rombel', 'bulan', 'tahun')));
    }

    /**
     * 🔹 Export rekap bulanan ke Excel (.xlsx).
     */
    public function exportRekapBulanan(Request $request)
    {
        $rombelList = Siswa::select('rombel')->whereNotNull('rombel')->distinct()->orderBy('rombel')->pluck('rombel');

        $rombel = $request->query('rombel', $rombelList->first());
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $d = $this->buildRekapBulananData($rombel, $bulan, $tahun);

        $namaBulan = \Carbon\Carbon::create()->month($bulan)->translatedFormat('F');
        $fileName = 'Rekap-Presensi-' . str_replace(' ', '-', $rombel) . '-' . $namaBulan . '-' . $tahun . '.xlsx';

        return Excel::download(
            new PresensiBulananExport($rombel, $bulan, $tahun, $d['siswaSekelas'], $d['tanggalList'], $d['grid'], $d['totalPerSiswa']),
            $fileName
        );
    }

    /**
     * Bangun data grid rekap bulanan (dipakai bareng oleh view & export biar hasilnya selalu sama).
     */
    private function buildRekapBulananData(string $rombel, int $bulan, int $tahun): array
    {
        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        $tanggalList = range(1, $jumlahHari);

        $siswaSekelas = Siswa::where('rombel', $rombel)->orderBy('nama_lengkap')->get();

        $presensiBulanIni = Presensi::where('rombel', $rombel)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get()
            ->groupBy('nis');

        $grid = [];
        $totalPerSiswa = [];
        foreach ($siswaSekelas as $siswa) {
            $records = $presensiBulanIni->get($siswa->nis, collect());
            $baris = [];
            foreach ($records as $r) {
                $baris[(int) $r->tanggal->format('j')] = $r->status;
            }
            $grid[$siswa->nis] = $baris;

            $totalPerSiswa[$siswa->nis] = [
                'hadir' => $records->where('status', 'hadir')->count(),
                'sakit' => $records->where('status', 'sakit')->count(),
                'izin'  => $records->where('status', 'izin')->count(),
                'alpa'  => $records->where('status', 'alpa')->count(),
            ];
        }

        return compact('tanggalList', 'siswaSekelas', 'grid', 'totalPerSiswa');
    }

    /**
     * 🔹 Rekap total (semua kelas atau 1 kelas) tanpa batas bulan tertentu —
     * bisa dipersempit dengan rentang tanggal lewat query ?dari=&sampai=.
     */
    public function rekapTotal(Request $request)
    {
        $rombelList = Siswa::select('rombel')->whereNotNull('rombel')->distinct()->orderBy('rombel')->pluck('rombel');
        $rombel = $request->query('rombel'); // kosong = semua kelas
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');

        $d = $this->buildRekapTotalData($rombel, $dari, $sampai);

        return view('admin.presensi.rekap-total', array_merge($d, compact('rombelList', 'rombel', 'dari', 'sampai')));
    }

    /**
     * 🔹 Export rekap total ke Excel (.xlsx).
     */
    public function exportRekapTotal(Request $request)
    {
        $rombel = $request->query('rombel');
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');

        $d = $this->buildRekapTotalData($rombel, $dari, $sampai);

        $namaFile = 'Rekap-Total-Presensi' . ($rombel ? '-' . str_replace(' ', '-', $rombel) : '-SemuaKelas') . '.xlsx';

        return Excel::download(
            new PresensiTotalExport($d['siswaList'], $d['rekap'], $rombel, $dari, $sampai),
            $namaFile
        );
    }

    /**
     * Bangun data rekap total (dipakai bareng oleh view & export biar hasilnya selalu sama).
     */
    private function buildRekapTotalData(?string $rombel, ?string $dari, ?string $sampai): array
    {
        $query = Presensi::query();

        if ($rombel) {
            $query->where('rombel', $rombel);
        }
        if ($dari) {
            $query->whereDate('tanggal', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('tanggal', '<=', $sampai);
        }

        $rekap = $query
            ->select('nis', 'rombel',
                DB::raw("SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as total_hadir"),
                DB::raw("SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) as total_sakit"),
                DB::raw("SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as total_izin"),
                DB::raw("SUM(CASE WHEN status = 'alpa' THEN 1 ELSE 0 END) as total_alpa"),
                DB::raw('COUNT(*) as total_hari')
            )
            ->groupBy('nis', 'rombel')
            ->get()
            ->keyBy('nis');

        $siswaList = Siswa::when($rombel, fn ($q) => $q->where('rombel', $rombel))
            ->orderBy('rombel')
            ->orderBy('nama_lengkap')
            ->get();

        return compact('rekap', 'siswaList');
    }
}
