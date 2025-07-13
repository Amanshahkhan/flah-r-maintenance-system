<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RepresentativeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated via the 'representative' guard
        if (!Auth::guard('representative')->check()) {
            
            // If not, redirect them to the main, unified login page.
            // This is the only change you need.
            return redirect()->route('login.form');
        }

        // If they are a logged-in representative, allow them to proceed.
        return $next($request);
    }
}