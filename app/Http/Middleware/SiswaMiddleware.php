<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SiswaMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Pastikan siswa sudah login (sesi siswa sudah dibuat)
        if (session('role') !== 'siswa' || !session('siswa_id')) {
            return redirect()->route('login')->withErrors([
                'unauthorized' => 'Silakan login sebagai siswa terlebih dahulu.'
            ]);
        }

        return $next($request);
    }
}
