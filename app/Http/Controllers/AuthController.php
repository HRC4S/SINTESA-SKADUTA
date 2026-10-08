<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Guru;
use App\Models\Siswa;

class AuthController extends Controller
{
    /**
     * 🔹 Login untuk guru & siswa
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // ======================
        // 📌 CEK LOGIN GURU
        // ======================
        $guru = Guru::where('nip', $request->username)->first();

        if ($guru && Hash::check($request->password, $guru->password)) {
            Auth::guard('guru')->login($guru);
            $request->session()->regenerate();

            // ➤ Jika guru adalah wali kelas (kolom tidak NULL)
            if (!empty($guru->walikelas)) {
                return redirect()->route('wali.dashboard');
            }

            // ➤ Jika bukan wali kelas → gunakan role biasa
            switch ($guru->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'guru_bk':
                    return redirect()->route('bk.dashboard');
                case 'kesiswaan':
                    return redirect()->route('kesiswaan.dashboard');
                default:
                    return redirect()->route('guru.dashboard');
            }
        }

        // ======================
        // 📌 CEK LOGIN SISWA
        // ======================
        $siswa = Siswa::where('nis', $request->username)->first();

        if ($siswa && Hash::check($request->password, $siswa->password)) {
            Auth::guard('siswa')->login($siswa);
            $request->session()->regenerate();

            // Cek password default
            if (Hash::check('siswa123', $siswa->password)) {
                session(['default_password' => true]);
            } else {
                session()->forget('default_password');
            }

            return redirect()->route('siswa.dashboard');
        }

        // ======================
        // 📌 Jika gagal semua
        // ======================
        return back()->withErrors([
            'login_error' => 'NIP/NIS atau password salah.'
        ]);
    }

    /**
     * 🔹 Logout
     */
    public function logout(Request $request)
    {
        if (Auth::guard('guru')->check()) {
            Auth::guard('guru')->logout();
        }

        if (Auth::guard('siswa')->check()) {
            Auth::guard('siswa')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
