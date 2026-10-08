<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

// Import Models
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Konseling;
use App\Models\Keterlambatan;
use App\Imports\SiswaImport;

class DashboardController extends Controller
{
    /** ===============================
     * DASHBOARD ADMIN
     * =============================== */
  public function adminDashboard(Request $request)
  {
      /** ===============================
       * 1. STATISTIK UTAMA
       * =============================== */
      $totalSiswa     = Siswa::count();
      $totalAdmin     = Guru::where('role', 'admin')->count();
      $totalKonseling = Konseling::count();
      $konselingMenunggu = Konseling::whereIn('status', ['Menunggu', 'pending'])->count();

      $keterlambatanBaru = Schema::hasTable('keterlambatans')
          ? Keterlambatan::whereIn('status', ['menunggu', 'pending'])->count()
          : 0;

      /** ===============================
       * 2. PROGRESS DOKUMEN SISWA
       * =============================== */
      $sudahUpload = 0;

      if (Schema::hasTable('dokumen_siswas')) {
          $sudahUpload = DB::table('dokumen_siswas')->distinct('nis')->count('nis');
      } elseif (Schema::hasTable('dokumen_siswa')) {
          $sudahUpload = DB::table('dokumen_siswa')->distinct('nis')->count('nis');
      }

      $belumUpload = max(0, $totalSiswa - $sudahUpload);
      $persenBelum = $totalSiswa > 0
          ? number_format(($belumUpload / $totalSiswa) * 100, 1)
          : 0;

      /** ===============================
       * 3. FILTER ANGKATAN
       * =============================== */
      $filterAngkatan = $request->get('angkatan');
      $kodeTahun = $filterAngkatan ? substr($filterAngkatan, -2) : null;

      $querySiswa = Siswa::select('jurusan', DB::raw('COUNT(*) as total'));

      if ($kodeTahun) {
          $querySiswa->where('nis', 'like', $kodeTahun . '%');
      }

      $chartData = $querySiswa
          ->groupBy('jurusan')
          ->orderBy('jurusan')
          ->get();

      $angkatanList = Siswa::pluck('nis')
          ->map(fn ($nis) => 2000 + (int) substr($nis, 0, 2))
          ->unique()
          ->sortDesc()
          ->values();

      /** ===============================
       * 4. CHART PRESTASI
       * =============================== */
      $chartPrestasi = [
          'Lomba' => 0,
          'Seminar' => 0,
          'Sertifikat' => 0,
          'Lainnya' => 0
      ];

      $tahunPrestasiList = collect();

      if (Schema::hasTable('prestasis')) {

          $filterTahunPrestasi = $request->get('tahun_prestasi');

          $kolomTanggal = Schema::hasColumn('prestasis', 'tanggal_prestasi')
              ? 'tanggal_prestasi'
              : 'created_at';

          $queryPrestasi = DB::table('prestasis')
              ->select('jenis', DB::raw('COUNT(*) as total'));

          if ($filterTahunPrestasi) {
              $queryPrestasi->whereYear($kolomTanggal, $filterTahunPrestasi);
          }

          $dataPrestasi = $queryPrestasi
              ->groupBy('jenis')
              ->get();

          foreach ($dataPrestasi as $p) {
              $jenis = ucfirst(strtolower($p->jenis));

              if (array_key_exists($jenis, $chartPrestasi)) {
                  $chartPrestasi[$jenis] = $p->total;
              } else {
                  $chartPrestasi['Lainnya'] += $p->total;
              }
          }

        $tahunPrestasiList = DB::table('prestasis')
            ->pluck($kolomTanggal)
            ->filter()
            ->map(function ($tanggal) {
                return \Carbon\Carbon::parse($tanggal)->year;
            })
            ->unique()
            ->sortDesc()
            ->values();

      }

      /** ===============================
       * RETURN VIEW
       * =============================== */
      return view('admin.dashboard', compact(
          'totalSiswa',
          'totalAdmin',
          'totalKonseling',
          'konselingMenunggu',
          'keterlambatanBaru',
          'sudahUpload',
          'belumUpload',
          'persenBelum',
          'chartData',
          'angkatanList',
          'chartPrestasi',
          'tahunPrestasiList'
      ));
  }


    /** ===============================
     * DASHBOARD SISWA
     * =============================== */
    public function siswaDashboard()
    {
        return view('siswa.dashboard');
    }

    /** ===============================
     * DATA SISWA
     * =============================== */
    public function dataSiswa()
    {
        return view('admin.datasiswa.index');
    }

    public function showUploadSiswaForm()
    {
        return view('admin.datasiswa.upload_siswa');
    }

    public function importSiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new SiswaImport, $request->file('file'));

        return back()->with('success', 'Data siswa berhasil diunggah!');
    }

    /** ===============================
     * MANAJEMEN KARTU PELAJAR
     * =============================== */
    public function manajemenKartu(Request $request)
    {
        $search = $request->input('search');

        $siswas = Siswa::when($search, function ($query, $search) {
            $query->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
        })
        ->orderBy('nama_lengkap')
        ->paginate(10);

        return view('admin.kartupelajar.index', compact('siswas', 'search'));
    }

    public function kartuPelajar()
    {
        $siswas = Siswa::all();
        return view('admin.kartupelajar.index', compact('siswas'));
    }

    /** ===============================
     * MANAJEMEN LAINNYA
     * =============================== */
    public function konseling()
    {
        return view('admin.konseling.index');
    }

    public function role()
    {
        return view('admin.role.index');
    }
}