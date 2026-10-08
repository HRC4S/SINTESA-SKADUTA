<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekAksesEditSiswa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $siswa = auth('siswa')->user();

        if ($siswa && !$siswa->akses_edit) {
            return redirect()->back()->with('error', 'Akses edit Anda telah dinonaktifkan. Hubungi admin untuk membuka kembali.');
        }

        return $next($request);
    }
}
