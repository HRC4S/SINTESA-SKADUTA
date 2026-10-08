<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Tentukan guard aktif
        $guard = Auth::guard('guru')->check() ? 'guru' :
                 (Auth::guard('siswa')->check() ? 'siswa' : null);

        if (!$guard) {
            return redirect()->route('login');
        }

        $user = Auth::guard($guard)->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Cek role
        if (isset($user->role) && !in_array($user->role, $roles)) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
