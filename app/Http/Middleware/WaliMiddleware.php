<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WaliMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $guru = auth('guru')->user();

        // Jika tidak login / bukan guru
        if (!$guru) {
            return redirect()->route('login');
        }

        // Harus punya kelas walikelas
        if (!$guru->walikelas) {
            return redirect()->route('login')->with('error', 'Anda bukan wali kelas.');
        }

        return $next($request);
    }
}
