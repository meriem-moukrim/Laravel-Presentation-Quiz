<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

// =========================================================================
// 🏠 Home & Presentation Section
// =========================================================================
// Displays the interactive course content loaded from JSON files.
Route::get('/', [PresentationController::class, 'index'])->name('home');


// =========================================================================
// 🔐 Authentication Routes (Laravel Socialite)
// =========================================================================
// Handles Google OAuth validtion and user session management.
Route::prefix('auth')->name('auth.')->group(function () {

    // Redirects user to Google's login page
    Route::get('google', [AuthController::class, 'redirectToGoogle'])->name('google');

    // Handles the callback from Google after successful login
    Route::get('google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
});

// Logs out the current user and invalidates the session
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


// =========================================================================
// 🎮 Quiz System Routes
// =========================================================================

// Public Entry Point: Displays the login prompt or redirect to game if auth
Route::get('/quiz', [QuizController::class, 'index'])->name('quiz');

// Fallback Route: Redirects unauthenticated users to the main quiz page
// Required by the 'auth' middleware when a user tries to access protected pages.
Route::get('/login', function () {
    return redirect()->route('quiz');
})->name('login');


// 🛡️ Protected Game Area
// Middleware:
// - 'auth': Ensures user is logged in.
// - 'log.quiz': Custom middleware to log user activity/access.
Route::middleware(['auth', 'log.quiz'])->prefix('quiz')->name('quiz.')->group(function () {

    // 🎮 The Game Interface
    // Loads the quiz questions and displays the play view.
    Route::get('/play', [QuizController::class, 'play'])->name('play');

    // 💾 Score Submission API
    // - Uses 'throttle:10,1' to limit requests (10 per minute) for security.
    // - Validates and saves the score to the database.
    // - Invalidates the leaderboard cache if a high score is updated.
    Route::post('/score', [QuizController::class, 'storeScore'])->middleware('throttle:10,1')->name('score');

    // 🏆 Leaderboard API
    // Returns the top 5 players, utilized by the frontend via AJAX.
    // Results are cached for performance.
    Route::get('/leaderboard', [QuizController::class, 'leaderboard'])->name('leaderboard');
});
