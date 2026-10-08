<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        /**
         * 🔹 Global Middleware (jalan di semua request)
         */
        $middleware->use([
            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        ]);

        /**
         * 🔹 Middleware Groups
         */
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->group('api', [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        /**
         * 🔹 Route Middleware Alias (bisa dipanggil di route)
         * Pastikan semua middleware custom ada di App\Http\Middleware
         */
        $middleware->alias([
            'siswa' => \App\Http\Middleware\SiswaMiddleware::class,
            'auth'     => \App\Http\Middleware\Authenticate::class,
            'role'     => \App\Http\Middleware\RoleMiddleware::class,
            'kesiswaan.readonly' => \App\Http\Middleware\KesiswaanReadOnly::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'cekAksesEdit' => \App\Http\Middleware\CekAksesEditSiswa::class,
            'walikelas' => \App\Http\Middleware\WaliMiddleware::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
