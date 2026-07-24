<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'check.blocked' => \App\Http\Middleware\CheckBlockedUser::class,
    ]);

    // Har authenticated request pe ye middleware chale
    $middleware->appendToGroup('web', \App\Http\Middleware\CheckBlockedUser::class);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
