<?php

use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 1. GLOBAL - berjalan pada setiap request (fase sesudah menambah header keamanan)
        $middleware->append(SecurityHeaders::class);

        // 2. GRUP 'web' - memastikan pengguna yang terautentikasi masih berstatus aktif
        $middleware->web(append: [
            EnsureUserIsActive::class,
        ]);

        // 3. ALIAS - nama singkat untuk diterapkan per rute atau grup rute
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
            'active' => EnsureUserIsActive::class,
        ]);

        // 4. PRIORITAS - menjamin urutan eksekusi: auth -> active -> role
        $middleware->appendToPriorityList(
            AuthenticatesRequests::class,
            EnsureUserIsActive::class,
        );
        $middleware->appendToPriorityList(
            EnsureUserIsActive::class,
            EnsureUserHasRole::class,
        );

        $middleware->redirectTo(
            guests: fn () => route('login'),
            users: fn () => route('produk.index'),
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
