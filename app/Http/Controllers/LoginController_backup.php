<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController_backup extends Controller
{
    /**
     * Constructor untuk menerapkan middleware 'guest' pada semua route,
     * kecuali 'logout' agar user yang sudah login tidak bisa akses halaman login lagi.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Proses login user (admin / siswa / bk)
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // Coba login dengan username & password
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();

            // Arahkan sesuai role
            $userRole = Auth::user()->role;

            if ($userRole === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($userRole === 'siswa') {
                return redirect()->intended(route('siswa.dashboard'));
            } elseif ($userRole === 'bk') {
                return redirect()->intended('/bk/dashboard');
            } elseif ($userRole === 'guru') {
                return redirect()->intended('/guru/dashboard');
            } elseif ($userRole === 'walikelas') {
                return redirect()->intended('/walikelas/dashboard');
            } elseif ($userRole === 'kesiswaan') {
                return redirect()->intended('/kesiswaan/dashboard');
             }

            // Jika role tidak dikenal, logout dan kembali ke login
            Auth::logout();
            return redirect()->route('login')->withErrors(['username' => 'Role tidak dikenali.']);
        }

        // Jika gagal login
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    /**
     * Logout user dan hapus session
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
