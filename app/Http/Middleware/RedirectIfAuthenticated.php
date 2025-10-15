<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Ubah ke route tujuanmu
                return redirect('/dashboard');
            }
        }


        return $next($request);
    }
}
