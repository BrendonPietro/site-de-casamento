<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePanelOwner
{
    /**
     * Garante que apenas noivo/noiva acessem o painel.
     *
     * - Convidado comum (logado) tentando entrar → redireciona pro site.
     * - Não logado → segue o fluxo do Filament (tela de login do painel).
     * - Noivo/noiva → segue normalmente.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('guest')->user();

        if ($user && ! $user->isDono()) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
