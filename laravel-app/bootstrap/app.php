<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/*
|--------------------------------------------------------------------------
| Bootstrap de l'Application (Configuration Laravel 12)
|--------------------------------------------------------------------------
| Ce fichier est le point de configuration central. Il définit :
| - Les fichiers de routes utilisés.
| - La configuration des middlewares (filtres).
| - La gestion des exceptions.
*/
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',      // Routes classiques (navigateur)
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Enregistrement d'un ALIAS pour notre middleware personnalisé.
        // Cela permet d'utiliser 'log.quiz' directement dans les routes.
        $middleware->alias([
            'log.quiz' => \App\Http\Middleware\LogQuizActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
