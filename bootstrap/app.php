<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'collecteur' => \App\Http\Middleware\CollecteurMiddleware::class,
            'menage' => \App\Http\Middleware\MenageMiddleware::class,
            'partenaire' => \App\Http\Middleware\PartenaireMiddleware::class,
        ]);
    })

    ->withSchedule(function ($schedule) {
        $schedule->command('collecteur:calculer-primes')->monthlyOn(1, '00:00');
    })

    ->withSchedule(function ($schedule) {
        $schedule->command('abonnements:verifier')->dailyAt('08:00');
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
