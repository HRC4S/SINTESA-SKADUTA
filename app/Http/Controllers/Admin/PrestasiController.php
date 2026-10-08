<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use App\Models\Siswa;


class PrestasiController extends Controller
{
    public function index(Request $request)
{
    $prestasi = Prestasi::with('siswa');

    // Filter Jenis
    if ($request->jenis) {
        $prestasi->where('jenis', $request->jenis);
    }

    $prestasi = $prestasi->get();

    return view('admin.prestasi.index', compact('prestasi'));
}


    public function create()
{
    $siswas = Siswa::orderBy('nama_lengkap', 'asc')->get();

    return view('admin.prestasi.create', compact('siswas'));
}


    public function store(Request $request)
{
    $request->validate([
        'nis' => 'required',
        'judul' => 'required',
        'jenis' => 'required',
        'file' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
        'tanggal_prestasi' => 'required|date',
    ]);

    // Upload file
    $filename = null;
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time() . "." . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/prestasi'), $filename);
    }

    Prestasi::create([
        'nis' => $request->nis,   // 🟢 WAJIB diganti ini
        'judul' => $request->judul,
        'jenis' => $request->jenis,
        'deskripsi' => $request->deskripsi,
        'file' => $filename,
        'link' => $request->link,
        'tanggal_prestasi' => $request->tanggal_prestasi,
    ]);

    return redirect()
        ->route('admin.prestasi.index')
        ->with('success', 'Data prestasi berhasil ditambahkan!');
}

    public function show($id)
    {
        $prestasi = Prestasi::findOrFail($id);
        return view('admin.prestasi.show', compact('prestasi'));
    }

    public function destroy($id)
    {
        $prestasi = Prestasi::findOrFail($id);

        if ($prestasi->file && file_exists(public_path('uploads/prestasi/' . $prestasi->file))) {
            unlink(public_path('uploads/prestasi/' . $prestasi->file));
        }

        $prestasi->delete();

        return back()->with('success', 'Prestasi berhasil dihapus');
    }
}
