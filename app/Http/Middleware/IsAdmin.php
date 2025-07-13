<?php


// app/Http/Middleware/IsAdmin.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') { // Adjust 'role' field and 'admin' value as needed
            return $next($request);
        }
        abort(403, 'غير مصرح بالدخول.');
    }
}
