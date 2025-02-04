<?php

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

// Selanjutnya, kamu bisa menambahkan konfigurasi atau binding lain yang dibutuhkan.
// Misalnya, load environment, konfigurasi log, dsb.

return $app;