<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureGuestAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('guest')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Acesso restrito a convidados.'], 401);
            }

            return redirect()->route('home', ['login' => 1]);
        }

        return $next($request);
    }
}
