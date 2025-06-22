<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['api.rate_limit' => \App\Http\Middleware\ApiRateLimit::class]);
        $middleware->group('api', [
            'api.rate_limit',
        ]);
        $middleware->group('web', [
            'api.rate_limit',
        ]);
    })
    ->withSchedule(function ($schedule) {
        $schedule->command('cache:refresh-daily')->dailyAt('00:00');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
