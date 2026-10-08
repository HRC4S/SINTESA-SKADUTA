<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Konseling;
use App\Models\Siswa;
use Illuminate\Http\Request;

class KonselingController extends Controller
{
    public function index()
    {
        // Ambil semua data konseling terbaru
        $konselings = Konseling::orderBy('tanggal', 'desc')->paginate(10);

        return view('admin.konseling.index', compact('konselings'));
    }

    public function create()
    {
        // Ambil daftar siswa untuk dropdown
        $siswas = Siswa::orderBy('nama_lengkap', 'asc')->get();

        return view('admin.konseling.create', compact('siswas'));
    }

public function store(Request $request)
{
    $request->validate([
        'siswa_nis' => 'required|exists:siswas,nis',
        'rombel'    => 'required|string',
        'tanggal'   => 'required|date',
        'catatan'   => 'nullable|string',
    ]);

    // Ambil data siswa
    $siswa = Siswa::where('nis', $request->siswa_nis)->firstOrFail();

    Konseling::create([
        'nis'               => $siswa->nis,
        'nama_siswa'        => $siswa->nama_lengkap,
        'kelas'             => $request->rombel,
        'nama_ortu'         => $siswa->nama_ortu ?? '-',
        'alamat_ortu'       => $siswa->alamat_ortu ?? '-',
        'no_telp_ortu'      => $siswa->no_telp_ortu ?? '-',
        'tanggal'           => $request->tanggal,
        'topik'             => 'Konseling Umum',
        'latar_belakang'    => $request->catatan,
        'kegiatan_layanan'  => 'Konseling Individu',
        'status'            => 'Menunggu',
        'tanggapan_admin'   => null,
    ]);

    return redirect()
        ->route('admin.konseling.index')
        ->with('success', 'Data konseling berhasil ditambahkan.');
}

    public function edit($id)
    {
        $konseling = Konseling::findOrFail($id);
        return view('admin.konseling.edit', compact('konseling'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nis' => 'required|string',
            'nama_siswa' => 'required|string',
            'kelas' => 'required|string',
            'nama_ortu' => 'required|string',
            'alamat_ortu' => 'required|string',
            'no_telp_ortu' => 'required|string',
            'tanggal' => 'required|date',
            'topik' => 'required|string',
            'latar_belakang' => 'nullable|string',
            'kegiatan_layanan' => 'nullable|string',
            'status' => 'required|string|in:Menunggu,Disetujui,Ditolak',
            'tanggapan_admin' => 'nullable|string',
        ]);

        $konseling = Konseling::findOrFail($id);
        $konseling->update($request->all());

        return redirect()->route('admin.konseling.index')
                         ->with('success', 'Data konseling berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $konseling = Konseling::findOrFail($id);
        $konseling->delete();

        return redirect()->route('admin.konseling.index')
                         ->with('success', 'Data konseling berhasil dihapus.');
    }

    // Method untuk memproses persetujuan (Setuju/Tolak)
public function proses(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:Disetujui,Ditolak',
        'tanggapan_admin' => 'required|string|max:255', // Wajib diisi agar siswa jelas
    ]);

    $konseling = Konseling::findOrFail($id);

    $konseling->update([
        'status' => $request->status,
        'tanggapan_admin' => $request->tanggapan_admin, // Simpan pesan admin
    ]);

    return redirect()->back()->with('success', 'Status konseling berhasil diperbarui.');
}
public function show($id)
{
    $konseling = Konseling::findOrFail($id);
    return view('admin.konseling.show', compact('konseling'));
}


}
