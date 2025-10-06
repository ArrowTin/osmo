<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika belum login
        if (!auth()->check()) {
            // Jika route API → kirim JSON
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Please login first.'], 401);
            }

            // Jika route web → redirect ke login
            return redirect()->route('login');
        }

        return $next($request);
    }
}
