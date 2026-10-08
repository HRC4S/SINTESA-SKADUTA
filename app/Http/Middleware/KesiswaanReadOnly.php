<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KesiswaanReadOnly
{
    public function handle(Request $request, Closure $next)
    {
        // Jika role = kesiswaan → hanya boleh GET
        if (auth('guru')->check() && auth('guru')->user()->role === 'kesiswaan') {
            if (! $request->isMethod('get')) {
                return redirect()->back()->with('error', 'Akses ditolak. Anda tidak memiliki izin melakukan perubahan data.');
            }
        }

        return $next($request);
    }
}
