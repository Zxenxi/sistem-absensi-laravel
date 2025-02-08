<?php
// File: api/index.php

// Muat autoloader Composer
require __DIR__ . '/../vendor/autoload.php';

// Muat bootstrap aplikasi Laravel
$app = require __DIR__ . '/../bootstrap/app.php';

// Buat instance kernel untuk menangani HTTP request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Tangkap request yang masuk
$request = Illuminate\Http\Request::capture();

// Proses request dan dapatkan response
$response = $kernel->handle($request);

// Kirim response ke browser
$response->send();

// Jalankan termination callbacks
$kernel->terminate($request, $response);