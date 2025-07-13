<?php

namespace App\Http\Middleware; // ✅ ADD THIS LINE - THIS IS THE ENTIRE FIX

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login.form');
        }

        // 2. Check if the logged-in user's role is 'admin'
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized Action.');
        }

        // 3. If they are an admin, allow the request to continue
        return $next($request);
    }
}