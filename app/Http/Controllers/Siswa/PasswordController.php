<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function edit()
{
    $siswa = Auth::guard('siswa')->user(); // ✅ ambil data siswa yang sedang login
    return view('siswa.password.edit', compact('siswa'));
}


    public function update(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $siswa = Auth::guard('siswa')->user();

    // Cek password lama
    if (!Hash::check($request->current_password, $siswa->password)) {
        return back()->withErrors(['current_password' => 'Password lama salah.']);
    }

    // Update password dan ubah status default
    $siswa->update([
        'password' => Hash::make($request->new_password),
        'is_default_password' => false, // 🔥 ubah jadi false biar alert hilang
    ]);
    session()->forget('default_password');

    return redirect()->route('siswa.dashboard')->with('success', 'Password berhasil diubah!');
}
}
