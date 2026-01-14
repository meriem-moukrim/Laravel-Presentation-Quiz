<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * LogQuizActivity - Middleware de surveillance (Audit)
 * 
 * Ce middleware est un "filtre" qui s'exécute lors de chaque requête vers le quiz.
 * Son rôle est d'enregistrer qui accède au jeu et à quel moment.
 */
class LogQuizActivity
{
    /**
     * Gère la requête entrante.
     * 
     * @param  \Illuminate\Http\Request  $request : L'objet contenant les infos de la requête.
     * @param  \Closure  $next : La fonction qui permet de passer au middleware suivant ou au contrôleur.
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * FONCTIONNEMENT :
     * 1. On vérifie si l'utilisateur est connecté.
     * 2. Si oui, on écrit une ligne dans les fichiers de "logs" (storage/logs/laravel.log).
     * 3. On laisse la requête continuer son chemin via $next($request).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // Enregistrement dans les logs système
            \Illuminate\Support\Facades\Log::info('Accès Quiz', [
                'id_utilisateur' => auth()->id(),
                'nom' => auth()->user()->name,
                'horodatage' => now()->toDateTimeString()
            ]);
        }

        return $next($request);
    }
}
