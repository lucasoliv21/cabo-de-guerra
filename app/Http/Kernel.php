<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // Middleware global
    protected $middleware = [
        \App\Http\Middleware\CorsMiddleware::class,
        // outros middlewares...
    ];

    // Grupos de middleware
    protected $middlewareGroups = [
        'web' => [
            // ...
        ],
        'api' => [
            // ...
        ],
    ];

    // Middleware de rota (alias)
    protected $routeMiddleware = [
        'cors' => \App\Http\Middleware\CorsMiddleware::class,
        // ...
    ];
}
