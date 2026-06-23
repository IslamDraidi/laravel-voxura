<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'store.owner' => \App\Http\Middleware\StoreOwnerMiddleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'webhooks/payment/*',
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('tryon:cleanup')->daily();
        $schedule->command('stores:send-reminders')->dailyAt('09:00');
        $schedule->command('stores:suspend-expired')->dailyAt('00:00');
        $schedule->command('stores:auto-feature')->weeklyOn(1, '08:00');
        $schedule->command('stores:topup-3d-credits')->monthlyOn(1, '00:01');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();