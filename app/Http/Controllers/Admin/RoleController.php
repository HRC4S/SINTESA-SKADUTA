<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Tampilkan daftar (variabel dikirim sebagai $roles supaya sesuai view).
     */
    public function index(Request $request)
{
    $search = $request->search;
    $role = $request->role;

    // Jika role = siswa (atau koor_kelas) → ambil dari tabel siswa
    if ($role === 'Siswa' || strtolower((string) $role) === 'koor_kelas') {
        $query = Siswa::query();

        if (strtolower((string) $role) === 'koor_kelas') {
            $query->where('role', 'koor_kelas');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('nis', 'like', "%$search%");
            });
        }

        $roles = $query->orderBy('nama_lengkap')->get();
        return view('admin.role.index', compact('roles'));
    }

    // Selain siswa — admin, guru_bk, guru, kesiswaan → tabel guru
    $query = Guru::query();

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhere('nip', 'like', "%$search%");
        });
    }

    if ($role) {
        $query->where('role', $role);
    }

    $roles = $query->orderBy('nama')->get();
    return view('admin.role.index', compact('roles'));
}

     public function create()
    {
        // Menampilkan form tambah role baru
        return view('admin.role.create');
    }

public function store(Request $request)
{
    $request->validate([
        'nama_pengguna' => 'required|string|max:255',
        'nip_nis' => 'required',
        'email' => 'required|email',
        'role' => 'required',
    ]);

    // Jika role Siswa → simpan ke tabel siswa
    if ($request->role === 'Siswa') {

        Siswa::create([
            'nama_lengkap' => $request->nama_pengguna,
            'nis' => $request->nip_nis,   // ← perbaikan
            'email' => $request->email,
            'password' => bcrypt('siswa123'),
            'role' => 'siswa',
        ]);

    } else {

        Guru::create([
            'nama' => $request->nama_pengguna,
            'nip' => $request->nip_nis, // ← perbaikan
            'email' => $request->email,
            'password' => bcrypt('guru123'),
            'role' => $request->role,
        ]);
    }

    return redirect()->route('admin.role.index')
        ->with('success', 'Data berhasil ditambahkan.');
}

    /**
     * Edit — terima id/nis, cari fleksibel (cari berdasarkan id dulu, kalau tidak ada pakai nis).
     */
public function edit($identifier)
{
    // cari siswa berdasarkan NIS
    $siswa = Siswa::where('nis', $identifier)->first();

    // kalau tidak ditemukan, cari guru berdasarkan NIP
    $guru = Guru::where('nip', $identifier)->first();

    if (!$siswa && !$guru) {
        abort(404, "Data tidak ditemukan.");
    }

    // pilih data mana yg ditemukan
    $role = $siswa ?? $guru;

    // 🔹 Ambil daftar rombel (tabel: siswas)
    $rombels = Siswa::select('rombel')->distinct()->get();

    return view('admin.role.edit', compact('role', 'rombels'));
}

    /**
     * Update role (menerima id/nis di route juga).
     */public function update(Request $request, $identifier)
{
    $request->validate([
        'role' => 'required|string',
        'walikelas' => 'nullable|string'  // tambahkan validasi wali kelas
    ]);

    // cari siswa berdasarkan NIS
    $siswa = Siswa::where('nis', $identifier)->first();

    // cari guru berdasarkan NIP
    $guru = Guru::where('nip', $identifier)->first();

    if ($siswa) {
        // Kalau mau dijadikan koor kelas, pastikan cuma 1 koor aktif per rombel.
        // Koor lama di kelas yang sama otomatis diturunkan jadi siswa biasa.
        if (strtolower($request->role) === 'koor_kelas') {
            Siswa::where('rombel', $siswa->rombel)
                ->where('role', 'koor_kelas')
                ->where('nis', '!=', $siswa->nis)
                ->update(['role' => 'siswa']);
        }

        $siswa->update([
            'role' => strtolower($request->role) === 'koor_kelas' ? 'koor_kelas' : $request->role,
        ]);

    } elseif ($guru) {

        // update role dan wali kelas (khusus guru)
        $guru->update([
            'role' => $request->role,
            'walikelas' => $request->role === 'guru' ? $request->walikelas : null
        ]);

    } else {
        abort(404, "Data tidak ditemukan.");
    }

    return redirect()->route('admin.role.index')->with('success', 'Role berhasil diperbarui!');
}

    /**
     * 🔹 Assign cepat: jadikan siswa (dicari via NIS) sebagai Koor Kelas.
     * Dipakai dari form ringkas di halaman Manajemen Role.
     */
    public function assignKoorByNis(Request $request)
    {
        $request->validate([
            'nis' => ['required', 'string'],
        ], [
            'nis.required' => 'NIS wajib diisi.',
        ]);

        $siswa = Siswa::where('nis', trim($request->nis))->first();

        if (! $siswa) {
            return back()->withInput()->with('error', 'Siswa dengan NIS "' . $request->nis . '" tidak ditemukan.');
        }

        if ($siswa->isKoorKelas()) {
            return back()->with('error', $siswa->nama_lengkap . ' (NIS ' . $siswa->nis . ') sudah menjadi Koor Kelas ' . $siswa->rombel . '.');
        }

        // Koor lama di kelas yang sama otomatis diturunkan jadi siswa biasa.
        $koorLama = Siswa::where('rombel', $siswa->rombel)
            ->where('role', 'koor_kelas')
            ->where('nis', '!=', $siswa->nis)
            ->first();

        Siswa::where('rombel', $siswa->rombel)
            ->where('role', 'koor_kelas')
            ->where('nis', '!=', $siswa->nis)
            ->update(['role' => 'siswa']);

        $siswa->update(['role' => 'koor_kelas']);

        $pesan = $siswa->nama_lengkap . ' (NIS ' . $siswa->nis . ') berhasil dijadikan Koor Kelas ' . $siswa->rombel . '.';
        if ($koorLama) {
            $pesan .= ' Koor sebelumnya (' . $koorLama->nama_lengkap . ') otomatis diturunkan jadi siswa biasa.';
        }

        return redirect()->route('admin.role.index', ['role' => 'koor_kelas'])->with('success', $pesan);
    }

public function destroy($identifier)
{
    // Cari siswa berdasarkan NIS
    $siswa = Siswa::where('nis', $identifier)->first();

    // Cari guru berdasarkan NIP
    $guru = Guru::where('nip', $identifier)->first();

    if ($siswa) {
        $siswa->delete();
        return redirect()->route('admin.role.index')
            ->with('success', 'Siswa berhasil dihapus.');
    }

    if ($guru) {
        $guru->delete();
        return redirect()->route('admin.role.index')
            ->with('success', 'Guru berhasil dihapus.');
    }

    abort(404, 'Data tidak ditemukan.');
}

}
