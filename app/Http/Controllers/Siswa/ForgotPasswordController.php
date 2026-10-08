<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /**
     * 🔹 Menampilkan form lupa password siswa
     */
    public function showForm()
    {
        return view('siswa.password.lupa');
    }

    /**
     * 🔹 Proses reset password siswa
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'nis' => 'required',
            'password_baru' => 'required|min:6|confirmed',
        ]);

        $siswa = Siswa::where('nis', $request->nis)->first();

        if (!$siswa) {
            return back()->withErrors(['nis' => 'NIS tidak ditemukan.']);
        }

        $siswa->password = Hash::make($request->password_baru);
        $siswa->save();

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login kembali.');
    }
}
