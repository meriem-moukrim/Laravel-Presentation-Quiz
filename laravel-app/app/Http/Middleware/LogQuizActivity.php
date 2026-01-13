<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to log user activity within the quiz.
 * 
 * Serves as an audit trail to track access to restricted game areas.
 */
class LogQuizActivity
{
    /**
     * Handle an incoming request.
     * 
     * If the user is authenticated, logs their ID, name, and access timestamp.
     * This runs transparently without affecting the request flow.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            \Illuminate\Support\Facades\Log::info('Quiz Access', [
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'time' => now()
            ]);
        }

        return $next($request);
    }
}
