<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        // Coba autentikasi dengan semua guard yang disediakan (misal: web, api)
        if (!Auth::guard($guards[0] ?? 'web')->check()) {
            // Jika request dari API → balas JSON
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login first.'
                ], 401);
            }

            // Jika request dari web → redirect ke login page
            return redirect()->route('login');
        }

        return $next($request);
    }
}
