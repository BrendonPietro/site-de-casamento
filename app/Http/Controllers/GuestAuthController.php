<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuestAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nome_completo' => 'required|string',
            'senha' => 'required|string',
        ]);

        if (Auth::guard('guest')->attempt([
            'nome_completo' => $request->nome_completo,
            'password' => $request->senha,
        ], true)) {
            $request->session()->regenerate();

            $guest = Auth::guard('guest')->user();
            if ($guest) {
                DB::table('guests')->where('id', $guest->getAuthIdentifier())->update(['ultimo_acesso' => now()]);
            }

            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Nome ou senha incorretos.',
            ], 422);
        }

        return back()->withErrors(['login' => 'Nome ou senha incorretos.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('guest')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
