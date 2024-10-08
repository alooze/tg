<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Console\Commands\GrabMessages;
use App\Console\Commands\ParseMessages;
use App\Console\Commands\SendPosts;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            '/read',
            '/webhook',
        ]);
    })
    ->withCommands([
        GrabMessages::class,
        ParseMessages::class,
        SendPosts::class,
    ])
    // ->withSchedule(function (Schedule $schedule) {
    //     $schedule->call(new DeleteRecentUsers)->daily();
    // })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
