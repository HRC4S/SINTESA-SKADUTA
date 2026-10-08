<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if ($guard) {
            if (!Auth::guard($guard)->check()) {
                return redirect()->route('login');
            }

            Auth::shouldUse($guard);
            return $next($request);
        }

        // Jika tidak spesifik guard, cek semua
        foreach (['guru', 'siswa'] as $g) {
            if (Auth::guard($g)->check()) {
                Auth::shouldUse($g);
                return $next($request);
            }
        }

        return redirect()->route('login');
    }
}
