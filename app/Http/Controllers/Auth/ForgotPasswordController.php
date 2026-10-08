<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Masa berlaku link reset (menit).
     */
    private const EXPIRES_MINUTES = 60;

    /**
     * Jeda minimal antar permintaan untuk identifier yang sama (detik),
     * supaya tidak dipakai untuk spam kirim email.
     */
    private const RESEND_COOLDOWN_SECONDS = 60;

    /**
     * 🔹 Tampilkan form "Lupa Password" (universal: guru & siswa).
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * 🔹 Proses kirim link reset password ke email terdaftar.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string', 'max:50'],
        ], [
            'identifier.required' => 'NIP / NIS wajib diisi.',
        ]);

        $identifier = trim($request->identifier);

        // Pesan generik: sengaja SAMA baik akun ditemukan maupun tidak,
        // supaya tidak bisa dipakai untuk menebak NIP/NIS mana yang valid.
        $genericMessage = 'Jika NIP/NIS terdaftar dan memiliki email aktif, '
            . 'link reset password telah dikirim ke email tersebut. Silakan cek inbox / folder spam.';

        [$guard, $user] = $this->findUser($identifier);

        if (! $user || empty($user->email)) {
            // Tidak ketahuan dari luar apakah akunnya ada atau emailnya kosong.
            return back()->with('success', $genericMessage);
        }

        $nama = $guard === 'guru' ? $user->nama : $user->nama_lengkap;

        // 🔒 Cegah spam: kalau baru saja minta link (< cooldown), jangan kirim lagi.
        $recentRequest = DB::table('password_reset_links')
            ->where('guard', $guard)
            ->where('identifier', $identifier)
            ->where('created_at', '>=', now()->subSeconds(self::RESEND_COOLDOWN_SECONDS))
            ->exists();

        if ($recentRequest) {
            return back()->with('success', $genericMessage);
        }

        // Hapus token lama milik akun ini biar tidak menumpuk / tidak dobel aktif.
        DB::table('password_reset_links')
            ->where('guard', $guard)
            ->where('identifier', $identifier)
            ->delete();

        $token = Str::random(64);

        DB::table('password_reset_links')->insert([
            'guard'      => $guard,
            'identifier' => $identifier,
            'email'      => $user->email,
            'token_hash' => hash('sha256', $token),
            'expires_at' => now()->addMinutes(self::EXPIRES_MINUTES),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $resetUrl = route('password.reset', ['token' => $token]) . '?' . http_build_query([
            'guard' => $guard,
            'id'    => $identifier,
        ]);

        try {
            Mail::to($user->email)->send(
                new ResetPasswordMail($nama, $resetUrl, self::EXPIRES_MINUTES)
            );
        } catch (\Throwable $e) {
            // Jangan bocorkan detail error ke user, cukup log untuk admin.
            Log::error('Gagal mengirim email reset password: ' . $e->getMessage());
        }

        return back()->with('success', $genericMessage);
    }

    /**
     * 🔹 Tampilkan form reset password (dari link di email).
     */
    public function showResetForm(Request $request, string $token)
    {
        $guard = $request->query('guard');
        $identifier = $request->query('id');

        $row = $this->validTokenRow($guard, $identifier, $token);

        if (! $row) {
            return redirect()->route('password.request')
                ->with('error', 'Link reset password tidak valid, sudah dipakai, atau sudah kedaluwarsa. Silakan minta link baru.');
        }

        return view('auth.reset-password', [
            'token'      => $token,
            'guard'      => $guard,
            'identifier' => $identifier,
        ]);
    }

    /**
     * 🔹 Proses simpan password baru.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token'      => ['required', 'string'],
            'guard'      => ['required', 'in:guru,siswa'],
            'identifier' => ['required', 'string'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $row = $this->validTokenRow($request->guard, $request->identifier, $request->token);

        if (! $row) {
            return redirect()->route('password.request')
                ->with('error', 'Link reset password tidak valid, sudah dipakai, atau sudah kedaluwarsa. Silakan minta link baru.');
        }

        [, $user] = $this->findUser($request->identifier, $request->guard);

        if (! $user) {
            return redirect()->route('password.request')
                ->with('error', 'Akun tidak ditemukan.');
        }

        $user->password = Hash::make($request->password);

        if ($request->guard === 'siswa') {
            $user->is_default_password = false;
        }

        $user->save();

        // Token hanya boleh dipakai sekali.
        DB::table('password_reset_links')->where('id', $row->id)->delete();

        return redirect()->route('login')
            ->with('status', 'Password berhasil direset. Silakan login dengan password baru kamu.');
    }

    /**
     * Cari akun berdasarkan identifier (NIP untuk guru, NIS untuk siswa).
     * Kalau $guard dikasih, langsung cari di guard itu saja.
     *
     * @return array{0: ?string, 1: \Illuminate\Database\Eloquent\Model|null}
     */
    private function findUser(string $identifier, ?string $guard = null): array
    {
        if ($guard === 'guru') {
            $guru = Guru::where('nip', $identifier)->first();
            return $guru ? ['guru', $guru] : [null, null];
        }

        if ($guard === 'siswa') {
            $siswa = Siswa::where('nis', $identifier)->first();
            return $siswa ? ['siswa', $siswa] : [null, null];
        }

        $guru = Guru::where('nip', $identifier)->first();
        if ($guru) {
            return ['guru', $guru];
        }

        $siswa = Siswa::where('nis', $identifier)->first();
        if ($siswa) {
            return ['siswa', $siswa];
        }

        return [null, null];
    }

    /**
     * Ambil baris token yang masih valid (cocok, belum dipakai, belum kedaluwarsa).
     */
    private function validTokenRow(?string $guard, ?string $identifier, string $token)
    {
        if (! $guard || ! $identifier) {
            return null;
        }

        $row = DB::table('password_reset_links')
            ->where('guard', $guard)
            ->where('identifier', $identifier)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $row) {
            return null;
        }

        if (! hash_equals($row->token_hash, hash('sha256', $token))) {
            return null;
        }

        return $row;
    }
}
