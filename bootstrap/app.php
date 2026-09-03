<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Railway (dan reverse proxy lain) meneruskan request ke app sebagai HTTP
        // dengan header X-Forwarded-Proto=https. Percayai semua proxy supaya
        // Laravel tahu request asli HTTPS -> asset()/url() menghasilkan https://,
        // bukan http:// (yang diblokir browser sebagai Mixed Content).
        $middleware->trustProxies(at: '*');

        // Tamu yang mengakses area admin diarahkan ke halaman login admin,
        // bukan route bawaan 'login' (yang tidak ada di app ini).
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
