<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();


    /*
    |--------------------------------------------------------------------------
    | Create The Application
    |--------------------------------------------------------------------------
    |
    | The first thing we will do is create a new Laravel application instance
    | which serves as the "glue" for all the components of Laravel, and is
    | the IoC container for the system binding all of the various parts.
    |
    */
    
    $app = new Illuminate\Foundation\Application(
        $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
    );
    
    // Override lokasi storage agar menggunakan direktori /tmp (writable di Vercel)
    $app->useStoragePath('/tmp/storage');
    
    // Pastikan direktori /tmp/storage dan subdirektori yang diperlukan sudah dibuat
    // Jika perlu, kamu bisa menambahkan skrip pembuatan direktori dalam proses build/deploy.
    
    // Lanjutkan dengan sisa bootstrap...
    // ...
    
    return $app;
    