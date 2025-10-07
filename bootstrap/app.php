<?php

use App\Http\Middleware\IsSeller;
use App\Http\Middleware\IsCustomer;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    // Routing
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    // Middleware
    ->withMiddleware(function (Middleware $middleware): void {
        // Registrasi middleware custom
        $middleware->alias([
            'seller' => IsSeller::class,
            'customer' => IsCustomer::class,
        ]);

        // Contoh: middleware global lain bisa ditambahkan di sini
        // $middleware->register('auth', \App\Http\Middleware\Authenticate::class);
    })
    // Exceptions
    ->withExceptions(function (Exceptions $exceptions): void {
        // Kamu bisa handle exception global di sini jika perlu
    })
    ->create();
