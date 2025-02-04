<?php
// Pastikan Composer autoloader dimuat terlebih dahulu
require __DIR__ . '/../vendor/autoload.php';

// Panggil bootstrap Laravel
$app = require __DIR__ . '/../bootstrap/app.php';

// Buat instance Kernel untuk menangani HTTP request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Tangkap request yang masuk
$request = Illuminate\Http\Request::capture();

// Proses request melalui kernel dan dapatkan response
$response = $kernel->handle($request);

// Kirim response ke browser
$response->send();

// Jalankan termination callbacks
$kernel->terminate($request, $response);