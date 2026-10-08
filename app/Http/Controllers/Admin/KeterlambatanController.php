<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keterlambatan;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class KeterlambatanController extends Controller
{
    // Tampilkan daftar keterlambatan / izin kelas
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $jenis = $request->input('jenis'); // 'masuk' | 'keluar' | null (semua)

        $query = Keterlambatan::with('siswa')->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc');

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }
        if ($jenis) {
            $query->where('jenis', $jenis);
        }

        $keterlambatans = $query->get();

        return view('admin.keterlambatan.index', compact('keterlambatans', 'tanggal', 'jenis'));
    }

    // Form tambah data manual oleh admin (dianggap jenis 'masuk')
    public function create()
    {
        $siswas = Siswa::orderBy('nama_lengkap', 'asc')->get();
        return view('admin.keterlambatan.create', compact('siswas'));
    }

    // Simpan data keterlambatan baru (input manual admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|exists:siswas,nis',
            'nama_siswa' => 'required|string|max:255',
            'tanggal' => 'nullable|date',
            'jam_datang' => 'nullable',
            'menit_terlambat' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
            'status' => 'nullable|in:terima,menunggu,pending,tolak',
            'dokumen' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ], [
            'nis.required' => 'Silakan pilih siswa terlebih dahulu.',
            'nis.exists' => 'NIS siswa tidak ditemukan.',
            'menit_terlambat.required' => 'Menit keterlambatan wajib diisi.',
            'menit_terlambat.numeric' => 'Menit keterlambatan harus berupa angka.',
        ]);

        $tanggalSekarang = $request->tanggal ?? Carbon::now()->format('Y-m-d');
        $jamSekarang = $request->jam_datang ?? Carbon::now()->format('H:i:s');
        $siswa = Siswa::where('nis', $validated['nis'])->first();

        // "pending" di dropdown lama disamakan ke 'menunggu' yang dipakai di seluruh sistem.
        $status = $request->status ?? 'terima';
        if ($status === 'pending') {
            $status = 'menunggu';
        }

        $filename = null;
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/dokumen_izin'), $filename);
        }

        try {
            Keterlambatan::create([
                'nis' => $validated['nis'],
                'jenis' => 'masuk',
                'nama_siswa' => $validated['nama_siswa'],
                'rombel' => $siswa->rombel ?? null,
                'no_absen' => '-',
                'tanggal' => $tanggalSekarang,
                'jam_datang' => $jamSekarang,
                'jam_ke' => '-',
                'menit_terlambat' => $validated['menit_terlambat'],
                'keterangan' => $validated['keterangan'] ?? '-',
                'dokumen' => $filename,
                'status' => $status,
            ]);

            return redirect()->route('admin.keterlambatan.index')
                ->with('success', '✅ Data keterlambatan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '❌ Gagal menambahkan data keterlambatan: ' . $e->getMessage());
        }
    }

    // Cetak surat (pilih template sesuai jenis)
    public function cetakSurat($id)
    {
        $data = Keterlambatan::with('siswa')->findOrFail($id);

        $total_terlambat = Keterlambatan::where('nis', $data->nis)
            ->where('jenis', $data->jenis)
            ->where('status', 'terima')
            ->count();

        $view = $data->jenis === 'keluar' ? 'admin.keterlambatan.surat_keluar' : 'admin.keterlambatan.surat_masuk';

        $pdf = Pdf::loadView($view, compact('data', 'total_terlambat'))->setPaper('A5', 'portrait');

        return $pdf->stream('Surat_Izin_' . $data->nama_siswa . '.pdf');
    }

    public function updateStatus(Request $request, $id)
    {
        $keterlambatan = Keterlambatan::findOrFail($id);
        $keterlambatan->status = $request->status;
        $keterlambatan->save();

        return back()->with('success', 'Status pengajuan berhasil diperbarui!');
    }

    /**
     * 🔹 Rekap bulanan izin masuk/keluar kelas per siswa.
     * Siswa dengan >=2 izin MASUK (terlambat) yang disetujui dalam bulan itu ditandai.
     */
    public function rekapBulanan(Request $request)
    {
        $rombelList = Siswa::select('rombel')->whereNotNull('rombel')->distinct()->orderBy('rombel')->pluck('rombel');

        $rombel = $request->query('rombel');
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $query = Keterlambatan::where('status', 'terima')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun);

        if ($rombel) {
            $query->where('rombel', $rombel);
        }

        $data = $query->get()->groupBy('nis');

        $siswaList = Siswa::when($rombel, fn ($q) => $q->where('rombel', $rombel))
            ->orderBy('rombel')->orderBy('nama_lengkap')->get();

        $rekap = [];
        foreach ($siswaList as $s) {
            $records = $data->get($s->nis, collect());
            $rekap[$s->nis] = [
                'masuk'  => $records->where('jenis', 'masuk')->count(),
                'keluar' => $records->where('jenis', 'keluar')->count(),
            ];
        }

        return view('admin.keterlambatan.rekap-bulanan', compact('rombelList', 'rombel', 'bulan', 'tahun', 'siswaList', 'rekap'));
    }
}
