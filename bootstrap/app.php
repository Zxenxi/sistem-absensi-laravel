<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| File bootstrap ini bertanggung jawab untuk membuat instance aplikasi Laravel,
| mengikat semua komponen, dan mengatur path penyimpanan serta cache agar sesuai
| dengan lingkungan Vercel (read-only kecuali /tmp).
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Override Lokasi Storage dan Cache
|--------------------------------------------------------------------------
|
| Di Vercel, sistem file bersifat read-only kecuali direktori /tmp. Oleh karena itu,
| kita override lokasi penyimpanan (storage) dan path cache (konfigurasi, routes, services,
| packages) agar berada di /tmp. Pastikan folder-folder ini dapat dibuat pada runtime.
|
*/
$app->useStoragePath('/tmp/storage');

// Override lokasi cache agar tersimpan di /tmp/bootstrap/cache
$app->useCachedConfigPath('/tmp/bootstrap/cache/config.php');
$app->useCachedRoutesPath('/tmp/bootstrap/cache/routes.php');
$app->useCachedServicesPath('/tmp/bootstrap/cache/services.php');
$app->useCachedPackagesPath('/tmp/bootstrap/cache/packages.php');

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Berikut binding penting agar Laravel dapat menangani HTTP request, menjalankan perintah
| CLI, dan menangani exception.
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
| Setelah semua konfigurasi selesai, kembalikan instance aplikasi.
|
*/
return $app;