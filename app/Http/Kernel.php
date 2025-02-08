<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array<int, class-string>
     */
    protected $middleware = [
        // Contoh: \App\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string>>
     */
    protected $middlewareGroups = [
        'web' => [
            // Middleware untuk grup web
        ],

        'api' => [
            // Middleware untuk grup api
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array<string, class-string>
     */
    protected $routeMiddleware = [
        // Middleware per route
    ];
}