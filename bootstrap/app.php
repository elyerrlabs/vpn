<?php

use Elyerr\LaravelRuntime\App\Application;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withCommands([
        \Vpn\App\Console\Commands\KeysGenerator::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {})->create();
