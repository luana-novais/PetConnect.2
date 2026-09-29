<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
    $middleware->trustProxies(at: '*');
    $middleware->alias([
        'role.adm' => \App\Http\Middleware\RoleAdmMiddleware::class,
        'role.cli' => \App\Http\Middleware\RoleCliMiddleware::class,
        'role.pro' => \App\Http\Middleware\RoleProMiddleware::class,
    ]);
    }) 
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();