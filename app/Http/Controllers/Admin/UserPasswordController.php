<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserPasswordController extends Controller
{
    public function index(Request $request)
    {
        // ===============================
        // 1. FILTER & PENCARIAN SISWA
        // ===============================
        $siswas = Siswa::when($request->search_siswa, function($q) use ($request) {
                $q->where('nis', 'like', '%' . $request->search_siswa . '%')
                  ->orWhere('nama_lengkap', 'like', '%' . $request->search_siswa . '%');
            })
            ->orderBy('nama_lengkap')
            ->paginate(10, ['*'], 'siswa_page');

        // ===============================
        // 2. FILTER & PENCARIAN GURU
        // ===============================
        $gurus = Guru::when($request->search_guru, function($q) use ($request) {
                $q->where('nip', 'like', '%' . $request->search_guru . '%')
                  ->orWhere('nama', 'like', '%' . $request->search_guru . '%');
            })
            ->orderBy('nama')
            ->paginate(10, ['*'], 'guru_page');

        // ===============================
        // KIRIM KE VIEW
        // ===============================
        return view('admin.password.index', compact('siswas', 'gurus'));
    }

    // ===============================
    // FORM EDIT PASSWORD (SISWA/GURU)
    // ===============================
    public function edit($type, $id)
    {
        if ($type === 'siswa') {
            $user = Siswa::where('nis', $id)->firstOrFail();
        } else {
            $user = Guru::where('nip', $id)->firstOrFail();
        }

        return view('admin.password.edit', compact('user', 'type'));
    }

    // ===============================
    // UPDATE PASSWORD SISWA/GURU
    // ===============================
    public function update(Request $request, $type, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        if ($type === 'siswa') {
            $user = Siswa::where('nis', $id)->firstOrFail();
        } else {
            $user = Guru::where('nip', $id)->firstOrFail();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    // ===============================
    // UPDATE PASSWORD ADMIN SENDIRI
    // ===============================
    public function updateSelf(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->with('error', 'Password lama salah');
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return back()->with('success', 'Password admin berhasil diperbarui!');
    }
}
