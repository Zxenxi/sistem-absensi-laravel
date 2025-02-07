<?php
// File: api/index.php

// Pastikan kamu mengubah path jika diperlukan
// Adjust the relative path if necessary
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
// Then proceed to handle the request...


$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);