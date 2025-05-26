<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd(Auth::user(),Auth::check()); // Debugging output
        if (!Auth::check()) {
          return redirect()->route('auth.login-page')->with('login-error', 'Your session has ended. Please log in to continue.');
        }

        return $next($request);
    }
}
