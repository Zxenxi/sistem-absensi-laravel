<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| File bootstrap ini bertanggung jawab untuk membuat instance aplikasi Laravel.
| Instance ini mengikat semua komponen Laravel dan menjadi container IoC untuk aplikasi.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Override Lokasi Storage
|--------------------------------------------------------------------------
|
| Di Vercel, sistem file aplikasi (misalnya di /var/task) bersifat read-only kecuali
| folder /tmp. Kita mengarahkan Laravel agar menggunakan /tmp/storage untuk menulis file
| seperti log, cache, dan lain-lain.
|
*/
$app->useStoragePath('/tmp/storage');

/*
|--------------------------------------------------------------------------
| Override Lokasi Cache Bootstrap
|--------------------------------------------------------------------------
|
| Secara default, Laravel akan menulis file cache (seperti konfigurasi, routes, dan package manifest)
| ke folder bootstrap/cache yang berada di basePath (read-only di Vercel).
| Solusinya: kita override binding container "path.bootstrap.cache" agar menunjuk ke folder writable
| misalnya /tmp/bootstrap/cache.
|
*/
$cachePath = '/tmp/bootstrap/cache';
if (! is_dir($cachePath)) {
    mkdir($cachePath, 0777, true);
}
$app->instance('path.bootstrap.cache', $cachePath);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Berikut binding penting agar Laravel dapat meng-handle HTTP request, command-line,
| dan exception secara benar.
|
*/
$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| Kembalikan instance aplikasi agar file entry point (misalnya public/index.php atau api/index.php)
| dapat menggunakannya untuk memproses request.
|
*/
return $app;