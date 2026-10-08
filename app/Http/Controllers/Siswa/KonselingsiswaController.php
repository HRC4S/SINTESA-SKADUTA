<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\OrangTua;
use App\Models\Konseling;
use App\Models\Guru;
use Carbon\Carbon;

class KonselingSiswaController extends Controller
{
    // MENAMPILKAN DAFTAR RIWAYAT
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        
        // Ambil data konseling, urutkan dari terbaru
        $konselings = Konseling::where('nis', $siswa->nis)
            ->with('guru') // Relasi ke guru agar nama guru muncul
            ->orderBy('created_at', 'desc')
            ->get();

        return view('siswa.konseling.index', compact('siswa', 'konselings'));
    }

    // FORM PENGAJUAN BARU
    public function create()
    {
        $siswa = Auth::guard('siswa')->user();
        $orangtua = OrangTua::where('nis', $siswa->nis)->first();
        
        // Ambil guru BK (Pastikan Model Guru sudah diset $table='guru')
        $guruBk = Guru::where('role', 'guru_bk')->get();
        $waliKelas = Guru::where('walikelas', $siswa->rombel)->value('nama');

        return view('siswa.konseling.create', compact('siswa', 'orangtua', 'guruBk', 'waliKelas'));
    }

    // PROSES SIMPAN DATA (STORE)
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'guru_bk_nip'      => 'required|exists:guru,nip',
            'tanggal'          => 'required|date',
            'jam_pengajuan'    => 'required',
            'jenis_layanan'    => 'required|string',
            'topik'            => 'required|string|max:255',
            'latar_belakang'   => 'required|string',
            'kegiatan_layanan' => 'required|string',
        ]);

        $siswa = Auth::guard('siswa')->user();
        $orangtua = OrangTua::where('nis', $siswa->nis)->first();

        // 2. Ambil No Telp Ortu (Cegah error jika data ortu kosong)
        $noTelp = $orangtua ? $orangtua->no_telp_ayah : '-';

        // 3. Simpan ke Database
        Konseling::create([
            'nis'              => $siswa->nis,
            'nama_siswa'       => $siswa->nama_lengkap,
            'kelas'            => $siswa->rombel,
            'nama_ortu'        => $siswa->nama_ortu ?? '-',
            'alamat_ortu'      => $siswa->alamat ?? '-',
            'no_telp_ortu'     => $noTelp,
            
            // Data Inputan Form
            'guru_bk_nip'      => $request->guru_bk_nip,
            'tanggal'          => $request->tanggal,
            'jam_pengajuan'    => $request->jam_pengajuan,
            'jenis_layanan'    => $request->jenis_layanan,
            'topik'            => $request->topik,
            'latar_belakang'   => $request->latar_belakang,
            'kegiatan_layanan' => $request->kegiatan_layanan,
            
            'status'           => 'Menunggu',
            'created_at'       => Carbon::now(),
        ]);

        return redirect()->route('siswa.konseling.index')
            ->with('success', 'Pengajuan konseling berhasil dikirim.');
    }

    // LIHAT DETAIL (SHOW)
    public function show($id)
    {
        $siswa = Auth::guard('siswa')->user();
        // Pastikan hanya bisa lihat punya sendiri
        $konseling = Konseling::where('id', $id)->where('nis', $siswa->nis)->firstOrFail();

        return view('siswa.konseling.show', compact('siswa', 'konseling'));
    }

    // FORM EDIT (HANYA JIKA STATUS MENUNGGU)
    public function edit($id)
    {
        $siswa = Auth::guard('siswa')->user();
        $konseling = Konseling::where('id', $id)->where('nis', $siswa->nis)->firstOrFail();

        // Cek Status
        if ($konseling->status !== 'Menunggu') {
            return redirect()->route('siswa.konseling.index')
                ->with('error', 'Maaf, pengajuan sudah diproses dan tidak bisa diedit.');
        }

        $guruBk = Guru::where('role', 'guru_bk')->get();
        $orangtua = OrangTua::where('nis', $siswa->nis)->first();

        return view('siswa.konseling.edit', compact('konseling', 'guruBk', 'orangtua', 'siswa'));
    }

    // UPDATE DATA (PUT)
    public function update(Request $request, $id)
    {
        $siswa = Auth::guard('siswa')->user();
        $konseling = Konseling::where('id', $id)->where('nis', $siswa->nis)->firstOrFail();

        if ($konseling->status !== 'Menunggu') {
            return back()->with('error', 'Pengajuan tidak dapat diedit karena sudah diproses.');
        }

        $request->validate([
            'guru_bk_nip'      => 'required|exists:guru,nip',
            'tanggal'          => 'required|date',
            'jam_pengajuan'    => 'required',
            'jenis_layanan'    => 'required|string',
            'topik'            => 'required|string',
            'latar_belakang'   => 'required|string',
            'kegiatan_layanan' => 'required|string',
        ]);

        $konseling->update([
            'guru_bk_nip'      => $request->guru_bk_nip,
            'tanggal'          => $request->tanggal,
            'jam_pengajuan'    => $request->jam_pengajuan,
            'jenis_layanan'    => $request->jenis_layanan,
            'topik'            => $request->topik,
            'latar_belakang'   => $request->latar_belakang,
            'kegiatan_layanan' => $request->kegiatan_layanan,
        ]);

        return redirect()->route('siswa.konseling.index')
            ->with('success', 'Data pengajuan berhasil diperbarui.');
    }
}