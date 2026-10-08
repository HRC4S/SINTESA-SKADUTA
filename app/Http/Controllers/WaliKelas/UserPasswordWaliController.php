<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserPasswordWaliController extends Controller
{
    // ===============================
    // 1. INDEX PASSWORD SISWA DI KELAS walikelas
    // ===============================
    public function index(Request $request)
    {
        
        $guru = auth('guru')->user();
        // Ambil rombel wali kelas dari user login
         $rombel = $guru->walikelas; // ambil rombel wali kelas


        // Filter + pencarian siswa, tapi hanya dalam kelas walikelas
        $siswas = Siswa::where('rombel', $rombel)
            ->when($request->search_siswa, function($q) use ($request) {
                $q->where('nis', 'like', '%' . $request->search_siswa . '%')
                  ->orWhere('nama_lengkap', 'like', '%' . $request->search_siswa . '%');
            })
            ->orderBy('nama_lengkap')
            ->paginate(40);

        return view('walikelas.password.index', compact('siswas', 'rombel'));
    }

    // ===============================
    // 2. FORM EDIT PASSWORD SISWA
    // ===============================
    public function edit($nis)
    {
        $walikelas = Auth::guard('guru')->user();

        $siswa = Siswa::where('nis', $nis)
            ->where('rombel', $walikelas->walikelas)
            ->firstOrFail();

        return view('walikelas.password.edit', compact('siswa'));
    }

    // ===============================
    // 3. UPDATE PASSWORD SISWA
    // ===============================
    public function update(Request $request, $nis)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $walikelas = Auth::guard('guru')->user();

        $siswa = Siswa::where('nis', $nis)
            ->where('rombel', $walikelas->walikelas)
            ->firstOrFail();

        $siswa->password = Hash::make($request->password);
        $siswa->save();

        return back()->with('success', 'Password siswa berhasil diperbarui!');
    }

    // ===============================
    // 4. UPDATE PASSWORD walikelas SENDIRI
    // ===============================
    public function updateSelf(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $walikelas = Auth::guard('guru')->user();

        if (!Hash::check($request->current_password, $walikelas->password)) {
            return back()->with('error', 'Password lama salah');
        }

        $walikelas->password = Hash::make($request->new_password);
        $walikelas->save();

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
