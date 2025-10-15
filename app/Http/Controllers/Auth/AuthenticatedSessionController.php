<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan form login (untuk web)
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Proses login (untuk web & API)
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Coba autentikasi
        if (!Auth::attempt($credentials)) {
            // Jika request dari API
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Username atau password salah'
                ], 401);
            }

            // Jika dari web
            return back()->withErrors([
                'username' => 'Username atau password salah.',
            ])->onlyInput('username');
        }
        $user = Auth::user();

        // Jika request dari API → kirim token Sanctum
        if ($request->expectsJson()) {
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'token' => $token,
                'user' => $user,
            ]);
        }

        // Jika dari web → redirect sesuai role
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    /**
     * Logout (untuk web & API)
     */
    public function destroy(Request $request)
    {
        // Jika API
        if ($request->expectsJson()) {
            $user = $request->user();
            if ($user && $user->currentAccessToken()) {
                $user->currentAccessToken()->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil',
            ]);
        }

        // Jika web
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
