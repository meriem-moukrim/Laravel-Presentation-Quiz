<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - Configuration du Routage
|--------------------------------------------------------------------------
| Ce fichier définit toutes les "portes d'entrée" de l'application.
| Chaque route lie une URL spécifique à une action dans un contrôleur.
| Le routage est le premier pilier de Laravel pour diriger les requêtes.
*/

// =========================================================================
// 🏠 Section Accueil & Présentation
// =========================================================================
// Cette route gère l'affichage du cours interactif.
// Elle appelle la méthode 'index' du PresentationController.
Route::get('/', [PresentationController::class, 'index'])->name('home');


// =========================================================================
// 🔐 Authentification via Google (Socialite)
// =========================================================================
// Utilise le package Socialite pour une connexion moderne sans mot de passe.
// Le préfixe 'auth' permet d'organiser les URLs (ex: /auth/google).
Route::prefix('auth')->name('auth.')->group(function () {

    // Redirige l'utilisateur vers la page de connexion de Google.
    Route::get('google', [AuthController::class, 'redirectToGoogle'])->name('google');

    // Récupère les informations de l'utilisateur renvoyées par Google.
    Route::get('google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
});

// Route de déconnexion : détruit la session utilisateur.
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


// =========================================================================
// 🎮 Système de Quiz - Logique Métier
// =========================================================================

// Point d'entrée du quiz : affiche soit le formulaire, soit le bouton de démarrage.
Route::get('/quiz', [QuizController::class, 'index'])->name('quiz');

// Route 'login' : indispensable pour Laravel. 
// Si un utilisateur non-connecté tente d'accéder à une page protégée, 
// le middleware 'auth' le redirigera automatiquement ici.
Route::get('/login', function () {
    return redirect()->route('quiz');
})->name('login');


// 🛡️ Zone Sécurisée (Middleware)
// Ici, on utilise un "Groupe de Middleware" :
// 1. 'auth' : Vérifie que l'utilisateur est bien identifié.
// 2. 'log.quiz' : Notre middleware personnalisé qui enregistre l'activité.
Route::middleware(['auth', 'log.quiz'])->prefix('quiz')->name('quiz.')->group(function () {

    // L'interface de jeu : charge les questions et lance la partie.
    Route::get('/play', [QuizController::class, 'play'])->name('play');

    // Sauvegarde du score : protégée par un 'throttle' (limiteur de débit).
    // Empêche un utilisateur d'envoyer 1000 scores par seconde (protection anti-spam).
    Route::post('/score', [QuizController::class, 'storeScore'])->middleware('throttle:10,1')->name('score');

    // API Classement : renvoie les meilleurs scores en format JSON pour AJAX.
    Route::get('/leaderboard', [QuizController::class, 'leaderboard'])->name('leaderboard');
});
